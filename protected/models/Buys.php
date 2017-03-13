<?php
Yii::import('application.modules.plans.model.*');
Yii::import('application.modules.messages.model.*');
/**
 * This is the model class for table "{{buys}}".
 *
 * The followings are the available columns in table '{{buys}}':
 * @property string $id
 * @property string $title
 * @property integer $type
 * @property integer $user_id
 * @property integer $date
 * @property string $gateway
 * @property integer $tracking_no
 * @property integer $status
 * @property integer $qty
 * @property string $details
 * @property string $sum_price
 * @property string $current_agents_plan
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Users[] $iwUsers
 * @property PlansBuys $plansBuys
 */
/*$A = $this->sum_price;
$B = SiteOptions::getOption(SiteOptions::B);
$C = SiteOptions::getOption(SiteOptions::C);*/

class Buys extends iWebActiveRecord{
    const STATUS_WAITING = 0;
    const STATUS_DOING = 1;
    const STATUS_DONE = 2;
    const STATUS_FAILED = 3;

    public static $statusList = array(
        0 => 'منتظر رسیدگی',
        1 => 'در حال خرید',
        2 => 'موفقیت آمیز',
        3 => 'ناموفق',
    );

    const TYPE_PLAN = 1;
    const TYPE_NUMBER = 2;
    const TYPE_PAGE = 3;
    const TYPE_CREDIT_CHARGE = 4;
    const TYPE_EMAIL = 5;
    const TYPE_SETTLEMENT = 6;

    public static $typeList = array(
        1 => 'خرید پلن',
        2 => 'خرید خط اختصاصی',
        3 => 'شارژ آنلاین',
        4 => 'شارژ اعتبار نقدی',
        5 => "خرید ایمیل",
        6 => "تسویه حساب",
    );

    public static $typeAssoc = array(
        1 => "plans_buy",
        2 => "numbers_buy",
        3 => "texts_buy",
        4 => "credits_buy",
        5 => "emails_buy",
        6 => "settlement_buy"
    );

    public static $typeInvestment = array(
        1 => "plan_investment",
        2 => "number_investment",
        3 => "sms_investment",
        4 => "credits_investment",
        5 => "emails_investment",
        6 => "settlement_investment"
    );

    public $subset_level,$currentPlan,$days,$success_price,$failed_price,$success_count,$failed_count;
	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return '{{buys}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('title', 'length', 'max'=>512),
            array('user_id','filter','filter'=>'iWebHelper::getUserId','except'=>'user_register,admin_change_plan,admin_charge_credit'),
            //array('current_plan_id','filter','filter'=>array($this,'getCurrentPlan')),
            array('current_agents_plan','filter','filter'=>array($this,'getCurrentAgentsPlan')),
			array('type, user_id', 'required'),
			array('type, user_id, date, tracking_no, status, qty', 'numerical', 'integerOnly'=>true),
			array('gateway', 'length', 'max'=>100),
			array('sum_price', 'length', 'max'=>10),

            array('date', 'filter', 'filter'=>'time'),

