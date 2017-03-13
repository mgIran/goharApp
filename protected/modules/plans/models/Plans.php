<?php
/**
 * This is the model class for table "{{plans}}".
 *
 * The followings are the available columns in table '{{plans}}':
 * @property integer $id
 * @property string $name
 * @property string $real_price
 * @property string $approved_price
 * @property string $pages
 * @property string $ratio
 * @property double $speed_time_discount
 * @property string $emails
 * @property string $free_special_services
 * @property string $agency
 * @property double $extension_discount
 * @property integer $expire_time
 * @property string $color
 * @property integer $active
 * @property integer $role_id
 * @property integer $deleted
 * @property string $factor_name
 * @property string $agency_profit_sections
 * @property string $extension_discount_sections
 * @property string $required_fields
 * @property integer $disable_login
 * @property integer $max_events_daily
 * @property integer $max_general_filters
 * @property integer $max_favorite_filters
 * @property integer $max_groups_membership
 * @property integer $max_groups_admin_membership
 *
 * The followings are the available model relations:
 * @property UsersRoles $role
 * @property PlansBuys[] $plansBuys
 */
class Plans extends iWebActiveRecord
{
    const
        DELETE_PLAN = 1,
        DISABLE_PLAN = 2,
        FREE_PLAN = 3,
        DEMO_PLAN = 4;
    public $commission_percent;

    public static $deActivePlans = array(1,2,3,4);

    public static $statusList = array(
        0 => 'خارج از لیست فروش',
        1 => 'داخل لیست فروش'
    );

    public static $joinCondition = array(
        1 => '-30 روز عدم خرید در پلن رایگان غیر فعال',
        2 => '90 روز عدم خرید در پلن رایگان فعال',
        3 => 'ثبت نام جدید + اتمام اعتبار زمانی پلن های داینامیک',
        4 => 'غیرفعال'
    );

    public static $creditTime = array(
        1 => 'نامحدود تا وقتی کاربر حذف واقعی شود',
        2 => '-30 روز به 2 شرط',
        3 => '90 روز از آخرین خرید',
        4 => 'غیرفعال'
    );

    public static $policy = array(
        1 => 'قوانین اعتبار زمانی',
        2 => 'وضعیت پنل همیشه غیرفعال',
        3 => 'LINK',
        4 => 'غیرفعال'
    );

    public static $disableLoginList = array(
        0 => 'دارد',
        1 => 'ندارد'
    );

