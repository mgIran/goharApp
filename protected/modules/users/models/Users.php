<?php
Yii::import('application.modules.plans.models.*');
class Users extends iWebActiveRecord
{
    public $repeat_password;
    public $old_password;
    public $passwordSet;
    public $verifyCode;
    public $full_name;
    public $pageSize = NULL,
        $searchCondition = NULL,
        $additionalSelect = NULL,
        $type,
        $sitePolicy,
        $change_plan;


    const STATUS_NOT_VERIFIED = 0;
    const STATUS_VERIFIED = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DISABLE_LOGIN = 3;

    public static $statusList = array(
        0 => 'تائید اعتبار نشده',
        1 => 'فعال',
        2 => 'غیر فعال',
        3 => 'پلن غیرفعال'
    );

    // legal documents
    public $birth_town, $home_town, $work_town, $charge_desc;

    public function tableName()
    {
        return '{{users}}';
    }

    public function rules()
    {
        $required = 'first_name';
        if (($this->scenario == 'changePassword') AND isset($this->activePlan->plansBuys->plan->required_fields)) {
            $requiredFields = CJSON::decode($this->activePlan->plansBuys->plan->required_fields, TRUE);

            $required = array();
            if(is_string($requiredFields))
                $requiredFields=CJSON::decode($requiredFields);
            foreach ($requiredFields as $field => $isRequired) {
                if ($isRequired == '1') {
                    $required[] = $field;
                }
            }
            $required = implode(',', $required);
        }

        return array(
            array('role_id,email', 'required','on'=>'insert,update'),
            array('role_id', 'default' ,'value' => 5,'on'=>'app_insert'),
            array('work_city_id', 'default' ,'value' => 1,'on'=>'app_insert'),
            array('first_name,last_name', 'required','on'=>'register'),
            array($required, 'required', 'on' => 'changePassword'),
            array('user_name, password,repeat_password', 'required', 'on' => 'insert,register'),
            array('password, repeat_password', 'canBeEmpty', 'on' => 'changePassword'),
            array('password, repeat_password', 'canBeEmpty2', 'on' => 'update'),
            array('password, repeat_password', 'required', 'on' => 'recoverPassword'),
            //array('old_password', 'findPasswords', 'on' => 'changePassword'),
            array('email', 'CEmailValidator'),
            array('role_id, mobile', 'numerical', 'integerOnly' => true, 'message' => '{attribute} باید عددی باشد.'),
            array('user_name', 'length', 'min' => 5, 'max' => 254),
            array('first_name,last_name', 'length', 'min' => 3, 'max' => 100),
            array('email', 'length', 'min' => 5, 'max' => 254),
            array('mobile', 'length', 'min' => 11, 'max' => 11),
            array('status', 'default', 'value' => 1, 'except' => 'changeValue,upload'),
            array('agent_id', 'default', 'setOnEmpty' => TRUE, 'value' => NULL, 'except' => 'changeValue,upload'),
            array('agent_id', 'exist', 'className' => 'Users', 'attributeName' => 'id'),
            array('sms_charge, credit_charge', 'safe', 'on' => 'changeValue,upload'),
            //array('password,passwordSet,repeat_password,old_password,avatar,sms_charge,email_charge,credit_charge','safe'),
            array('password,passwordSet,repeat_password,old_password,avatar', 'safe'),
            array('id, role_id, user_name, password, status, first_name, last_name, email, mobile', 'safe', 'on' => 'search'),
            array('repeat_password', 'compare', 'compareAttribute' => 'password', 'message' => "رمز عبور و تکرار رمز عبور یکسان نیستند", 'except' => 'delete,activate,changeValue,upload'),
            //array('repeat_password', 'compare', 'compareAttribute'=>'password', 'message'=>"رمز عبور و تکرار رمز عبور یکسان نیستند" , 'allowEmpty'=>TRUE, 'on'=>'update' ),
            //array('password', 'CRegularExpressionValidator', 'pattern'=>"/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/","message"=>"رمز عبور ایمن نیست"),
            array('user_name', 'unique', 'className' => 'Users', "message" => 'نام کاربری "{value}" قبلا ثبت شده است، لطفا نام کاربری دیگری را وارد نمایید', 'on' => array('insert', 'register')),
            array('email', 'unique', 'className' => 'Users', "message" => 'این پست الکترونیک قبلا ثبت شده است!', 'on' => array('insert', 'register')),
            array('mobile', 'unique', 'className' => 'Users', "message" => 'این شماره قبلا ثبت شده است!', 'on' => array('insert', 'register')),
            array('user_name, email, mobile, first_name, last_name', 'filter', 'filter' => 'trim'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'register'),

            array('sitePolicy', 'CCompareValidator', 'compareValue' => '1', 'message' => 'برای ثبت نام،شما باید قوانین سایت را پذیرفته باشید.', 'on' => 'register'),

            // legal documents
            array('birth_city_id, home_city_id,work_city_id', 'numerical', 'integerOnly' => true),
            array('birth_city_id, home_city_id', 'required', 'on' => 'register'),
            array('personal_image, father_name, national_card_front, national_card_rear, birth_certificate_first,business_license,activity_permission', 'length', 'max' => 100),

            array('national_id', 'length', 'max' => 10),
            array('national_id', 'validateNationalId', 'on' => 'changePassword,changePasswordValidation'),
            array('national_id', 'matchPrefixNumberValidator', 'values' => isset($this->birthCity) ? ((is_null($this->birthCity->national_id_prefix) OR empty($this->birthCity->national_id_prefix))?$this->birthCity->parent->national_id_prefix:$this->birthCity->national_id_prefix) : '', 'message' => "محل تولد و کدملی خود را کنترل کنید", 'on' => 'changePasswordValidation'),
            array('national_id', 'unique'),

            array('home_postal_code, work_postal_code', 'length', 'min' => 10),
            array('home_postal_code', 'matchPrefixNumberValidator', 'values' => isset($this->homeCity) ? ((is_null($this->homeCity->postal_code_prefix) OR empty($this->homeCity->postal_code_prefix))?$this->homeCity->parent->postal_code_prefix:$this->homeCity->postal_code_prefix) : '', 'on' => 'changePasswordValidation'),
            array('work_postal_code', 'matchPrefixNumberValidator', 'values' => isset($this->workCity) ? ((is_null($this->workCity->postal_code_prefix) OR empty($this->workCity->postal_code_prefix))?$this->workCity->parent->postal_code_prefix:$this->workCity->postal_code_prefix) : '', 'on' => 'changePasswordValidation'),
            //array('home_postal_code', 'matchPrefixNumberValidator', 'values' => isset($this->homeCity) ? $this->homeCity->postal_code_prefix : '', 'on' => 'changePassword'),
            //array('work_postal_code', 'matchPrefixNumberValidator', 'values' => isset($this->workCity) ? $this->workCity->postal_code_prefix : '', 'on' => 'changePassword'),
            array('home_phone_prefix, work_phone_prefix', 'length', 'max' => 6),
            array('home_phone_prefix', 'matchPrefixNumberValidator', 'values' => isset($this->homeCity) ? ((is_null($this->homeCity->phone_number_prefix) OR empty($this->homeCity->phone_number_prefix))?$this->homeCity->parent->phone_number_prefix:$this->homeCity->phone_number_prefix) : '', 'on' => 'changePasswordValidation'),
            array('work_phone_prefix', 'matchPrefixNumberValidator', 'values' => isset($this->workCity) ? ((is_null($this->workCity->phone_number_prefix) OR empty($this->workCity->phone_number_prefix))?$this->workCity->parent->phone_number_prefix:$this->workCity->phone_number_prefix) : '', 'on' => 'changePasswordValidation'),
            /*array('home_phone_prefix', 'matchPrefixNumberValidator', 'values' => isset($this->homeCity) ? $this->homeCity->phone_number_prefix : '', 'equal' => TRUE, 'on' => 'changePassword'),
            array('work_phone_prefix', 'matchPrefixNumberValidator', 'values' => isset($this->workCity) ? $this->workCity->phone_number_prefix : '', 'equal' => TRUE, 'on' => 'changePassword'),*/
            array('home_phone_number, work_phone_number', 'length', 'max' => 9),
            array('home_address, work_address,other_legal_documents', 'safe'),

            //bank details
            array('account_number, bank_name', 'length', 'max' => 30),
            array('card_number', 'validateCardBankLength', 'except'=>'upload, changePassword, app_insert, app_update'),
            array('card_number', 'length', 'max'=>20),
            array('iban', 'length', 'is'=>24),
            array('holder_name', 'length', 'max' => 100),
            array('iban', 'unique'),

            //app token
            array('app_token', 'required', 'on'=>'app_update'),
            array('app_token', 'unique', 'on'=>'app_update'),
        );
    }

