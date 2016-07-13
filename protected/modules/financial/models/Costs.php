<?php

/**
 * This is the model class for table "{{costs}}".
 *
 * The followings are the available columns in table '{{costs}}':
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property string $price
 * @property integer $qty
 * @property integer $start_date
 */
class Costs extends CActiveRecord
{
	public
		$each_installment,
		$debt_sum,
		$last_installment,
		$last_pay,
		$remain_installment_num,
		$last_installment_price;
	const
		STATUS_DISABLE = 0,
		STATUS_ENABLE = 1;

	public static $statusList = array(
		0 => 'غیر فعال',
		1 => 'فعال'
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{costs}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title, price, qty, start_date', 'required'),
			array('status, qty, start_date, payed_qty', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, status, price, qty, start_date', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'title' => 'عنوان هزینه',
			'status' => 'وضعیت',
			'price' => 'مبلغ کل',
			'qty' => 'تعداد اقساط',
			'remain_qty' => 'تعداد اقساط باقیمانده',
			'start_date' => 'تاریخ اولین قسط',
			'each_installment' => 'مبلغ هر قسط',
			'debt_sum' => 'کل مبلغ بدهی',
			'last_installment' => 'تاریخ آخرین قسط',
			'last_pay' => 'تاریخ آخرین پرداخت',
			'remain_installment_num' => 'تعداد اقساط باقی مانده',
			'last_installment_price' => 'مبلغ آخرین قسط'
		);
	}

	public function afterFind(){
		$this->each_installment = intval($this->price / $this->qty);

		$this->last_installment_price = ($this->price - ($this->qty * $this->each_installment)) + $this->each_installment;

		$currentDate = time();

		$this->last_pay = NULL;
		$this->last_installment = $this->start_date;

		$date = $this->start_date;
		$i = 0;

		if(intval($this->status) === self::STATUS_ENABLE)
			while(intval($date) < $currentDate) {
				$this->last_pay = $date;
				$date = strtotime('+1 month',$date);
				$this->last_installment = $date;
				$i++;
			}

		$this->debt_sum = $this->price - ($this->each_installment * $i);
		$this->remain_installment_num = $this->qty - $i;

		return parent::afterFind();
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('start_date',$this->start_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