			array('details', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, user_id, date, gateway, tracking_no, status, qty, details, sum_price', 'safe', 'on'=>'search'),
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
            'plansBuys' => array(self::HAS_ONE, 'PlansBuys', 'buy_id','condition'=>'type = :type','params'=>array(':type'=>self::TYPE_PLAN)),
            'credit' => array(self::HAS_ONE, 'CreditsTransactions', 'buy_id'),
            //'currentPlan' => array(self::BELONGS_TO, 'Plans','current_plan_id'),
            //'currentPlan' => array(self::BELONGS_TO, 'PlansBuys','current_plan_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'type' => 'نوع',
			'user_id' => 'شناسه کاربر',
			'date' => 'تاریخ تراکنش',
			'gateway' => 'درگاه',
			'tracking_no' => 'شماره پیگیری',
			'status' => 'وضعیت',
			'qty' => 'تعداد',
			'details' => 'جزئیات',
			'sum_price' => 'جمع قیمت',
            'time' => 'ساعت تراکنش',
            'full_name' => 'نام و نام خانوادگی',
            'registerDate' => 'تاریخ ثبت نام',
            'subset_level' => 'سطح زیرمجموعه',
            'currentPlan' => 'نوع پلن من (نماینده)',
            'commission_percent' => 'درصد پورسانت',
            'commission_price' => 'پورسانت (تومان)',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('date',$this->date);
		$criteria->compare('gateway',$this->gateway,true);
		$criteria->compare('tracking_no',$this->tracking_no);
		$criteria->compare('status',$this->status);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('details',$this->details,true);
		$criteria->compare('sum_price',$this->sum_price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function commissions() {
        $criteria = $this->getCommissionCriteria(Yii::app()->user->userID);

        if (isset($_GET['ajax']) AND $_GET['ajax'] == 'buys-grid') {
            if (isset($_GET['effectiveType']) AND intval($_GET['effectiveType']) !== 3) {
                $criteria->together = TRUE;
                $criteria->with = 'credit';
                switch (intval($_GET['effectiveType'])) {
                    case 0:
                        $type = 2;
                        break;
                    case 1:
                        $type = 1;
                        break;
                    case 2:
                        $type = 0;
                        break;
                }
                $criteria->compare('effective', $type);

            }
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getCommissionCriteria($userId){
        $firstSubsets = Users::model()->find(array(
            'select' => 'GROUP_CONCAT(id) AS id',
            'condition' => 'agent_id = :agentId',
            'params' => array(':agentId' => $userId)
        ));
        $firstSubsets = $firstSubsets->id;


        $secondSubsets = Users::model()->find(array(
            'select' => 'GROUP_CONCAT(id) AS id',
            'condition' => 'agent_id IN(:agentId)',
            'params' => array(':agentId'=>$firstSubsets)
        ));
        $secondSubsets = $secondSubsets->id;

        $thirdSubsets = Users::model()->find(array(
            'select' => 'GROUP_CONCAT(id) AS id',
            'condition' => 'agent_id IN(:agentId)',
            'params' => array(':agentId'=>$secondSubsets)
        ));
        $thirdSubsets = $thirdSubsets->id;

        $subsets = array_merge(explode(",",$firstSubsets),explode(",",$secondSubsets),explode(",",$thirdSubsets));

        $criteria=new CDbCriteria;
        $criteria->alias = 'buy';

        if(!is_null($firstSubsets))
            $firstSubsets = "IN($firstSubsets)";
        else
            $firstSubsets = "= 'first'";

        if(!is_null($secondSubsets))
            $secondSubsets = "IN($secondSubsets)";
        else
            $secondSubsets = "= 'fake'";

        if(!is_null($firstSubsets))
            $criteria->select = array($criteria->select,"IF(buy.user_id $firstSubsets,1,IF(buy.user_id $secondSubsets,2,3)) AS subset_level");

        $criteria->compare('id',$this->id,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('date',$this->date);
        $criteria->compare('gateway',$this->gateway,true);
        $criteria->compare('tracking_no',$this->tracking_no);
        $criteria->compare('status',self::STATUS_DONE);
        $criteria->compare('qty',$this->qty);
        $criteria->compare('details',$this->details,true);
        $criteria->compare('sum_price',$this->sum_price,true);
        $criteria->addInCondition('buy.user_id',$subsets);
        $criteria->addCondition('sum_price != 0 AND sum_price IS NOT NULL AND type != 4');

        return $criteria;
    }

    public function getCurrentAgentsPlan(){
        $currentAgentsPlan = array();

        // set investment
        $investment = 0;
        if(intval($this->type) === self::TYPE_CREDIT_CHARGE){
            if($this->status == self::STATUS_DONE) {
                /*$price = intval(str_replace(',', '', $_POST['CreditsTransactions']['price']));
                $wage = floatval($_POST['CreditsTransactions_wage']);*/
                $price = intval($this->credit->price);
                $details = json_decode($this->details,true);
                $wage = floatval($details[4]['value']);

                $investment = ($wage * $price) / 100;
            }
        }
        else {
            $investment = SiteOptions::getOption(Buys::$typeInvestment[$this->type]);

            $details = json_decode($this->details,TRUE);
            $eachProduct = floatval(str_replace(',','',$details[1]['value']));
            $productQty = intval(str_replace(',','',$details[0]['value']));

            if(strpos($investment,'%')){
                $investment = ($eachProduct * floatval($investment)) / 100;
            }

            $investment = $eachProduct - $investment;

            $investment = $investment * $productQty;
        }

        $agent = $this->user;
        for($i = 1;$i<=3;$i++){
            if(isset($agent->agent->id)){
                $agent = $agent->agent;
                $currentAgentsPlan[] = array(
                    'level' => $i,
                    'user_id' => $agent->id,
                    'plan_id' => $agent->activePlan->plansBuys->plan_id,
                    'investment' => $investment
                );
            }
        }
        return json_encode($currentAgentsPlan);
    }

    public function getCurrentPlanAttributes($userId,$attr = NULL){
        $this->currentPlan = new Plans;
        if(!is_null($this->current_agents_plan)){
            $currentAgentsPlan = json_decode($this->current_agents_plan);
            foreach($currentAgentsPlan as $current)
                if($current->user_id == $userId){

                    $this->currentPlan = Plans::model()->findByPk($current->plan_id);
                }
        }
        if(!is_null($attr))
            return $this->currentPlan->$attr;
    }

    public function reportCriteria($hasRelation = TRUE){
        $criteria = new CDbCriteria();
        if($hasRelation)
            $criteria->with = 'buy';
        else
            $criteria->alias = 'buy';

        $criteria->order = 'id DESC';
        $criteria->addCondition('buy.user_id = :user');
        $criteria->params[':user'] = Yii::app()->user->userID;

        if(isset($_GET['tracking_no']) AND $_GET['tracking_no']){
            $criteria->compare('tracking_no',$_GET['tracking_no']);
        }

        if(isset($_GET['status']) AND $_GET['status'] != 4){
            $criteria->compare('status',$_GET['status']);
        }else {
            $criteria->addCondition('status = :successStatus OR status = :failedStatus');
            $criteria->params[':successStatus'] = Buys::STATUS_DONE;
            $criteria->params[':failedStatus'] = Buys::STATUS_FAILED;
        }

        if(isset($_GET['start_time']) AND $_GET['start_time']){
            $startTime = iWebHelper::jalaliToTime($_GET['start_time']);
            $criteria->addCondition('date > :startTime');
            $criteria->params[':startTime'] = $startTime;
        }

        if(isset($_GET['end_time']) AND $_GET['end_time']){
            $startTime = iWebHelper::jalaliToTime($_GET['end_time']);
            $criteria->addCondition('date < :endTime');
            $criteria->params[':endTime'] = $startTime;
        }

        return $criteria;
    }

    public function chartMaker($type){
        error_reporting(0);
        $criteria = $this->reportCriteria(false);

        $criteria = Buys::model()->reportCriteria(FALSE);

        $criteria->compare('type',$type);

        $criteria->addCondition('gateway IS NOT NULL');

        $criteria->select = array(
            'DATE_FORMAT(FROM_UNIXTIME(date),\'%Y/%m/%d\') as days',
            'SUM(IF(status=:successStatus,sum_price,0)) AS success_price',
            'SUM(IF(status=:failedStatus,sum_price,0)) AS failed_price',
            'SUM(IF(status=:successStatus,1,0)) AS success_count',
            'SUM(IF(status=:failedStatus,1,0)) AS failed_count'
            //'SUM(sum_price) AS success_price',
        );
        $criteria->params[':successStatus'] = Buys::STATUS_DONE;
        $criteria->params[':failedStatus'] = Buys::STATUS_FAILED;

        $criteria->addCondition('buy.user_id = :user');
        $criteria->params[':user'] = Yii::app()->user->userID;

        $criteria->order = 'days ASC';
        $criteria->group = "days";
        $criteria->limit = 20;
        $model = Buys::model()->findAll($criteria);

        $successBars = array();
        $failedBars = array();
        $average = array();
        $days = array();
        $successCount = 0;
        $failedCount = 0;


        foreach($model as $record){
            $avg = $record->success_price / $record->success_count;

            $successBars[] = $record->success_price;
            $failedBars[] = $record->failed_price;
            $average[] = $avg;

            $successCount += intval($record->success_count);
            $failedCount += intval($record->failed_count);

            list($year,$month,$day) = explode('/',$record->days);
            $jDate = Yii::app()->jdate->toJalali($year,$month,$day);
            $days[] = implode('/',$jDate);
        }

        echo CJavaScript::jsonEncode(array(
            'successBars' => $successBars,
            'failedBars' => $failedBars,
            'average' => $average,
            'days' => $days,
            'successCount' => $successCount,
            'failedCount' => $failedCount,
        ));
    }

    public function afterSave(){
        if(!in_array($this->scenario,array('user_register','admin_change_plan','admin_charge_credit')) AND $this->status == self::STATUS_DONE){
            if(intval($this->type) !== self::TYPE_PLAN AND intval($this->user->activePlan->plansBuys->plan_id) === 2){
                $buy = new Buys;
                $buy->scenario = 'admin_change_plan';
                $buy->attributes = array(
                    'user_id' => $this->user->id,
                    'status' => Buys::STATUS_DONE,
                    'type' => Buys::TYPE_PLAN,
                );

                if($buy->save()){
                    $selectFreePlan = new PlansBuys;
                    $selectFreePlan->attributes = array(
                        'buy_id' => $buy->id,
                        'plan_id' => 3,
                        'charge_kind' => PlansBuys::KIND_ONLINE,
                        'active' => 1
                    );
                    $selectFreePlan->save();
                }
            }

            $user = $this->user;
            $i = 0;
            while(!is_null($user->agent_id) AND $i <= 2){
                $user = $user->agent;
                $plan = $user->activePlan->plansBuys->plan;
                $agencyProfitSections = json_decode($plan->agency_profit_sections,TRUE);
                if(isset($agencyProfitSections[self::$typeAssoc[$this->type]]) AND $agencyProfitSections[self::$typeAssoc[$this->type]]['value'] == '1'){
                    $agency = array_values(json_decode($plan->agency,TRUE));
                    $agency = $agency[$i];
                    if(isset($agency['checked']) AND $agency['checked'] == '1'){
                        // calculate profit for agent
                        $profit = 0;
                        if(intval($this->type) !== Buys::TYPE_CREDIT_CHARGE) {
                            $extensionDiscountSections = json_decode($plan->extension_discount_sections,TRUE);
                            if(isset($extensionDiscountSections[self::$typeAssoc[$this->type]]) AND $extensionDiscountSections[self::$typeAssoc[$this->type]]['value'] == '1')
                                $profit = $this->getProfit($plan->extension_discount."%");
                        }
                        else {
                            $profit = $this->getProfit();
                        }

                        $profitDiscount = floatval($agency['value']);
                        if(floatval($profit) !== 0)
                            $profit = ($profit * $profitDiscount) / 100;

                        if($user->userInfoStatus() AND !in_array($plan->id,Plans::$deActivePlans)){
                            $effective = 1;
                            $effectiveTitle = 'موثر';
                            $sum = $user->credit_charge + $profit;
                        }
                        else{
                            $effective = 0;
                            $effectiveTitle = 'غیر موثر';
                            $sum = $user->credit_charge;
                        }

                        $credit = new CreditsTransactions;
                        $credit->attributes = array(
                            'buy_id' => $this->id,
                            'user_id' => $user->id,
                            'descriptions' => ((!is_null($this->tracking_no))?'پورسانت '.$effectiveTitle.' نمایندگی '.self::$typeList[$this->type] .'، شماره پیگیری : '.$this->tracking_no:NULL),
                            'price' => $profit,
                            'user_price' => $sum,
                            'effective' => $effective,
                        );
                        if($credit->save()){
                            $user->credit_charge = $sum;
                            $user->scenario = 'changeValue';
                            $user->save();
                        }
                    }
                }
                $i++;
            }

            // sum all buys
            /*$sumBuysModel = SiteOptions::model()->findByAttributes(array('name'=>'buys_sum'));
            $sumBuysModel->value = intval($sumBuysModel->value) + $this->sum_price;
            $sumBuysModel->save();*/
            // sum all buys end
        }
        parent::afterSave();
    }

    public function getProfit($difference = NULL){
        if(!is_null($difference))
            $difference = SiteOptions::getOption(Buys::$typeInvestment[$this->type]);

        if(strpos($difference,'%')){
            $difference = $this->sum_price * floatval($difference) / 100;
        }
        return $this->sum_price - $difference;
    }

    public function getUrl(){
        return 'financial/checkouts/buy/?buyId='.$this->id;
    }

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

    public function getAgentAttr($attr){
        $current = json_decode($this->current_agents_plan);
        $current = $current[0];
        @($current->$attr);
        if(isset($current->$attr))
            return $current->$attr;
        else
            return 0;
    }

    static public function getPrice($date = 'SUBDATE(CURRENT_DATE, 0)',$sign = '=',$func = 'SUM'){
        $model = Buys::model()->find(array(
            'select' => "$func(sum_price) AS sum_price",
            'order' => 'id DESC',
            'condition' => "DATE_FORMAT(FROM_UNIXTIME(date),'%Y-%m-%d') $sign DATE_FORMAT($date,'%Y-%m-%d') AND status = :status",
            'params' => array(
                ':status' => Buys::STATUS_DONE
            )
        ));
        return $model->sum_price;
    }
}