    public static $ratioPlans = array(
        'ratio_send' => 'ارسال با اپراتور های (فرستنده)',
        'ratio_receive' => 'ارسال به اپراتور های گیرنده',
        'ratio_content' => 'نوع ارسال',
        //'' => 'سایر موارد'
    );
    public $serializedFields = array(
        'pages'=>
            array(
                "pages_1"=>
                    array(
                        'title' => '1 تا 999'
                    ),
                'pages_1000'=>
                    array(
                        'title' => '1,000 تا 9,999'
                    ),
                'pages_10000'=>
                    array(
                        'title' => '10,000 تا 100,000'
                    ),
                'pages_100000'=>
                    array(
                        'title' => 'بیشتر از 100 هزار صفحه'
                    ),
            ),
        'ratio_send'=>
            array(
                "operator_5000"=>
                    array(
                        'title' => 'اپراتور 5000'
                    ),
            ),
        'ratio_receive'=>
            array(
                "irancell"=>
                    array(
                        'title' => 'ایرانسل (*******093)'
                    ),
                "mci"=>
                    array(
                        'title' => 'سن و جنسیت،استان،شهر،کدپستی و دکل BTS'
                    ),
                "other"=>
                    array(
                        'title' => 'ارسال از بانک شماره موبایل های مشاغل و اصناف'
                    ),
            ),
        'ratio_content'=>
            array(
                "webservice"=>
                    array(
                        'title' => 'ارسال با کمک امکانات مخابرات'
                    ),
                "system"=>
                    array(
                        'title' => 'ارسال به دفترچه مخاطبین،بانک شماره موبایل و ...'
                    ),
                "persian"=>
                    array(
                        'title' => 'پیامک فارسی'
                    ),
                "english"=>
                    array(
                        'title' => 'پیامک لاتین'
                    ),
            ),
        'emails'=>
            array(
                "mass_emails"=>
                    array(
                        'title' => 'ضریب مصرف شارژ ایمیل های انبوه'
                    ),
                "alert_emails"=>
                    array(
                        'title' => 'ضریب مصرف شارژ ایمیل های هشدار'
                    ),
                "monthly_free_email"=>
                    array(
                        'title' => 'حداکثر اعتبار سرعت-زمان رایگان گهر میل در ماه'
                    ),
            ),
        'agency'=>
            array(
                "first_level_profit"=>
                    array(
                        'title' => 'دریافت سود از زیر مجموعه سطح 1'
                    ),
                "second_level_profit"=>
                    array(
                        'title' => 'دریافت سود از زیر مجموعه سطح 2'
                    ),
                "third_level_profit"=>
                    array(
                        'title' => 'دریافت سود از زیر مجموعه سطح 3'
                    ),
            ),
        'agency_profit_sections'=>
            array(
                "texts_buy"=>
                    array(
                        'title' => 'خرید شارژ گهر پیامک'
                    ),
                "emails_buy"=>
                    array(
                        'title' => 'خرید شارژ گهر میل'
                    ),
                "numbers_buy"=>
                    array(
                        'title' => 'خرید شماره مجازی خط اختصاصی گهر پیامک'
                    ),
                "plans_buy"=>
                    array(
                        'title' => 'انتخاب پلن'
                    ),
                "credits_buy"=>
                    array(
                        'title' => 'کارمزد افزایش اعتبار نقدی'
                    ),
                "settlement_buy"=>
                    array(
                        'title' => 'کارمزد تسویه حساب'
                    ),

            ),
        'extension_discount_sections'=>
            array(
                "texts_buy"=>
                    array(
                        'title' => 'خرید شارژ گهر پیامک'
                    ),
                "emails_buy"=>
                    array(
                        'title' => 'خرید شارژ گهر میل'
                    ),
                "numbers_buy"=>
                    array(
                        'title' => 'خرید شماره مجازی خط اختصاصی گهر پیامک'
                    ),
                "plans_buy"=>
                    array(
                        'title' => 'انتخاب پلن'
                    ),
                "credits_buy"=>
                    array(
                        'title' => 'کارمزد افزایش اعتبار نقدی'
                    ),
                "settlement_buy"=>
                    array(
                        'title' => 'کارمزد تسویه حساب'
                    ),

            ),
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{plans}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        $maxDiscount = SiteOptions::getOption('plan_max_discount');
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,factor_name, approved_price', 'required'),
			array('expire_time,active,disable_login', 'numerical', 'integerOnly'=>true),
            array('active', 'default', 'value'=>0),
            array('disable_login', 'default', 'value'=>1),
			array('speed_time_discount, extension_discount, max_events_daily, max_general_filters, max_favorite_filters, max_groups_membership, max_groups_admin_membership', 'numerical'),
            array('extension_discount', 'CCompareValidator', 'allowEmpty'=>TRUE, 'compareValue' => $maxDiscount, 'operator'=>'<='),
			array('name', 'length', 'max'=>255),
			array('real_price, approved_price', 'length', 'max'=>10),
			array('pages, ratio, emails, free_special_services, agency, agency_profit_sections,extension_discount_sections, color,role_id,deleted', 'safe'),
            array('pages, ratio, emails, free_special_services, agency, agency_profit_sections,extension_discount_sections,required_fields', 'iWebJsonValidator' , 'type'=>'number'),
            array('pages, ratio, emails, free_special_services, agency, agency_profit_sections,extension_discount_sections,required_fields','filter','filter'=>'json_encode'),
            //array('pages, ratio, emails, free_special_services, agency', 'jsonEncoding'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, real_price, approved_price, pages, ratio, speed_time_discount, emails, free_special_services, agency, extension_discount, expire_time, max_events_daily, max_general_filters, max_favorite_filters, max_groups_membership, max_groups_admin_membership', 'safe', 'on'=>'search'),
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
            'role' => array(self::BELONGS_TO, 'UsersRoles', 'role_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'name' => 'عنوان پلن در مقایسه  و مدیریت پلن',
            'about' => 'درباره محصول',
            'factor_name' => 'عنوان محصول در فاکتور',
			//'real_price' => 'قیمت واقعی پلن های تخفیفی - تومان',
            'real_price' => 'قیمت اصلی (ارزش واقعی)',
            'approved_price' => 'قیمت فروش ( فعلی)',
			//'approved_price' => 'قیمت مصوب پلن های تخفیفی - تومان',
			'pages' => 'قیمت فروش هر صفحه شارژ گهر پیامک (تومان)',

			//ratio
            'ratio' => 'ضریب های مصرفی تجمیعی',
            'ratio_send' => 'عامل فرستنده',
            'ratio_receive' => 'عامل گیرنده',
            'ratio_content' => 'عامل محتوا',

			//'speed_time_discount' => 'درصد تخفیف خرید اعتبار سرعت-زمان گهر میل',
            //'speed_time_discount' => 'درصد تخفیف خرید اعتبار سرعت-زمان',
			//'emails' => 'ایمیل',

			'free_special_services' => 'Free Special Services',
			//'agency' => 'نمایندگی',
            'agency' => 'بازاریابی ، نمایندگی و همکاری در فروش',
            'agency_profit_sections' => 'موارد شامل نمایندگی',

            'extension_discount' => 'درصد تخفیف',
            'extension_discount_sections' => 'موارد شامل تخفیف',

			'expire_time' => 'مدت زمان اعتبار پلن',
            'color' => 'رنگ',
            'active' => 'وضعیت',
            'disable_login' => 'امکان لاگین',

            'required_fields' => 'ملزومات ستاره دار کاربران این پلن',

            'max_events_daily'=>'حداکثر تعداد مراسمات قابل ثبت روزانه هر کاربر',
            'max_general_filters'=>'حداکثر تعداد فیلترهای عمومی رایگان قابل ثبت برای هر کاربر',
            'max_favorite_filters'=>'حداکثر تعداد فیلترهای علاقه مندی رایگان قابل ثبت برای هر کاربر',
            'max_groups_membership'=>'حداکثر تعداد گروهایی که کاربر در آن عضو عادی است',
            'max_groups_admin_membership'=>'حداکثر تعداد گروهایی که کاربر در آن مدیر است',
		);
	}

