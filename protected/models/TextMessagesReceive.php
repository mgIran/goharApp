<?php

/**
 * This is the model class for table "{{text_messages_receive}}".
 *
 * The followings are the available columns in table '{{text_messages_receive}}':
 * @property string $id
 * @property string $sender
 * @property string $to
 * @property string $date
 * @property string $text
 * @property string $sms_id
 * @property integer $prman_user_id
 */
class TextMessagesReceive extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{text_messages_receive}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('sender, to, date, text, sms_id, prman_user_id', 'required'),
			array('prman_user_id', 'numerical', 'integerOnly'=>true),
			array('sender, to, sms_id', 'length', 'max'=>15),
			array('date', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sender, to, date, text, sms_id, prman_user_id', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'sender' => 'فرستنده',
			'to' => 'دریافت کننده',
			'date' => 'تاریخ',
			'text' => 'متن',
			'sms_id' => 'شناسه sms',
			'prman_user_id' => 'نام کاربری prman',
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
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('to',$this->to,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('sms_id',$this->sms_id,true);
		$criteria->compare('prman_user_id',$this->prman_user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TextMessagesReceive the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
