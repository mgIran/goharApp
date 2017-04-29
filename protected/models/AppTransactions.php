<?php

/**
 * This is the model class for table "{{app_transactions}}".
 *
 * The followings are the available columns in table '{{app_transactions}}':
 * @property string $id
 * @property double $amount
 * @property string $date
 * @property string $status
 * @property string $description
 * @property integer $order_id
 * @property string $ref_id
 * @property integer $res_code
 * @property string $sale_reference_id
 * @property integer $settle
 * @property string $model_name
 * @property string $model_id
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class AppTransactions extends CActiveRecord
{
	const TRANSACTION_PAID = "paid";
	const TRANSACTION_UNPAID = "unpaid";
	const TRANSACTION_DELETED = "deleted";

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{app_transactions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, res_code, settle, user_id', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('date', 'length', 'max'=>20),
			array('status', 'length', 'max'=>7),
			array('description', 'length', 'max'=>200),
			array('ref_id, sale_reference_id, model_name', 'length', 'max'=>50),
			array('model_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, amount, date, status, description, order_id, ref_id, res_code, sale_reference_id, settle, model_name, model_id, user_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'amount' => 'مقدار',
			'date' => 'تاریخ',
			'status' => 'وضعیت',
			'description' => 'توضیحات',
			'order_id' => 'Order',
			'ref_id' => 'Ref',
			'res_code' => 'Res Code',
			'sale_reference_id' => 'Sale Reference',
			'settle' => 'Settle',
			'model_name' => 'Model Name',
			'model_id' => 'Model',
			'user_id' => 'کاربر',
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
		$criteria->compare('amount',$this->amount);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('ref_id',$this->ref_id,true);
		$criteria->compare('res_code',$this->res_code);
		$criteria->compare('sale_reference_id',$this->sale_reference_id,true);
		$criteria->compare('settle',$this->settle);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('model_id',$this->model_id,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppTransactions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
