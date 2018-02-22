<?php

/**
 * This is the model class for table "{{events}}".
 *
 * The followings are the available columns in table '{{events}}':
 * @property string $id
 * @property string $creator_type
 * @property integer $creator_id
 * @property string $type1
 * @property string $type2
 * @property string $subject1
 * @property string $subject2
 * @property string $conductor1
 * @property string $conductor2
 * @property string $sexed_guest
 * @property integer $min_age_guests
 * @property integer $max_age_guests
 * @property string $start_date_run
 * @property string $long_days_run
 * @property string $start_time_run
 * @property string $end_time_run
 * @property string $more_days
 * @property string $country_id
 * @property string $state_id
 * @property string $city_id
 * @property string $town
 * @property string $main_street
 * @property string $by_street
 * @property string $boulevard
 * @property string $afew_ways
 * @property string $squary
 * @property string $bridge
 * @property string $quarter
 * @property string $area_code
 * @property string $postal_code
 * @property string $complete_address
 * @property string $complete_details
 * @property string $reception
 * @property string $invitees
 * @property string $ceremony_poster
 * @property string $state
 * @property string $city
 * @property string $selectedCategories
 * @property integer $status
 * @property string $ceremony_public
 * @property string $default_show_price
 * @property string $more_than_default_show_price
 * @property string $plan_off
 * @property string $tax
 * @property string $showStartTime
 * @property string $showEndTime
 * @property string $create_date
 * @property integer $deleted
 * @property string $confirm_date
 * @property string $show_start_time
 * @property string $show_end_time
 * @property string $user_mobile
 * @property string $recepiant_contact
 * @property integer $edit
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class Events extends iWebActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_USER_PAID = 2;

    public $scenarioError;
    public $selectedCategories;
    public $state;
    public $city;
    public $creator_mobile;
    public $inviteesLabels = array(
        'executer' => 'مجری',
        'reader' => 'قاری',
        'poet' => 'شاعر',
        'speaker' => 'سخنران',
        'maddah' => 'مداح',
        'singer' => 'خواننده',
        'team' => 'تیم/گروه',
        'other' => 'سایر',
    );
    public $statusLabels = array(
        '0' => 'در انتظار تایید',
        '1' => 'تایید شده'
    );
    public $sexLabels = array(
        'male' => 'آقایان',
        'female' => 'بانوان',
        'both' => 'هر دو'
    );

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{events}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ceremony_public, main_street, creator_type, creator_id, type1, subject1, sexed_guest, min_age_guests, max_age_guests, start_date_run, long_days_run, start_time_run, end_time_run, country_id, state_id, city_id, complete_address, conductor1', 'required'),
            array('deleted, user_mobile', 'numerical', 'integerOnly' => true),
            array('subject1, subject2, conductor1, conductor2, reception, ceremony_poster', 'length', 'max' => 256),
            array('status', 'default', 'value' => self::STATUS_PENDING),
            array('type1, type2', 'length', 'max' => 255),
            array('sexed_guest', 'length', 'max' => 6),
            array('status, ceremony_public, deleted', 'length', 'max' => 1),
            array('min_age_guests, max_age_guests, long_days_run, more_days, area_code', 'length', 'max' => 2),
            array('start_date_run, start_time_run, end_time_run, create_date, confirm_date, show_start_time, show_end_time', 'length', 'max' => 20),
            array('country_id, state_id, city_id, postal_code, default_show_price, more_than_default_show_price, plan_off, tax', 'length', 'max' => 10),
            array('country_id, state_id, city_id', 'checkPlaces'),
            array('creator_type', 'length', 'max' => 50),
            array('creator_id', 'length', 'max' => 11),
            array('user_mobile', 'length', 'is' => 11),
            array('create_date', 'default', 'value' => time(), 'on' => 'insert'),
            array('town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter', 'length', 'max' => 25),
            array('state, city, complete_details, invitees', 'safe'),
            array('selectedCategories', 'safe'),
            array('scenarioError', 'checkSubmitEvents'),
            array('more_days', 'checkMoreDays'),
            array('long_days_run', 'checkLongDays'),
            array('end_time_run', 'checkEndTime', 'distance' => 15),
            array('creator_id', 'checkPlanCountEventsDaily'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('creator_mobile, ceremony_public, creator_type, creator_id, type1, type2, state, city, subject1, subject2, conductor1, conductor2, sexed_guest, min_age_guests, max_age_guests, start_date_run, long_days_run, start_time_run, end_time_run, more_days, country_id, state_id, city_id, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, complete_address, complete_details, reception, invitees, ceremony_poster, status, default_show_price, more_than_default_show_price, plan_off, tax, deleted', 'safe', 'on' => 'search'),

            array('ceremony_public', 'checkCeremonyPublic'),

            // Increase edit_number on update
            array('edit', 'increase', 'on' => 'update'),
            array('edit', 'numerical'),
        );
    }

    public function checkCeremonyPublic($attribute)
    {
        if ($this->$attribute != 0 and $this->$attribute != 1)
            $this->addError($attribute, 'مقدار فیلد ' . $this->getAttributeLabel($attribute) . ' غیر قابل قبول است. مقادیر صحیح 0 و 1 می باشد.');
    }

    public function increase($attribute, $params)
    {
        if($this->$attribute < 99)
            $this->$attribute += 1;
        else
            $this->$attribute = 1;
    }

    /**
     * @param int $planOff
     * @return array
     */
    public function calculatePrice($planOff = 0)
    {
        $a = $this->long_days_run;
        Yii::app()->getModule('setting');
        $defaultShowTimes = CJSON::decode(SiteOptions::getOption('show_event_message'));
        $b = 0;
        foreach ($defaultShowTimes as $item)
            if ($a >= $item[0] and $a <= $item[1])
                $b = $item[2];
        $b = $b / 24;
        // Reducing time lost from default show time
        $showTime = strtotime(date("Y/m/d", $this->start_date_run) . " 00:00 - " . $b . "days");
        $diff = (time() - $showTime < 0) ? 0 : time() - $showTime;
        $diff = floor($diff / (60 * 60 * 24));
        $constStartShowTime = strtotime(date('Y/m/d', $showTime) . ' ' . date('H:i', $this->start_time_run));
        $b = ($b - $diff < 0) ? 0 : $b - $diff;
        $c = (float)$a + $b;
        $defaultShowPrice = CJSON::decode(SiteOptions::getOption('show_event'));
        $d = 0;
        foreach ($defaultShowPrice as $item)
            if ($c >= $item[0] and $c <= $item[1])
                $d = $item[2];
        $showEventMoreThanDefaultPrice = (int)SiteOptions::getOption('show_event_more_than_default_price');
        // Reducing time lost from more_days
        $showTime = strtotime(date("Y/m/d", $showTime) . " 00:00 - " . $this->more_days . "days");
        $diff = (time() - $showTime < 0) ? 0 : time() - $showTime;
        $diff = floor($diff / (60 * 60 * 24));
        $moreDays = ($this->more_days - $diff < 0) ? 0 : $this->more_days - $diff;
        $e = $showEventMoreThanDefaultPrice * $moreDays;
        $eventTaxEnabled = SiteOptions::getOption('event_tax_enabled');
        $tax = 0;
        if ($eventTaxEnabled == 1)
            $tax = (float)SiteOptions::getOption('tax');
        $f = (($d + $e) * (100 - $planOff) / 100) + (($tax / 100) * (($d + $e) * (100 - $planOff) / 100));
        $returns = array(
            'defaultPrice' => $d,
            'showStartDefault' => $constStartShowTime,
            'moreDaysPrice' => $showEventMoreThanDefaultPrice,
            'showMoreThanDefaultPrice' => $e,
            'eventPrice' => $e + $d,
            'planOff' => $planOff,
            'planOffPrice' => (($e + $d) * $planOff) / 100,
            'eventPriceWithOff' => ($e + $d) - ((($e + $d) * $planOff) / 100),
            'tax' => SiteOptions::getOption('tax'),
            'thisEventTax' => $tax,
            'taxPrice' => ((($e + $d) - ((($e + $d) * $planOff) / 100)) * $tax) / 100,
            'price' => $f
        );
        return $returns;
    }

    public function checkPlaces($attribute, $params = null, $isManual = false)
    {
        Yii::app()->getModule('users');

        $place = UsersPlaces::model()->findByPk($this->{$attribute});

        if ($place === NULL) {
            if($isManual)
                return false;
            $this->addError($attribute, 'استان یا شهرستان موردنظر وجود ندارد.');
        }
        return true;
    }

    public function checkPlanCountEventsDaily($attribute, $params)
    {
        if ($this->creator_type && $this->creator_type == 'user') {
            $user = Users::model()->findByPk($this->creator_id);
            if ($user->activePlan->plansBuys->plan) {
                $max = $user->activePlan->plansBuys->plan->max_events_daily;
                if ($max) {
                    $criteria = new CDbCriteria();
                    $criteria->compare('creator_type', $this->creator_type);
                    $criteria->compare('creator_id', $this->creator_id);
                    $criteria->compare('status', self::STATUS_ACCEPTED);
                    $criteria->addCondition('create_date > ' . (time() - 15 * 60 * 60));
                    $eventsCount = Events::model()->count($criteria);
                    if ($eventsCount > $max)
                        $this->addError($attribute, "حداکثر تعداد مراسمی که پلن شما میتواند در هر روز ثبت (ارسال) کند، {$max} روز است.");
                }
            }
        }
    }

    public function checkSubmitEvents($attribute = null, $params = null)
    {
        Yii::app()->getModule('setting');
        $submitGeneralEvents = SiteOptions::getOption('submit_general_events');

        if ($submitGeneralEvents == 0) {
            if (is_null($attribute))
                return false;
            $this->addError($attribute, 'در حال حاضر امکان ثبت مراسم وجود ندارد.');
        }
        return true;
    }

    public function checkEndTime($attribute = null, $params = null)
    {
        $lastDay = (float)$this->start_date_run + (float)($this->long_days_run * (3600 * 24));
        $lastDateTime = strtotime(date('Y/m/d', $lastDay) . date(' H:i', $this->end_time_run));

        if (strtotime(date('Y/m/d', $this->start_date_run) . ' ' . date('H:i', $this->start_time_run)) < time()) {
            if (is_null($attribute))
                return false;
            $this->addError($attribute, 'تاریخ و زمان انتخاب شده صحیح نمی باشد.');
        }

        if ($lastDateTime < time()) {
            if (is_null($attribute))
                return false;
            $this->addError($attribute, 'تاریخ و زمان انتخاب شده صحیح نمی باشد.');
        } elseif ($lastDateTime < (time() + ($params['distance'] * 60))) {
            if (is_null($attribute))
                return false;
            $this->addError($attribute, 'تاریخ و زمان آخرین جلسه از مراسم باید حداقل ' . $params['distance'] . ' دقیقه بعد باشد.');
        }

        if (($this->end_time_run - $this->start_time_run) < (15 * 60)) {
            if (is_null($attribute))
                return false;
            $this->addError($attribute, 'ساعت پایان مراسم باید حداقل ' . $params['distance'] . ' دقیقه بعد از ساعت شروع مراسم باشد.');
        }
        return true;
    }

    public function checkMoreDays($attribute = null, $params = null)
    {
        Yii::app()->getModule('setting');
        $showEventMoreThanDefault = SiteOptions::getOption('show_event_more_than_default');

        $a = $this->long_days_run;
        $defaultShowTimes = CJSON::decode(SiteOptions::getOption('show_event_message'));
        $b = 0;
        foreach ($defaultShowTimes as $item)
            if ($a >= $item[0] and $a <= $item[1])
                $b = $item[2];
        $b = $b / 24;
        $showTime = strtotime(date("Y/m/d", $this->start_date_run) . " 00:00 - " . $b . "days");
        // Reducing time lost from more_days
        $showTime = strtotime(date("Y/m/d", $showTime) . " 00:00 - " . $this->more_days . "days");
        $diff = (time() - $showTime < 0) ? 0 : time() - $showTime;
        $diff = floor($diff / (60 * 60 * 24));
        $moreDays = ($this->more_days - $diff < 0) ? 0 : $this->more_days - $diff;

        $moreDaysForCheck = $showEventMoreThanDefault;
        if($moreDays < $moreDaysForCheck)
            $moreDaysForCheck = $moreDays;

        if ($this->more_days > $moreDaysForCheck and $this->status == self::STATUS_PENDING) {
            if (is_null($attribute))
                return false;
            $this->addError('more_days', $this->getAttributeLabel('more_days') . ' نمی تواند بیشتر از ' . $moreDaysForCheck . ' باشد.');
        }
        return true;
    }

    public function checkLongDays($attribute = null, $params = null)
    {
        Yii::app()->getModule('setting');
        $eventMaxLongDays = SiteOptions::getOption('event_max_long_days');

        if ($this->long_days_run < 1) {
            if (is_null($attribute))
                return false;
            $this->addError('long_days_run', $this->getAttributeLabel('long_days_run') . ' نمی تواند کمتر از 1 باشد.');
        } elseif ($this->long_days_run > $eventMaxLongDays and $this->status == self::STATUS_PENDING) {
            if (is_null($attribute))
                return false;
            $this->addError('long_days_run', $this->getAttributeLabel('long_days_run') . ' نمی تواند بیشتر از ' . $eventMaxLongDays . ' باشد.');
        }
        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'creator_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'شناسه',
            'creator_type' => 'نوع ایجاد کننده',
            'creator_id' => 'شناسه ایجاد کننده',
            'type1' => 'نوع مراسم',
            'type2' => 'نوع مراسم',
            'subject1' => 'موضوع',
            'subject2' => 'موضوع',
            'conductor1' => 'میزبان',
            'conductor2' => 'میزبان',
            'sexed_guest' => 'جنسیت',
            'min_age_guests' => 'حداقل سن میهمان',
            'max_age_guests' => 'حداکثر سن میهمان',
            'start_date_run' => 'تاریخ شروع',
            'long_days_run' => 'مدت مراسم',
            'start_time_run' => 'ساعت شروع',
            'end_time_run' => 'ساعت پایان',
            'more_days' => 'تعداد روزهای اضافه تر از پیشفرض',
            'country_id' => 'کشور',
            'state_id' => 'استان',
            'state' => 'استان',
            'city_id' => 'شهرستان',
            'city' => 'شهرستان',
            'town' => 'شهرک/ده/روستا',
            'main_street' => 'خیابان اصلی',
            'by_street' => 'خیابان فرعی',
            'boulevard' => 'بلوار',
            'afew_ways' => 'سه یا چهارراه',
            'squary' => 'میدان',
            'bridge' => 'پل/زیر گذر',
            'quarter' => 'محله',
            'area_code' => 'منطقه شهرداری',
            'postal_code' => 'کد پستی',
            'complete_address' => 'آدرس',
            'complete_details' => 'توضیحات تکمیلی',
            'reception' => 'پذیرایی',
            'invitees' => 'مدعوین',
            'ceremony_poster' => 'پوستر مراسم',
            'ceremony_public' => 'مراسم عمومی است',
            'selectedCategories' => 'نوع مراسم',
            'status' => 'وضعیت',
            'creator_mobile' => 'شماره موبایل ثبت کننده',
            'default_show_price' => 'هزینه نمایش پیشفرض',
            'more_than_default_show_price' => 'هزینه نمایش بیشتر از پیشفرض',
            'plan_off' => 'تخفیف پلنی',
            'tax' => 'مالیات ثبت مراسم',
            'showStartTime' => 'شروع نمایش',
            'showEndTime' => 'پایان نمایش',
            'show_start_time' => 'شروع نمایش',
            'show_end_time' => 'پایان نمایش',
            'create_date' => 'تاریخ ثبت',
            'confirm_date' => 'تاریخ تایید یا واریز وجه',
            'paymentStatus' => 'وضعیت پرداخت',
            'bankName' => 'بانک عامل',
            'bankRefID' => 'کد رهگیری بانک',
            'user_mobile' => 'شماره موبایل ثبت کننده',
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
     * @param $condition string the additional condition
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($condition = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('creator_type', $this->creator_type, true);
        $criteria->compare('creator_id', $this->creator_id, true);
        $criteria->compare('type1', $this->type1, true);
        $criteria->compare('type2', $this->type2, true);
        $criteria->compare('sexed_guest', $this->sexed_guest, true);
        $criteria->compare('min_age_guests', $this->min_age_guests, true);
        $criteria->compare('max_age_guests', $this->max_age_guests, true);
        $criteria->compare('start_date_run', $this->start_date_run, true);
        $criteria->compare('long_days_run', $this->long_days_run, true);
        $criteria->compare('start_time_run', $this->start_time_run, true);
        $criteria->compare('end_time_run', $this->end_time_run, true);
        $criteria->compare('more_days', $this->more_days, true);
        $criteria->compare('city_id', $this->city_id, true);
        $criteria->compare('town', $this->town, true);
        $criteria->compare('main_street', $this->main_street, true);
        $criteria->compare('by_street', $this->by_street, true);
        $criteria->compare('boulevard', $this->boulevard, true);
        $criteria->compare('afew_ways', $this->afew_ways, true);
        $criteria->compare('squary', $this->squary, true);
        $criteria->compare('bridge', $this->bridge, true);
        $criteria->compare('quarter', $this->quarter, true);
        $criteria->compare('area_code', $this->area_code, true);
        $criteria->compare('postal_code', $this->postal_code, true);
        $criteria->compare('complete_address', $this->complete_address, true);
        $criteria->compare('complete_details', $this->complete_details, true);
        $criteria->compare('reception', $this->reception, true);
        $criteria->compare('invitees', $this->invitees, true);
        $criteria->compare('ceremony_poster', $this->ceremony_poster, true);
        $criteria->compare('user_mobile', $this->user_mobile, true);

        $criteria->addCondition('deleted = 0');

        if (!empty($_GET['Events']['subject1'])) {
            $criteria->addCondition("subject1 LIKE :subject OR subject2 LIKE :subject");
            $criteria->params[':subject'] = '%' . $this->subject1 . '%';
        }

        if (!empty($_GET['Events']['conductor1'])) {
            $criteria->addCondition("conductor1 LIKE :conductor OR conductor2 LIKE :conductor");
            $criteria->params[':conductor'] = '%' . $this->conductor1 . '%';
        }

        if (!empty($_GET['Events']['state_id']))
            $criteria->compare('state_id', $this->state_id);

        if (!empty($_GET['Events']['country_id']))
            $criteria->compare('country_id', $this->country_id);

        if (!empty($_GET['Events']['creator_mobile'])) {
            if ($_GET['Events']['creator_mobile'] == 'مدیر')
                $criteria->addCondition('user_mobile = "" OR user_mobile IS NULL');
            else {
                $criteria->addCondition("creator_id IN (SELECT id FROM iw_users WHERE mobile LIKE :mobile)");
                $criteria->compare('user_mobile', $this->creator_mobile, true, 'OR');
                $criteria->params[':mobile'] = '%' . $this->creator_mobile . '%';
            }
        }

        if (!is_null($condition))
            $criteria->addCondition($condition);

        $criteria->order = 'id DESC';

//        var_dump($criteria);exit;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Events the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function implodeInvitees($glue = ' - ')
    {
        $invitees = CJSON::decode($this->invitees);
        $translated = array();
        foreach ($invitees as $key => $value)
            $translated[$this->inviteesLabels[$key]] = implode(', ', $value);

        $string = '';
        foreach ($translated as $key => $value)
            $string .= $key . ': ' . $value . $glue;

        return $string;
    }

    public function getShowStartTime()
    {
        $a = $this->long_days_run;
        Yii::app()->getModule('setting');
        $defaultShowTimes = CJSON::decode(SiteOptions::getOption('show_event_message'));
        $b = 0;
        foreach ($defaultShowTimes as $item)
            if ($a >= $item[0] and $a <= $item[1])
                $b = $item[2];
        $b = $b / 24;
        $b = (float)$b + (float)$this->more_days;
        $showTime = strtotime(date("Y/m/d", $this->start_date_run) . " " . date("H:i", $this->start_time_run));
        return $showTime - ($b * 24 * 60 * 60);
    }

    public function getShowEndTime()
    {
        Yii::app()->getModule('setting');
        $startTime = strtotime(date("Y/m/d", $this->start_date_run) . " " . date("H:i", $this->start_time_run));
        return (float)$startTime + (float)(($this->long_days_run - 1) * 24 * 60 * 60);
    }

    /**
     * unset invalid attributes
     * @param $attributes
     */
    public function unsetInvalidAttributes(&$attributes)
    {
        $invalidChangeAttributes = array(
            'id',
            'creator_type',
            'creator_id',
        );
        foreach ($attributes as $key => $item)
            if (in_array($key, $invalidChangeAttributes))
                unset($attributes[$key]);
    }

    /**
     * Delete Event Poster
     *
     * @param $currentPoster
     * @return bool
     */
    public function deletePoster($currentPoster)
    {
        $path = Yii::getPathOfAlias('webroot') . self::$path;
        if ($currentPoster && file_exists($path . $currentPoster))
            return @unlink($path . $currentPoster);
        return true;
    }

    public function getPrice()
    {
        $eventSubmitPrice = (float)$this->default_show_price + (float)$this->more_than_default_show_price;
        $eventPriceWithOff = $eventSubmitPrice - (float)($this->plan_off * $eventSubmitPrice / 100);
        return (float)$eventPriceWithOff + (float)($this->tax * $eventPriceWithOff / 100);
    }

    public static function getConfirmedEvents($userID)
    {
        return self::model()->findAll('status = :status AND creator_id = :userID', [':status' => self::STATUS_ACCEPTED, ':userID' => $userID]);
    }
}