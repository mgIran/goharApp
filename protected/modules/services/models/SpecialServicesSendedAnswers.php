<?php

/**
 * This is the model class for table "{{special_services_sended_answers}}".
 *
 * The followings are the available columns in table '{{special_services_sended_answers}}':
 * @property string $id
 * @property string $user
 * @property integer $answer_id
 *
 * The followings are the available model relations:
 * @property SpecialServicesAnswers $answer
 */
class SpecialServicesSendedAnswers extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{special_services_sended_answers}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user', 'required'),
			array('answer_id', 'numerical', 'integerOnly'=>true),
			array('user', 'length', 'max'=>254),
            //array('user', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user, answer_id', 'safe', 'on'=>'search'),
		);
	}

    public function beforeSave(){
        $user = SpecialServicesSendedAnswers::model()->findByAttribute(array('user'=>$this->user));
        if(!is_null($user)){
            if($this->answer->service_id == $user->answer->service_id)
                return FALSE;
        }
        if(is_null($this->answer_id)){
            $temp = SpecialServicesAnswers::model()->findByAttributes(array(
                'answer_title' => 'failed',
                'service_id' => $this->answer->service_id
            ));
            $this->answer_id = $temp->id;
        }

        return parent::beforeSave();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'answer' => array(self::BELONGS_TO, 'SpecialServicesAnswers', 'answer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user' => 'User',
			'answer_id' => 'Answer',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('answer_id',$this->answer_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SpecialServicesSendedAnswers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
