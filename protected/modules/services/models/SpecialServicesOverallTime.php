<?php

/**
 * This is the model class for table "{{special_services_overall_time}}".
 *
 * The followings are the available columns in table '{{special_services_overall_time}}':
 * @property integer $service_id
 * @property integer $start_time
 * @property integer $interval
 *
 * The followings are the available model relations:
 * @property SpecialServices $service
 */
class SpecialServicesOverallTime extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{special_services_overall_time}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('service_id, start_time, interval,quantity', 'required'),
			array('service_id, start_time, interval,quantity', 'numerical', 'integerOnly'=>true),
            array('interval','length','max'=>3),
            array('quantity','length','max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('service_id, start_time, interval,quantity', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'service_id' => 'شناسه سرویس',
			'start_time' => 'زمان شروع',
            'interval' => 'فاصله زمانی',
            'quantity' => 'تعداد نوبت'
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

		$criteria->compare('service_id',$this->service_id);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('interval',$this->interval);
        $criteria->compare('quantity',$this->quantity);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SpecialServicesOverallTime the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