    public function findPasswords($attribute, $params)
    {
        if ($this->passwordSet === "1" && $this->old_password !== '') {
            $user = Users::model()->findByPk(Yii::app()->user->userID);

            $bcrypt = new Bcrypt();
            if (!$bcrypt->verify(md5(sha1($this->old_password)), $user->password))
                $this->addError($attribute, 'رمز عبور فعلی را با دقت وارد نمائید.');
        }

    }

    public function canBeEmpty($attribute, $params)
    {
        if ($this->passwordSet === "1") {
            switch ($attribute) {
                case 'old_password':
                    if (empty($this->old_password))
                        $this->addError($attribute, "رمز عبور فعلی را وارد کنید.");
                    break;
                case 'password':
                    if (empty($this->password))
                        $this->addError($attribute, "رمز عبور جدید را وارد کنید.");
                    break;
                case 'repeat_password':
                    if (empty($this->repeat_password))
                        $this->addError($attribute, "تکرار رمز عبور جدید را وارد کنید.");
                    break;
            }
        }
    }

    public function canBeEmpty2($attribute, $params)
    {
        if ($this->passwordSet === "1") {
            switch ($attribute) {
                case 'password':
                    if (empty($this->password))
                        $this->addError($attribute, "رمز عبور جدید را وارد کنید.");
                    break;
                case 'repeat_password':
                    if (empty($this->repeat_password))
                        $this->addError($attribute, "تکرار رمز عبور جدید را وارد کنید.");
                    break;
            }
        }
    }

