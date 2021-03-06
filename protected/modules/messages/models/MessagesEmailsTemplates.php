<?php

/**
 * This is the model class for table "{{messages_texts_send}}".
 *
 * The followings are the available columns in table '{{messages_texts_send}}':
 * @property integer $id
 * @property string $body
 * @property string $to
 * @property integer $sender_id
 * @property string $bank
 * @property integer $status
 */
class MessagesEmailsTemplates extends iWebActiveRecord
{
    const STATUS_DISABLE = 0,
        STATUS_ENABLE = 1;

    public static $statusList = array(
        0 => 'غیر فعال',
        1 => 'فعال'
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{messages_emails_templates}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('template,title', 'required'),
            array('title', 'unique'),
            array('status', 'numerical', 'integerOnly'=>true),
			array('id, body','safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
            'title' => 'عنوان قالب',
			'template' => 'قالب',
            'status' => 'وضعیت'
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
		$criteria->compare('template',$this->template,true);
        $criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessagesTextsSend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
