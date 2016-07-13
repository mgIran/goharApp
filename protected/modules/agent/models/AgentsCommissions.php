<?php

/**
 * This is the model class for table "{{agents_commissions}}".
 *
 * The followings are the available columns in table '{{agents_commissions}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string $price
 * @property integer $date
 */
class AgentsCommissions extends iWebActiveRecord
{
    //const STATUS_REQUEST = 1;
    const STATUS_PAYED = 1;

    public static $statusList = array(
        1 => 'پرداخت شده',
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return '{{agents_commissions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, price', 'required'),
			array('user_id, status, date', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>10),
            array('date', 'filter', 'filter'=>'time'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, status, price, date', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO,'Users','user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'user_id' => 'شناسه کاربر',
			'status' => 'وضعیت',
			'price' => 'مبلغ',
			'date' => 'تاریخ پرداخت',
            'full_name' => 'نام و نام خانوادگی'
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
        if(Yii::app()->user->type == 'admin')
		    $criteria->compare('user_id',$this->user_id);
        else
            $criteria->compare('user_id',Yii::app()->user->userID);
		$criteria->compare('status',$this->status);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('date',$this->date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentsCommissions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