    public function getCommissionPercent($subsetLevel){
        $agency = json_decode($this->agency,true);
        switch($subsetLevel){
            case 1:
                $level = "first";
            break;
            case 2:
                $level = "second";
            break;
            case 3:
                $level = "third";
            break;

        }
        return $this->commission_percent = intval($agency[$level."_level_profit"]['value']);
    }

    public function getFreePlan(){

        //return 1;
        $freePlan = Plans::model()->find('active = 1 AND approved_price = 0 AND deleted = 0');
        return $freePlan->role_id;
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
	public function search($static = FALSE)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('real_price',$this->real_price,true);
		$criteria->compare('approved_price',$this->approved_price,true);
		$criteria->compare('pages',$this->pages,true);
		$criteria->compare('ratio',$this->ratio,true);
		$criteria->compare('speed_time_discount',$this->speed_time_discount);
		$criteria->compare('emails',$this->emails,true);
		$criteria->compare('free_special_services',$this->free_special_services,true);
		$criteria->compare('agency',$this->agency,true);
		$criteria->compare('extension_discount',$this->extension_discount);
		$criteria->compare('expire_time',$this->expire_time);
        $criteria->addCondition('deleted = 0');
        if($static)
            $criteria->addCondition('id IN('.implode(',',self::$deActivePlans).')');
        else {
            $criteria->addCondition('id NOT IN(' . implode(',', self::$deActivePlans) . ')');
            $criteria->order = 'id DESC';
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function getPagesRangeTitle(){
        $prices = SiteOptions::model()->findByAttributes(array("name" => "sms_prices_range"));
        $prices = json_decode($prices->value,TRUE);

        $pages = array(
            "pages_1"=>
                array(
                    'title' => number_format($prices[0]).' تا '.number_format($prices[1])
                ),
            'pages_1000'=>
                array(
                    'title' => number_format($prices[2]).' تا '.number_format($prices[3])
                ),
            'pages_10000'=>
                array(
                    'title' => number_format($prices[4]).' تا '.number_format($prices[5])
                ),
            'pages_100000'=>
                array(
                    'title' => 'بیش از '.number_format($prices[6])
                ),
        );
        return $pages;
    }

    /*public function jsonValidate($attribute,$params){
        if(!is_array($this->$attribute))
            return;
        if($params['type'] == 'number')
            foreach($this->$attribute as $field)
            {
                if(!is_numeric($field['value']) AND !empty($field['value']))
                {

                    $label = $this->attributeLabels();
                    $label = $label[$attribute];
                    $this->addError($attribute,"تمامی مقادیر \"$label\" باید از نوع عددی باشد.");
                    break;
                }
            }

    }*/

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Plans the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
