<?php

/**
 * This is the model class for table "{{sms_recipients_manual}}".
 *
 * The followings are the available columns in table '{{sms_recipients_manual}}':
 * @property integer $id
 * @property integer $sms_id
 * @property string $mobile
 * @property string $from
 *
 * The followings are the available model relations:
 * @property Sms $sms
 */
class SmsRecipientsManual extends CActiveRecord
{
	public $tableLabel;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sms_recipients_manual}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id, sms_id', 'numerical', 'integerOnly'=>true),
			array('from', 'length', 'max'=>10),
			array('mobile', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sms_id, mobile, from', 'safe', 'on'=>'search'),
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
			'sms' => array(self::BELONGS_TO, 'Sms', 'sms_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sms_id' => 'Sms',
			'mobile' => 'Mobile',
			'from' => 'From',
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
		$criteria->compare('sms_id',$this->sms_id);
		$criteria->compare('mobile',$this->mobile);
		$criteria->compare('from',$this->from,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SmsRecipientsManual the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
