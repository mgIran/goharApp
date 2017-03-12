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
 * @property string $min_age_guests
 * @property string $max_age_guests
 * @property string $start_date_run
 * @property string $long_days_run
 * @property string $start_time_run
 * @property string $end_time_run
 * @property string $more_days
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
 * @property integer $activator_area_code
 * @property integer $activator_postal_code
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
 *
 */
class Events extends CActiveRecord
{
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
            array('main_street, creator_type, creator_id, type1, subject1, sexed_guest, min_age_guests, max_age_guests, start_date_run, long_days_run, start_time_run, end_time_run, state_id, city_id, complete_address', 'required'),
            array('activator_area_code, activator_postal_code', 'numerical', 'integerOnly' => true),
            array('subject1, subject2, conductor1, conductor2, reception, ceremony_poster', 'length', 'max' => 256),
            array('type1, type2,', 'length', 'max' => 255),
            array('sexed_guest', 'length', 'max' => 6),
            array('status', 'length', 'max' => 1),
            array('min_age_guests, max_age_guests, long_days_run, more_days, area_code', 'length', 'max' => 2),
            array('start_date_run, start_time_run, end_time_run', 'length', 'max' => 20),
            array('state_id, city_id, postal_code, default_show_price, more_than_default_show_price, plan_off, tax', 'length', 'max' => 10),
            array('creator_type', 'length', 'max' => 50),
            array('creator_id', 'length', 'max' => 11),
            array('ceremony_public', 'length', 'max' => 1),
            array('town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter', 'length', 'max' => 25),
            array('state, city, complete_details, invitees', 'safe'),
            array('selectedCategories', 'safe'),
            array('scenarioError', 'checkSubmitEvents'),
            array('more_days', 'checkMoreDays'),
            array('long_days_run', 'checkLongDays'),
            array('end_time_run', 'checkEndTime', 'distance' => 15),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('creator_mobile, ceremony_public, creator_type, creator_id, type1, type2, state, city, subject1, subject2, conductor1, conductor2, sexed_guest, min_age_guests, max_age_guests, start_date_run, long_days_run, start_time_run, end_time_run, more_days, state_id, city_id, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, complete_address, complete_details, reception, invitees, activator_area_code, activator_postal_code, ceremony_poster, status, default_show_price, more_than_default_show_price, plan_off, tax', 'safe', 'on' => 'search'),
        );
    }

    public function calculatePrice($planOff = 0)
    {
        $a = $this->long_days_run;
        Yii::app()->getModule('setting');
        $defaultShowTimes = CJSON::decode(SiteOptions::model()->getOption('show_event_message'));
        $b = 0;
        foreach ($defaultShowTimes as $item)
            if ($a >= $item[0] and $a <= $item[1])
                $b = $item[2];
        $b = $b / 24;
        // Reducing time lost from default show time
        $showTime = strtotime(date("Y/m/d", $this->start_date_run) . " 00:00 - " . $b . "days");
        $diff = (time() - $showTime < 0) ? 0 : time() - $showTime;
        $diff = floor($diff / (60 * 60 * 24));
        $b = ($b - $diff < 0) ? 0 : $b - $diff;
        $c = (float)$a + $b;
        $defaultShowPrice = CJSON::decode(SiteOptions::model()->getOption('show_event'));
        $d = 0;
        foreach ($defaultShowPrice as $item)
            if ($c >= $item[0] and $c <= $item[1])
                $d = $item[2];
        $showEventMoreThanDefaultPrice = (int)SiteOptions::model()->getOption('show_event_more_than_default_price');
        // Reducing time lost from more_days
        $showTime = strtotime(date("Y/m/d", $this->start_date_run) . " 00:00 - " . $c . "days");
        $diff = (time() - $showTime < 0) ? 0 : time() - $showTime;
        $diff = floor($diff / (60 * 60 * 24));
        $moreDays = ($this->more_days - $diff < 0) ? 0 : $this->more_days - $diff;
        $e = $showEventMoreThanDefaultPrice * $moreDays;
        $eventTaxEnabled = SiteOptions::model()->getOption('event_tax_enabled');
        $tax = 0;
        if ($eventTaxEnabled == 1)
            $tax = (float)SiteOptions::model()->getOption('tax');
        $f = (($d + $e) * (100 - $planOff) / 100) + (($tax / 100) * (($d + $e) * (100 - $planOff) / 100));
        $returns = array(
            'defaultPrice' => $d,
            'showMoreThanDefaultPrice' => $e,
            'eventPrice' => $e + $d,
            'planOff' => $planOff,
            'planOffPrice' => (($e + $d) * $planOff) / 100,
            'eventPriceWithOff' => ($e + $d) - ((($e + $d) * $planOff) / 100),
            'tax' => SiteOptions::model()->getOption('tax'),
            'thisEventTax' => $tax,
            'taxPrice' => ((($e + $d) - ((($e + $d) * $planOff) / 100)) * $tax) / 100,
            'price' => $f
        );
        return $returns;
    }

    public function checkSubmitEvents($attribute, $params)
    {
        Yii::app()->getModule('setting');
        $submitGeneralEvents = SiteOptions::model()->getOption('submit_general_events');

        if ($submitGeneralEvents == 0)
            $this->addError($attribute, 'در حال حاضر امکان ثبت مراسم وجود ندارد.');
    }

    public function checkEndTime($attribute, $params)
    {
        $lastDay = $this->start_date_run + ($this->long_days_run * (3600 * 24));
        $lastDateTime = strtotime(date('Y/m/d', $lastDay) . date(' H:i', $this->end_time_run));
        if ($lastDateTime < time())
            $this->addError($attribute, 'تاریخ و زمان انتخاب شده صحیح نمی باشد.');
        elseif ($lastDateTime < (time() + ($params['distance'] * 60)))
            $this->addError($attribute, 'تاریخ و زمان آخرین جلسه از مراسم باید حداقل ' . $params['distance'] . ' دقیقه بعد باشد.');
    }

    public function checkMoreDays()
    {
        Yii::app()->getModule('setting');
        $showEventMoreThanDefault = SiteOptions::model()->getOption('show_event_more_than_default');

        if ($this->more_days > $showEventMoreThanDefault)
            $this->addError('more_days', $this->getAttributeLabel('more_days') . ' نمی تواند بیشتر از ' . $showEventMoreThanDefault . ' باشد.');
    }

    public function checkLongDays()
    {
        Yii::app()->getModule('setting');
        $eventMaxLongDays = SiteOptions::model()->getOption('event_max_long_days');

        if ($this->long_days_run < 1)
            $this->addError('long_days_run', $this->getAttributeLabel('long_days_run') . ' نمی تواند کمتر از 1 باشد.');
        elseif ($this->long_days_run > $eventMaxLongDays)
            $this->addError('long_days_run', $this->getAttributeLabel('long_days_run') . ' نمی تواند بیشتر از ' . $eventMaxLongDays . ' باشد.');
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
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
            'state_id' => 'استان',
            'state' => 'استان',
            'city_id' => 'شهرستان',
            'city' => 'شهرستان',
            'town' => 'شهرک',
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
            'activator_area_code' => 'فعال شدن منطقه شهرداری',
            'activator_postal_code' => 'فعال شدن کدپستی',
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
        $criteria->compare('activator_area_code', $this->activator_area_code);
        $criteria->compare('activator_postal_code', $this->activator_postal_code);
        $criteria->compare('ceremony_poster', $this->ceremony_poster, true);
        $criteria->compare('status', $this->status, true);

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

        if (!empty($_GET['Events']['creator_mobile'])) {
            $criteria->addCondition("creator_id IN (SELECT id FROM iw_users WHERE mobile LIKE :mobile)");
            $criteria->params[':mobile'] = '%' . $this->creator_mobile . '%';
        }

        if (!is_null($condition))
            $criteria->addCondition($condition);

        $criteria->order = 'id DESC';

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
        $defaultShowTimes = CJSON::decode(SiteOptions::model()->getOption('show_event_message'));
        $b = 0;
        foreach ($defaultShowTimes as $item)
            if ($a >= $item[0] and $a <= $item[1])
                $b = $item[2];
        $b = $b / 24;
        $b = (float)$b + (float)$this->more_days;
        $showTime=strtotime(date("Y/m/d",$this->start_date_run)." ".date("H:i", $this->start_time_run));
        return $showTime - ($b * 24 * 60 * 60);
    }

    public function getShowEndTime()
    {
        Yii::app()->getModule('setting');
        $startTime=strtotime(date("Y/m/d",$this->start_date_run)." ".date("H:i", $this->start_time_run));
        return $startTime + ($this->long_days_run * 24 * 60 * 60);
    }
}