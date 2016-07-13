<?php

class MessagesEmailsSend extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{messages_emails_send}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender, body', 'required'),
			array('sender, title', 'length', 'max'=>500),
            array('sender','email'),
            array('body','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
            array('bank,contacts,template_id','default', 'setOnEmpty' => true, 'value' => null),
            array('to','safe'),
            array('user_id','filter','filter'=>'iWebHelper::getUserId'),
            array('send_time , end_time','filter','filter'=>'iWebHelper::jalaliToTime'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sender, title, body', 'safe', 'on'=>'search'),
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
            'template' => array(self::BELONGS_TO,'MessagesEmailsTemplates','template_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'شناسه',
			'sender' => 'فرستنده',
			'title' => 'عنوان پیام',
			'body' => 'متن پیام',
            'to' => 'گیرنده(ها)',
            'send_time' => 'تاریخ ارسال',
            'end_time' => 'تا',
            'template_id' => 'قالب',
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
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);
        $criteria->compare('user_id',Yii::app()->user->userID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessagesEmailsSend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
