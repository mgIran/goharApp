<?php

/**
 * This is the model class for table "{{sms}}".
 *
 * The followings are the available columns in table '{{sms}}':
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property string $context_type
 * @property string $message_type
 * @property string $attachment_1_title
 * @property string $attachment_1_url
 * @property string $attachment_2_title
 * @property string $attachment_2_url
 * @property integer $sender_id
 * @property string $schedule
 * @property string $send_type
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property MessagesTextsUsersNumbers $sender
 * @property SmsRecipients[] $smsRecipients
 */
class Sms extends CActiveRecord
{
	public $tableLabel;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sms}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, body', 'required'),
			array('sender_id, user_id', 'numerical', 'integerOnly'=>true),
			array('title, attachment_1_title, attachment_2_title', 'length', 'max'=>200),
			array('body', 'length', 'max'=>630),
			array('context_type, message_type, send_type', 'length', 'max'=>20),
			array('attachment_1_url, attachment_2_url', 'length', 'max'=>2000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, body, context_type, message_type, attachment_1_title, attachment_1_url, attachment_2_title, attachment_2_url, sender_id, schedule, send_type, user_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'sender' => array(self::BELONGS_TO, 'MessagesTextsUsersNumbers', 'sender_id'),
			'smsRecipients' => array(self::HAS_MANY, 'SmsRecipients', 'sms_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => 'شناسه',
            'title' => 'عنوان پیامک',
            'body' => 'متن پیامک',
            'context_type' => 'نوع محتوا',
            'message_type' => 'نوع پیام',
            'attachment_1_title' => 'عنوان ضمیمه 1',
            'attachment_1_url' => 'آدرس ضمیمه 1',
            'attachment_2_title' => 'عنوان ضمیمه 2',
            'attachment_2_url' => 'آدرس ضمیمه 2',
            'sender_id' => 'فرستنده',
            'schedule' => 'زمانبندی ارسال',
            'send_type' => 'نوع ارسال',
			'user_id' => 'شناسه کاربر',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('context_type',$this->context_type,true);
		$criteria->compare('message_type',$this->message_type,true);
		$criteria->compare('attachment_1_title',$this->attachment_1_title,true);
		$criteria->compare('attachment_1_url',$this->attachment_1_url,true);
		$criteria->compare('attachment_2_title',$this->attachment_2_title,true);
		$criteria->compare('attachment_2_url',$this->attachment_2_url,true);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('schedule',$this->schedule,true);
		$criteria->compare('send_type',$this->send_type,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
