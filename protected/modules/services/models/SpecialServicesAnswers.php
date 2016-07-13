<?php

/**
 * This is the model class for table "{{special_services_answers}}".
 *
 * The followings are the available columns in table '{{special_services_answers}}':
 * @property integer $id
 * @property integer $service_id
 * @property string $answer_title
 *
 * The followings are the available model relations:
 * @property SpecialServices $service
 * @property SpecialServicesAnswersKeywords[] $specialServicesAnswersKeywords
 * @property SpecialServicesSendedAnswers[] $specialServicesSendedAnswers
 */
class SpecialServicesAnswers extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{special_services_answers}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('service_id, answer_title', 'required'),
			array('service_id', 'numerical', 'integerOnly'=>true),
			array('answer_title', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, service_id, answer_title', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'service' => array(self::BELONGS_TO, 'SpecialServices', 'service_id'),
			'specialServicesAnswersKeywords' => array(self::HAS_MANY, 'SpecialServicesAnswersKeywords', 'answer_id'),
			'specialServicesSendedAnswers' => array(self::HAS_MANY, 'SpecialServicesSendedAnswers', 'answer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'service_id' => 'شناسه سرویس',
			'answer_title' => 'عنوان جواب',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('service_id',$this->service_id);
		$criteria->compare('answer_title',$this->answer_title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SpecialServicesAnswers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