    public function beforeSave()
    {
        if ($this->scenario == 'insert' OR $this->scenario == 'register' OR $this->scenario == 'recoverPassword' OR ($this->scenario == 'changePassword' AND $this->passwordSet == 1)) {
            $bCrypt = new Bcrypt;
            $hash = $bCrypt->hash(md5(sha1($this->password)));
            $this->password = $hash;
        }
        return parent::beforeSave();
    }

    public function relations()
    {
        return array(
            'UsersRoles' => array(self::BELONGS_TO, 'UsersRoles', 'role_id'),
            'UsersOptions' => array(self::HAS_MANY, 'UsersOptions', 'user_id'),
            'activePlan' => array(self::HAS_ONE, 'Buys', 'user_id', 'with' => 'plansBuys', 'condition' => 'active = 1 AND status = :status', 'params' => array(':status' => Buys::STATUS_DONE)),
            'registerDate' => array(self::HAS_ONE, 'Log', 'pk', 'condition' => 'module=\'Users\' AND action = \'register\''),
            'agent' => array(self::BELONGS_TO, 'Users', 'agent_id'),
            'commissions' => array(self::HAS_MANY, 'AgentsCommissions', 'user_id'),
            //legal documents
            'homeCity' => array(self::BELONGS_TO, 'UsersPlaces', 'home_city_id'),
            'birthCity' => array(self::BELONGS_TO, 'UsersPlaces', 'birth_city_id'),
            'workCity' => array(self::BELONGS_TO, 'UsersPlaces', 'work_city_id'),

            'agentStatus' => array(self::HAS_ONE, 'UsersOptions', 'user_id','condition' => "options = 'user_info_status'"),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'شناسه',
            'role_id' => 'نقش',
            'user_name' => 'نام کاربری',
            'password' => 'رمز عبور',
            'repeat_password' => 'تکرار رمز عبور',
            'old_password' => 'رمز عبور فعلی',
            'status' => 'وضعیت',
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'email' => 'پست الکترونیک',
            'mobile' => 'همراه',
            'UsersRoles' => 'نقش',
            'verifyCode' => 'کد امنیتی',
            'full_name' => 'نام و نام خانوادگی',
            'agent_id' => 'شناسه نماینده',
            'activePlan' => 'پلن',
            'registerDate' => 'تاریخ ثبت نام',
            'sumPrice' => 'جمع پورسانت',
            'sitePolicy' => '
            <a style="color:#441e6b;" href="' . Yii::app()->createAbsoluteUrl('pages/view?pageName=site_policy') . '" target="_blank" title="قوانین سایت">
            قوانین سایت
            </a>
              را می پذیرم
            ',
            'change_plan' => 'تغییر پلن',

            //legal documents
            'personal_image' => 'عکس پرسنلی',
            'national_id' => 'کد ملی',
            'birth_town' => 'استان محل تولد',
            'birth_city_id' => 'شهر محل تولد',
            'home_town' => 'استان محل سکونت',
            'home_city_id' => 'شهر محل سکونت',
            'home_postal_code' => 'کدپستی محل سکونت',
            'home_address' => 'آدرس محل سکونت',
            'home_phone_prefix' => 'پیش شماره تلفن محل سکونت',
            'home_phone_number' => 'شماره محل سکونت',
            'father_name' => 'نام پدر',
            'work_town' => 'استان محل کار',
            'work_city_id' => 'شهر محل کار',
            'work_postal_code' => 'کدپستی محل کار',
            'work_address' => 'آدرس محل کار',
            'work_phone_prefix' => 'پیش شماره تلفن محل کار',
            'work_phone_number' => 'شماره تلفن محل کار',
            'national_card_front' => 'تصویر جلوی کارت ملی',
            'national_card_rear' => 'تصویر پشت کارت ملی',
            'birth_certificate_first' => 'صفحه اول شناسنامه',
            'business_license' => 'پروانه کسب',
            'activity_permission' => 'مجوز فعالیت کاری',
            'other_legal_documents' => 'موارد دیگر',

            //bank details
            'account_number' => 'شماره حساب',
            'iban' => 'شماره شبا',
            'card_number' => 'شماره کارت',
            'bank_name' => 'نام بانک',
            'holder_name' => 'نام صاحب حساب',

            // charges
            'charge_desc' => 'علت تراکنش',
            'credit_charge' => 'اعتبار نقدی کاربر',
            'sms_charge' => 'اعتبار گهر پیامک',
            'email_charge' => 'اعتبار گهر میل'
        );
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria;
        $criteria->alias = 'user';

        $criteria->addCondition('app_token IS NOT NULL');

        $criteria->compare('user.id', $this->id);
        $criteria->compare('user.role_id', $this->role_id);
        $criteria->compare('user.agent_id', $this->agent_id);
        //$criteria->compare('UsersRoles',$this->UsersRoles);
        $criteria->compare('user.user_name', $this->user_name, true);
        $criteria->compare('user.password', $this->password, true);
        $criteria->compare('user.status', $this->status, true);
        $criteria->compare('user.first_name', $this->first_name, true);
        $criteria->compare('user.last_name', $this->last_name, true);
        $criteria->compare('user.email', $this->email, true);
        $criteria->compare('user.mobile', $this->mobile, true);
        $criteria->compare('user.type', $this->type, true);
        $criteria->compare('user.account_number', $this->account_number, true);
        $criteria->compare('user.iban', $this->iban, true);
        $criteria->compare('user.national_id', $this->national_id, true);
        if(isset($_GET['effectiveType']) AND intval($_GET['effectiveType']) !== 3){
            $criteria->together = TRUE;
            $criteria->with[] = 'agentStatus';
            switch($_GET['effectiveType']){
                case 0:
                    $type = 'no';
                break;
                case 1:
                    $type = 1;
                break;
                case 2:
                    $type = 0;
                break;
            }
            $criteria->compare('agentStatus.value',$type);
        }

        $criteria->order = "user.id DESC";

        if (!is_null($this->searchCondition)) {
            $criteria->addCondition($this->searchCondition);
        }

        if (!is_null($this->additionalSelect)) {
            $criteria->select .= "," . $this->additionalSelect;
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $this->pageSize,
            ),
        ));
    }

    public function agents()
    {
        $agentsCriteria = new CDbCriteria;

        $agentsCriteria->select = 'GROUP_CONCAT(DISTINCT agent_id) AS id';
        $agentsCriteria->addCondition('agent_id IS NOT NULL AND status = 1 AND deleted = 0');


        $agentsIds = Users::model()->find($agentsCriteria);


        $criteria = new CDbCriteria;

        $criteria->addInCondition('id', explode(",", $agentsIds->id));

        if (isset($_GET['export']) AND $_GET['export'] == 'true') {
            $users = UsersBankDetails::model()->find(array(
                'select' => 'GROUP_CONCAT(user_id) AS user_id',
            ));

            $ids = explode(',', $users->user_id);
            $criteria->addInCondition('id', $ids);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $this->pageSize,
            ),
        ));
    }

    public function getSumPrice($numberFormat = true){
        $buys = Buys::model()->findAll(Buys::model()->getCommissionCriteria($this->id));
        $sum = 0;
        foreach ($buys as $buy) {
            if (is_null($buy->current_agents_plan))
                continue;

            $buy->getCurrentPlanAttributes($this->id);
            $price = intval($buy->sum_price);
            $level = json_decode($buy->current_agents_plan);
            $level = $level[0]->level;
            $discount = intval($buy->currentPlan->getCommissionPercent($level));

            if ($price AND $discount)
                $sum += ($price * $discount / 100);

        }

        foreach ($this->commissions as $commission) {
            $sum -= intval($commission->price);
        }
        if ($sum >= 50000) {
            if ($numberFormat)
                return number_format($sum);
            else
                return $sum;
        }

    }


    public function getDefaultRoleId()
    {
        //if(!@class_exists('Plans'))
        //Yii::import('application.modules.plans.models.*');
        //$freePlan = Plans::model()->find('active = 1 AND approved_price = 0 AND deleted = 0');
        return 5;
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function listForDropDown()
    {
        $models = Users::model()->findAll(array('condition' => 'deleted = 0 AND status = 1'));
        $temp = array();
        foreach ($models as $model) {
            $temp[$model->id] = $model->id . " - " . $model->first_name . " " . $model->last_name;
        }
        return $temp;

    }

    public function validateCardBankLength($attribute)
    {
        if(strlen($this->$attribute) < 16)
            $this->addError($attribute, "شماره کارت صحیح نمی باشد");
        elseif(strlen($this->$attribute) > 16 && strlen($this->$attribute) < 20)
            $this->addError($attribute, "شماره کارت صحیح نمی باشد");
        elseif(strlen($this->$attribute) > 20)
            $this->addError($attribute, "شماره کارت صحیح نمی باشد");
    }

    public function validateNationalId($attribute)
    {
        while (strlen($this->$attribute) < 10 AND strlen($this->$attribute) > 8) {
            $this->$attribute = "0" . $this->$attribute;
        }
        if (strlen($this->$attribute) < 10) {
            $this->addError($attribute, "کد ملی باید ده رقمی باشد.");
            return false;
        }

        $leftNum = substr($this->$attribute, 0, 9);
        $controlNum = substr($this->$attribute, 9);

        $leftNum = str_split($leftNum);
        $i = 10;
        $sum = 0;
        foreach ($leftNum as $value) {
            $sum += ($i * $value);
            $i--;
        }
        $r = $sum % 11;

        if ($r >= 2)
            $r -= 11;
        if ($r < 0)
            $r *= -1;
        if ($r != $controlNum)
            $this->addError($attribute, "کد ملی صحیح نمی باشد");
    }

    public function planStatus(){
        if(
            // user charge is lower than 1000
            $this->credit_charge < -1000 OR
            // user in deActive plan - 30
            intval($this->activePlan->plansBuys->plan_id) === 2 OR
            // user in deActive plan -90
            intval($this->activePlan->plansBuys->plan_id) === 1 OR
            // user info is not complete
            !$this->userInfoStatus()
        )
            return false;

        return true;
    }

    public function userInfoStatus($validateOld = TRUE, $clear = FALSE){
        if ($clear)
            $this->userInfoClear();

        if ($validateOld) {
            $values = array();
            $this->scenario = 'changePassword';
            $errors = CActiveForm::validate($this);
            $errors = json_decode($errors, TRUE);
            foreach ($errors as $name => $value) {
                if (in_array($name, $errors)) {
                    $values[$name] = '';
                }
            }
            if (!empty($values))
                $this->save(FALSE, $values);
        }

        $status = TRUE;
        $options = array();
        if (isset($this->UsersOptions))
            $options = $this->UsersOptions;

        $time = strtotime('+7 days');

        $timeFlag = FALSE;
        $statusFlag = FALSE;
        /*foreach ($options as $option) {
            if ($option->options == 'user_info_status_time' AND intval($option->value) < $time)
                $timeFlag = TRUE;

            if ($option->options == 'user_info_status' AND $option->value == '1')
                $statusFlag = TRUE;
        }*/

        if ($timeFlag AND $statusFlag) {
            Yii::app()->user->setState('info_status', TRUE);
            return TRUE;
        } elseif ($timeFlag AND !$statusFlag) {
            Yii::app()->user->setState('info_status', FALSE);
            RETURN FALSE;
        }

        $requiredFields = CJSON::decode($this->activePlan->plansBuys->plan->required_fields, TRUE);
        if(is_string($requiredFields))
            $requiredFields=CJSON::decode($requiredFields);

        foreach ($requiredFields as $field => $isRequired) {
            if ($isRequired == '1' AND (is_null($this->$field) OR empty($this->$field))) {
                $status = FALSE;
                break;
            }
        }
        $this->userInfoClear();

        /*$model = new UsersOptions;
        $model->user_id = $this->id;
        $model->options = 'user_info_status';
        $model->value = ($status) ? '1' : '0';
        $model->save()*/;

        /*$model = new UsersOptions;
        $model->user_id = $this->id;
        $model->options = 'user_info_status_time';
        $model->value = time();
        $model->save();*/

        Yii::app()->user->setState('info_status', $status);
        return $status;
    }

    public function userInfoClear()
    {
        return false;
        foreach ($this->UsersOptions as $option) {
            if ($option->options == 'user_info_status_time' OR $option->options == 'user_info_status')
                $option->delete();
        }
    }

    public function createAppToken(){
        $tokenize = new bCrypt();
        $this->app_token = $tokenize->hash($this->mobile);
        while($this->findByAttributes(array('app_token'=>$this->app_token)))
            $this->app_token = $tokenize->hash($this->mobile);
        return $this;
    }
}
