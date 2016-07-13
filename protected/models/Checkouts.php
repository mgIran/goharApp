<?php

/**
 * This is the model class for table "{{checkouts}}".
 *
 * The followings are the available columns in table '{{checkouts}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $price
 * @property integer $req_date
 * @property integer $status
 * @property string $tracking_no
 * @property string $gateway
 * @property integer $pay_date
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class Checkouts extends CActiveRecord
{
	public $reqPrice,$holder_name,$national_id,$iban,$mobile,$email;

    const STATUS_REQUESTED = 1;
    const STATUS_DOING = 2;
    const STATUS_DONE = 3;
    const STATUS_FAILED = 4;
	const STATUS_INCORRECT_IBAN = 5;
	const STATUS_CANCELED = 6;

	public static $statusMessages = array(
		1 => 'درخواست شما ارسال شده است،لطفا منتظر باشید.',
		2 => 'درخواست شما درحال انجام می باشد،لطفا شکیبا باشید.',
		3 => 'اعتبار نقدی شما پرداخت شد،به امید هکاری بیشتر',
		4 => 'به علت ترافیک در درخواست ها قادر به تسویه حساب نبودیم،لطفا دوباره امتحان کنید.',
		5 => 'اطلاعات شماره حساب بانکی و شبای وارد شده شما صحیح نمی باشد.',
		6 => 'پرداخت توسط بانک کنسل شده است.',
	);

	public static $statusSteps = array(
		1 => 'ارسال درخواست من',
		2 => 'ارجاع به بانک',
		3 => 'موفق',
		4 => 'ناموفق',
		5 => 'ناموفق(اطلاعات صحیح نمی باشد)',
		6 => 'کنسل شده',
	);

    public static $statusList = array(
        1 => 'درخواست تسویه',
        2 => 'در حال پرداخت',
        3 => 'پرداخت شده',
        4 => 'پرداخت ناموفق',
		5 => 'خطای اطلاعات بانکی',
		6 => 'کنسل شده',
    );
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{checkouts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$user = Users::model()->findByPk(Yii::app()->user->userID);
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, req_date, status, pay_date,export_id', 'numerical', 'integerOnly'=>true),
			array('wage', 'numerical','on'=>'insert'),
			array('req_date', 'filter', 'filter'=>'time','on'=>'insert'),
			array('price', 'length', 'max'=>10,'except'=>'update'),

			array('price', 'convertToTrueNumber','on'=>'insert'),
			array('price', 'compare','operator'=>'>=', 'compareValue'=> SiteOptions::getOption('minimum_credit'),'on'=>'insert'),
			array('price', 'compare','operator'=>'<=', 'compareValue'=> ((isset($user))?$user->credit_charge:0),'on'=>'insert'),

			array('price','checkBankDetails','on'=>'insert'),

			array('price','checkHasNoRequest','on'=>'insert'),

			array('user_id','filter','filter'=>'iWebHelper::getUserId','on'=>'insert'),

			array('user_id, price, req_date', 'required','except'=>'update'),
			array('tracking_no, gateway', 'length', 'max'=>100,'on'=>'update'),
			array('tracking_no, gateway,pay_date', 'required','on'=>'update'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, price, req_date, status, tracking_no, gateway, pay_date,export_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
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
			'price' => 'مبلغ صورت حساب',
			'req_date' => 'تاریخ درخواست',
			'status' => 'وضعیت',
			'tracking_no' => 'شماره پیگیری',
			'gateway' => 'بانک',
			'pay_date' => 'تاریخ پرداخت',
			'reqPrice' => 'مبلغ درخواستی',
			'wage' => 'کارمزد',

			'holder_name' => 'نان و نام خانوادگی صاحب حساب',
			'national_id' => 'شماره ملی',
			'iban' => 'شماره شبا',
            'mobile'=>'شماره موبایل',
            'email'=>'ایمیل'
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
	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$searchArray = array('holder_name','national_id','iban','mobile','email');
		foreach($searchArray as $search)
			$this->$search = (isset($_GET['Checkouts'][$search]))?$_GET['Checkouts'][$search]:NULL;

		$criteria->compare('holder_name',$this->holder_name,TRUE);
		$criteria->compare('national_id',$this->national_id);
		$criteria->compare('mobile',$this->mobile);
		$criteria->compare('email',$this->email);
		$criteria->compare('iban',$this->iban);
		$criteria->compare('t.status',self::STATUS_REQUESTED);

		$criteria->with = 'user';
		$criteria->together = TRUE;
		$criteria->order="t.id DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria
		));
	}

	public function getExportIds(){
		$this->convertToTrueNumber('price');

		$criteria = new CDbCriteria;

		$criteria->select = "GROUP_CONCAT(id) AS id";

		$criteria->addCondition('req_date < :reqDate');
		$criteria->params[':reqDate'] = $this->req_date;

		$criteria->addCondition('status = :status');
		$criteria->params[':status'] = self::STATUS_REQUESTED;

		$criteria->order = "id ASC";

		$criteria->group = "user_id";
		$criteria->having = 'SUM(price) <= :price';
		$criteria->params[':price'] = $this->price;

		$data = self::model()->find($criteria);

		if(!is_null($data))
			return $data->id;
		else
			return FALSE;
	}

	public function convertToTrueNumber($attribute){
		$this->$attribute = str_replace(',','',$this->$attribute);
	}

	public function checkBankDetails(){
		$user = Users::model()->findByPk(Yii::app()->user->userID);
		$bankDetails = array('account_number', 'iban', 'card_number', 'bank_name','holder_name');
		foreach($bankDetails as $detail){
			if(is_null($user->$detail) OR empty($user->$detail)) {
				$this->addError('price',"لطفا اطلاعات حساب بانکی خود را تکمیل نمایید.");
				return false;
			}
		}
	}

	public function checkHasNoRequest(){
		$check = Checkouts::model()->findByAttributes(array(
			'user_id' => Yii::app()->user->userID,
			'status' => self::STATUS_REQUESTED
		));
		if(!is_null($check)){
			$this->addError('price','درخواست شما ثبت شده است !');
			return false;
		}

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Checkouts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
