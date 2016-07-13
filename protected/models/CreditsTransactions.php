<?php

/**
 * This is the model class for table "{{credits_transactions}}".
 *
 * The followings are the available columns in table '{{credits_transactions}}':
 * @property string $buy_id
 * @property string $descriptions
 * @property string $price
 * @property string $user_price
 *
 * The followings are the available model relations:
 * @property Buys $buy
 */
class CreditsTransactions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{credits_transactions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buy_id,user_id', 'required','except' => 'userChange'),
			array('user_id', 'filter','filter'=>'iWebHelper::getUserId','on' => 'userChange'),
			array('price', 'convertToTrueNumber','on' => 'userChange'),
			array('price', 'default' , 'value'=>1000),
            array('user_id,effective', 'numerical', 'integerOnly'=>true),
			array('buy_id', 'length', 'max'=>20),
			array('price, user_price', 'length', 'max'=>10),
			array('descriptions', 'safe'),
			array('price', 'compare','operator'=>'>=', 'compareValue'=>'1000','on' => 'userChange'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('buy_id, descriptions, price, user_price', 'safe', 'on'=>'search'),
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
			'buy' => array(self::BELONGS_TO, 'Buys', 'buy_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'buy_id' => 'شناسه خرید',
			'descriptions' => 'علت تراکنش',
			'price' => 'مبلغ اعتبار نقدی',
			'user_price' => 'مبلغ اعتبار نقدی کاربر بعد از تراکنش',
            'gateway' => 'بانک عامل',
            'sum_price' => 'مبلغ تراکنش',
            'time' => 'تاریخ تراکنش',
            'effective' => 'موثر',
		);
	}

	public function beforeSave(){
		if($this->scenario == 'userChange'){
			$model = new Buys;
			$model->type = Buys::TYPE_CREDIT_CHARGE;
			$model->qty = 1;
			$model->status = Buys::STATUS_DOING;

			$price = $this->price;

			$userPlan = json_decode(Yii::app()->user->plan);
			Yii::import('application.modules.plans.models.*');
			$plan = Plans::model()->findByPk($userPlan->id);

			$discountSections = json_decode($plan->extension_discount_sections,TRUE);
			if(isset($discountSections['credits_buy']) AND $discountSections['credits_buy']){
				$wage = floatval($plan->extension_discount);
				$sumPrice = $price + ceil($price * ($wage / 100));
			}
			else {
				$wage = 0;
				$sumPrice = $price;
			}

			// get tax
			$taxPercent = SiteOptions::getOption('tax');
			$taxPercent = floatval($taxPercent);
			$taxPrice = ceil(($price * $taxPercent) / 100);
			$sumPrice += $taxPrice;

			$factorFields = array(
				array(
					'label' => 'تعداد (مقدار) محصول :',
					'value' => 1,
					'unit'  => 'واحد',
				),
				array(
					'label' => 'قیمت واحد',
					'value' => number_format($price),
					'unit'  => 'تومان',
				),
				array(
					'label' => '+ قیمت کل',
					'value' => number_format($price),
					'unit'  => 'تومان',
				)
			);

			if($wage){
				$factorFields = array_merge($factorFields,array('border',
						array(
							'label' => 'کارمزد',
							'value' => $wage,
							'unit'  => 'درصد',
						),
						array(
							'label' => '+ مبلغ کارمزد',
							'value' => number_format(ceil($price * ($wage / 100))),
							'unit'  => 'تومان',

						))
				);
			}

			$factorFields = array_merge($factorFields,array('border',
					array(
						'label' => 'درصد مالیات بر ارزش افزوده',
						'value' => $taxPercent,
						'unit'  => 'درصد',
					),
					array(
						'label' => '+ مبلغ مالیات بر ارزش افزوده',
						'value' => number_format($taxPrice),
						'unit'  => 'تومان',

					),
					'final' => array(
						'label' => 'مبلغ صورت حساب',
						'value' => $sumPrice,
						'unit'  => 'تومان',
					))
			);

			$model->sum_price = $sumPrice;
			$model->details = json_encode($factorFields);

			if($model->save())
				$this->buy_id = $model->id;
		}
		return parent::beforeSave();
	}

	public function afterSave(){
		if(intval($this->effective) === 1) {
			// sum all buys
			$usersDebtModel = SiteOptions::model()->findByAttributes(array('name' => 'users_debt'));
			$usersDebtModel->value = intval($usersDebtModel->value) + $this->price;
			$usersDebtModel->save();
			// sum all buys end
		}
		return parent::afterSave();
	}

	public function convertToTrueNumber($attribute){
		$this->$attribute = str_replace(',','',$this->$attribute);
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

		$criteria->compare('buy_id',$this->buy_id,true);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('effective',$this->effective);
		$criteria->compare('descriptions',$this->descriptions,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('user_price',$this->user_price,true);
        $criteria->order = 'buy_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditsTransactions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function latestChange(){
		$model = self::model()->find(array(
			'with' => 'buy',
			'condition' => 't.user_id = :userId',
			'params' => array(
				':userId' => Yii::app()->user->userID
			),
			'order' => 'id DESC'
		));
		return $model->buy->date;
	}
}
