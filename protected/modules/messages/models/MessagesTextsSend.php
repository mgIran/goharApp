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
class MessagesTextsSend extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{messages_texts_send}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('body, to, sender_id', 'required'),
			array('sender_id, status,webservice_num', 'numerical', 'integerOnly'=>true),
			array('bank', 'length', 'max'=>500),
            array('body', 'length', 'max'=>640),
            array('user_id','filter','filter'=>'iWebHelper::getUserId'),
            array('bank,contacts,webservice','default', 'setOnEmpty' => true, 'value' => null),
            array('send_time , end_time','filter','filter'=>'iWebHelper::jalaliToTime'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, body, to, sender_id, bank, status', 'safe', 'on'=>'search'),
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
            'sender' => array(self::BELONGS_TO,'MessagesTextsUsersNumbers','sender_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'body' => 'متن پیام',
			'to' => 'گیرنده(گان)',
			'sender_id' => 'فرستنده',
			'bank' => 'بانک',
			'status' => 'وضعیت',
            'send_time' => 'تاریخ ارسال',
            'end_time' => 'تا',
            'webservice_num' => 'تعداد مخاطبین گیرنده'
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
		$criteria->compare('body',$this->body,true);
		$criteria->compare('to',$this->to,true);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('bank',$this->bank,true);
		$criteria->compare('status',$this->status);
        $criteria->compare('user_id',Yii::app()->user->userID);

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
