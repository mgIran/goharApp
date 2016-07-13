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
class MessagesTextsInbox extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{messages_texts_inbox}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('body, receiver_id, sender', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
            array('body', 'length', 'max'=>640),
            array('date', 'safe'),
            array('user_id','filter','filter'=>'iWebHelper::getUserId'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, body, receiver_id, sender, status', 'safe', 'on'=>'search'),
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
            'receiver' => array(self::BELONGS_TO,"MessagesTextsUsersNumbers",'receiver_id'),
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
			'receiver_id' => 'گیرنده',
			'sender' => 'فرستنده',
			'status' => 'وضعیت',
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
		$criteria->compare('receiver_id',$this->receiver_id,true);
		$criteria->compare('sender',$this->sender);
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
