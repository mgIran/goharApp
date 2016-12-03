<?php

/**
 * This is the model class for table "{{notifications}}".
 *
 * The followings are the available columns in table '{{notifications}}':
 * @property string $id
 * @property string $subject
 * @property string $send_date
 * @property string $expire_date
 * @property string $content
 * @property string $status
 * @property string $poster
 * @property string $visit
 */
class Notifications extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{notifications}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject, send_date, expire_date, content', 'required'),
			array('subject', 'length', 'max'=>511),
			array('send_date, expire_date', 'length', 'max'=>20),
			array('status', 'length', 'max'=>7),
			array('poster', 'length', 'max'=>500),
			array('visit', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, subject, send_date, expire_date, content, status, poster, visit', 'safe', 'on'=>'search'),
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
			'subject' => 'موضوع',
			'send_date' => 'تاریخ شروع ارسال',
			'expire_date' => 'Expire Date',
			'content' => 'محتوا',
			'status' => 'وضعیت',
			'poster' => 'Poster',
			'visit' => 'Visit',
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
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('send_date',$this->send_date,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('poster',$this->poster,true);
		$criteria->compare('visit',$this->visit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notifications the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
