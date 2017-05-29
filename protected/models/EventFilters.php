<?php

/**
 * This is the model class for table "{{event_filters}}".
 *
 * The followings are the available columns in table '{{event_filters}}':
 * @property string $id
 * @property integer $user_id
 * @property string $title
 * @property string $type
 * @property string $subject
 * @property string $conductor
 * @property string $sexed_guest
 * @property string $min_age_guests
 * @property string $max_age_guests
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
 * @property string $user_mobile
 * @property string $invitees
 * @property string $filter_type
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class EventFilters extends iWebActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{event_filters}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, filter_type', 'required'),
			array('user_id', 'numerical', 'integerOnly' => true),
			array('user_mobile', 'numerical', 'integerOnly' => true),
			array('user_mobile', 'length', 'max' => 11),
			array('title', 'length', 'max' => 100),
			array('filter_type', 'length', 'max' => 20),
			array('sexed_guest', 'length', 'max' => 255),
			array('min_age_guests, max_age_guests', 'length', 'max' => 3),
			array('type, subject, conductor, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, invitees', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, title, type, subject, conductor, sexed_guest, min_age_guests, max_age_guests, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, invitees', 'safe', 'on' => 'search'),
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
			'id' => 'ID',
			'user_id' => 'User',
			'title' => 'عنوان فیلترینگ',
			'type' => 'Type',
			'subject' => 'Subject',
			'conductor' => 'Conductor',
			'sexed_guest' => 'Sexed Guest',
			'min_age_guests' => 'Min Age Guest',
			'max_age_guests' => 'Max Age Guest',
			'town' => 'Town',
			'main_street' => 'Main Street',
			'by_street' => 'By Street',
			'boulevard' => 'Boulevard',
			'afew_ways' => 'Afew Ways',
			'squary' => 'Squary',
			'bridge' => 'Bridge',
			'quarter' => 'Quarter',
			'area_code' => 'Area Code',
			'postal_code' => 'Postal Code',
			'user_mobile' => 'شماره موبایل ثبت کننده',
			'invitees' => 'Invitees',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('type', $this->type, true);
		$criteria->compare('subject', $this->subject, true);
		$criteria->compare('conductor', $this->conductor, true);
		$criteria->compare('sexed_guest', $this->sexed_guest, true);
		$criteria->compare('min_age_guests', $this->min_age_guests, true);
		$criteria->compare('max_age_guests', $this->max_age_guests, true);
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
		$criteria->compare('user_mobile', $this->user_mobile, true);
		$criteria->compare('invitees', $this->invitees, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventFilters the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * unset invalid attributes
	 * @param $attributes
	 */
	public function unsetInvalidAttributes(&$attributes)
	{
		$invalidChangeAttributes = array(
			'id',
			'user_id',
		);
		foreach($attributes as $key => $item)
			if(in_array($key, $invalidChangeAttributes))
				unset($attributes[$key]);
	}

	public function checkFilterCreatePermission($user_id)
	{
		$freeCount = SiteOptions::getOption('filter_free_count');
		$userFilterCount = $this->countByAttributes(array('user_id' => $user_id));
		$userPaidFilterCount = AppTransactions::model()->countByAttributes(array(
			'user_id' => $user_id,
			'status' => AppTransactions::TRANSACTION_PAID,
			'model_name' => "EventFilters"
		));
		return (int)$userFilterCount < ((int)$freeCount + (int)$userPaidFilterCount)?true:false;
	}


	/**
	 * Search Fields
	 */
	public $typeSearch = null;
	public $subjectSearch = null;
	public $conductorSearch = null;
	public $sexedGuestSearch = null;
	public $minAgeGuestSearch = null;
	public $maxAgeGuestSearch = null;
	public $townSearch = null;
	public $mainStreetSearch = null;
	public $byStreetSearch = null;
	public $boulevardSearch = null;
	public $afewWaysSearch = null;
	public $squarySearch = null;
	public $bridgeSearch = null;
	public $quarterSearch = null;
	public $areaCodeSearch = null;
	public $postalCodeSearch = null;
	public $userMobileSearch = null;
	public $inviteesSearch = null;
	public $date = null;
	public $filterTypeSearch = null;

	/**
	 * Load Search fields from filter or query string
	 */
	public function loadSearchFields()
	{
		$this->typeSearch 				= CJSON::decode($this->type)?CJSON::decode($this->type):$this->type;
		$this->subjectSearch 			= CJSON::decode($this->subject)?CJSON::decode($this->subject):$this->subject;
		$this->conductorSearch 			= CJSON::decode($this->conductor)?CJSON::decode($this->conductor):$this->conductor;
		$this->sexedGuestSearch 		= $this->sexed_guest;
		$this->minAgeGuestSearch 		= $this->min_age_guests;
		$this->maxAgeGuestSearch 		= $this->max_age_guests;
		$this->townSearch 				= CJSON::decode($this->town)?CJSON::decode($this->town):$this->town;
		$this->mainStreetSearch 		= CJSON::decode($this->main_street)?CJSON::decode($this->main_street):$this->main_street;
		$this->byStreetSearch 			= CJSON::decode($this->by_street)?CJSON::decode($this->by_street):$this->by_street;
		$this->boulevardSearch 			= CJSON::decode($this->boulevard)?CJSON::decode($this->boulevard):$this->boulevard;
		$this->afewWaysSearch 			= CJSON::decode($this->afew_ways)?CJSON::decode($this->afew_ways):$this->afew_ways;
		$this->squarySearch 			= CJSON::decode($this->squary)?CJSON::decode($this->squary):$this->squary;
		$this->bridgeSearch 			= CJSON::decode($this->bridge)?CJSON::decode($this->bridge):$this->bridge;
		$this->quarterSearch 			= CJSON::decode($this->quarter)?CJSON::decode($this->quarter):$this->quarter;
		$this->areaCodeSearch 			= CJSON::decode($this->area_code)?CJSON::decode($this->area_code):$this->area_code;
		$this->postalCodeSearch 		= CJSON::decode($this->postal_code)?CJSON::decode($this->postal_code):$this->postal_code;
		$this->userMobileSearch			= $this->user_mobile;
		$this->inviteesSearch 			= CJSON::decode($this->invitees)?CJSON::decode($this->invitees):$this->invitees;
	}


	/**
	 * @param $criteria CDbCriteria
	 */
	public function searchCriteria(&$criteria)
	{
		$andArr = [];
		// date

		// type
		if(is_array($this->typeSearch)){
			$orArr = [];
			foreach($this->typeSearch as $key => $item){
				$orArr[] = "t.type1 LIKE :type$key OR t.type2 LIKE :type$key";
				$criteria->params[":type$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' AND ', $orArr) . ')';
		}elseif($this->typeSearch){
			$andArr[] = "(t.type1 LIKE :type0 OR t.type2 LIKE :type0)";
			$criteria->params[":type0"] = "%{$this->typeSearch}%";
		}
		// subject
		if(is_array($this->subjectSearch)){
			$orArr = [];
			foreach($this->subjectSearch as $key => $item){
				$orArr[] = "t.subject1 LIKE :subject$key OR t.subject2 LIKE :subject$key";
				$criteria->params[":subject$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->subjectSearch){
			$andArr[] = "(t.subject1 LIKE :subject0 OR t.subject2 LIKE :subject0)";
			$criteria->params[":subject0"] = "%{$this->subjectSearch}%";
		}
		// conductor
		if(is_array($this->conductorSearch)){
			$orArr = [];
			foreach($this->conductorSearch as $key => $item){
				$orArr[] = "t.conductor1 LIKE :conductor$key OR t.conductor2 LIKE :conductor$key";
				$criteria->params[":conductor$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->conductorSearch){
			$andArr[] = "(t.conductor1 LIKE :conductor0 OR t.conductor2 LIKE :conductor0)";
			$criteria->params[":conductor0"] = "%{$this->conductorSearch}%";
		}
		// sex
		if($this->sexedGuestSearch){
//			if($this->sexedGuestSearch == 'both'){
//				$andArr[] = "(t.sexed_guest = :sex0 AND t.sexed_guest = :sex1)";
//				$criteria->params[":sex0"] = "male";
//				$criteria->params[":sex1"] = "female";
//			}else{
			$andArr[] = "(t.sexed_guest = :sex)";
			$criteria->params[":sex"] = $this->sexedGuestSearch;
//			}
		}
		// age
		if($this->minAgeGuestSearch){
			$andArr[] = "(t.min_age_guests >= :minAge)";
			$criteria->params[":minAge"] = $this->minAgeGuestSearch;
		}
		if($this->maxAgeGuestSearch){
			$andArr[] = "(t.max_age_guests <= :maxAge)";
			$criteria->params[":maxAge"] = $this->maxAgeGuestSearch;
		}
		// State And City
		if($this->user){
			$orArr = [];
			if($this->user->birth_city_id){
				$orArr [] = 't.state_id = :birth_city OR t.city_id = :birth_city';
				$criteria->params[":birth_city"] = $this->user->birth_city_id;
			}
			if($this->user->home_city_id){
				$orArr [] = 't.state_id = :home_city OR t.city_id = :home_city';
				$criteria->params[":home_city"] = $this->user->home_city_id;
			}
			if($this->user->work_city_id){
				$orArr [] = 't.state_id = :work_city OR t.city_id = :work_city';
				$criteria->params[":work_city"] = $this->user->work_city_id;
			}
			if($this->user->schooling_city_id_1){
				$orArr [] = 't.state_id = :schooling_city_1 OR t.city_id = :schooling_city_1';
				$criteria->params[":schooling_city_1"] = $this->user->schooling_city_id_1;
			}
			if($this->user->schooling_city_id_2){
				$orArr [] = 't.state_id = :schooling_city_2 OR t.city_id = :schooling_city_2';
				$criteria->params[":schooling_city_2"] = $this->user->schooling_city_id_2;
			}
			if($this->user->favorite_city_id_1){
				$orArr [] = 't.state_id = :favorite_city_1 OR t.city_id = :favorite_city_1';
				$criteria->params[":favorite_city_1"] = $this->user->favorite_city_id_1;
			}
			if($this->user->favorite_city_id_2){
				$orArr [] = 't.state_id = :favorite_city_2 OR t.city_id = :favorite_city_2';
				$criteria->params[":favorite_city_2"] = $this->user->favorite_city_id_2;
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}
		// Addresses
		// town
		if(is_array($this->townSearch)){
			$orArr = [];
			foreach($this->townSearch as $key => $item){
				$orArr[] = "t.town LIKE :town$key";
				$criteria->params[":town$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->townSearch){
			$andArr[] = "(t.town LIKE :town)";
			$criteria->params[":town"] = "%{$this->townSearch}%";
		}
		// main street
		if(is_array($this->mainStreetSearch)){
			$orArr = [];
			foreach($this->mainStreetSearch as $key => $item){
				$orArr[] = "t.main_street LIKE :mainStreet$key";
				$criteria->params[":mainStreet$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->mainStreetSearch){
			$andArr[] = "(t.main_street LIKE :mainStreet)";
			$criteria->params[":mainStreet"] = "%{$this->mainStreetSearch}%";
		}
		// by street
		if(is_array($this->byStreetSearch)){
			$orArr = [];
			foreach($this->byStreetSearch as $key => $item){
				$orArr[] = "t.by_street LIKE :byStreet$key";
				$criteria->params[":byStreet$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->byStreetSearch){
			$andArr[] = "(t.by_street LIKE :byStreet)";
			$criteria->params[":byStreet"] = "%{$this->byStreetSearch}%";
		}
		// boulevard
		if(is_array($this->boulevardSearch)){
			$orArr = [];
			foreach($this->boulevardSearch as $key => $item){
				$orArr[] = "t.boulevard LIKE :boulevard$key";
				$criteria->params[":boulevard$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->boulevardSearch){
			$andArr[] = "(t.boulevard LIKE :boulevard)";
			$criteria->params[":boulevard"] = "%{$this->boulevardSearch}%";
		}
		// a few ways
		if(is_array($this->afewWaysSearch)){
			$orArr = [];
			foreach($this->afewWaysSearch as $key => $item){
				$orArr[] = "t.afew_ways LIKE :afewWays$key";
				$criteria->params[":afewWays$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->afewWaysSearch){
			$andArr[] = "(t.afew_ways LIKE :afewWays)";
			$criteria->params[":afewWays"] = "%{$this->afewWaysSearch}%";
		}
		// squary
		if(is_array($this->squarySearch)){
			$orArr = [];
			foreach($this->squarySearch as $key => $item){
				$orArr[] = "t.squary LIKE :squary$key";
				$criteria->params[":squary$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->squarySearch){
			$andArr[] = "(t.squary LIKE :squary)";
			$criteria->params[":squary"] = "%{$this->squarySearch}%";
		}
		// bridge
		if(is_array($this->bridgeSearch)){
			$orArr = [];
			foreach($this->bridgeSearch as $key => $item){
				$orArr[] = "t.bridge LIKE :bridge$key";
				$criteria->params[":bridge$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->bridgeSearch){
			$andArr[] = "(t.bridge LIKE :bridge)";
			$criteria->params[":bridge"] = "%{$this->bridgeSearch}%";
		}
		// quarter
		if(is_array($this->quarterSearch)){
			$orArr = [];
			foreach($this->quarterSearch as $key => $item){
				$orArr[] = "t.quarter LIKE :quarter$key";
				$criteria->params[":quarter$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->quarterSearch){
			$andArr[] = "(t.quarter LIKE :quarter)";
			$criteria->params[":quarter"] = "%{$this->quarterSearch}%";
		}
		// area code
		if(is_array($this->areaCodeSearch)){
			$orArr = [];
			foreach($this->areaCodeSearch as $key => $item){
				$orArr[] = "t.area_code = :areaCode$key";
				$criteria->params[":areaCode$key"] = $item;
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->areaCodeSearch){
			$andArr[] = "(t.area_code = :areaCode)";
			$criteria->params[":areaCode"] = $this->areaCodeSearch;
		}
		// postal code
		if(is_array($this->postalCodeSearch)){
			$orArr = [];
			foreach($this->postalCodeSearch as $key => $item){
				$item = substr($item, 0, 6);
				$orArr[] = "t.postal_code LIKE :postalCode$key";
				$criteria->params[":postalCode$key"] = "{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->postalCodeSearch){
			$item = substr($this->postalCodeSearch, 0, 6);
			$andArr[] = "(t.postal_code LIKE :postalCode)";
			$criteria->params[":postalCode"] = "{$item}%";
		}
		// user mobile
		if(is_array($this->userMobileSearch)){
			$orArr = [];
			foreach($this->postalCodeSearch as $key => $item){
				$orArr[] = "t.postal_code LIKE :userMobile$key";
				$criteria->params[":userMobile$key"] = "%{$item}%";
			}
			$andArr[] = '(' . implode(' OR ', $orArr) . ')';
		}elseif($this->userMobileSearch){
			$andArr[] = "(t.user_mobile LIKE :userMobile)";
			$criteria->params[":userMobile"] = "%{$this->userMobileSearch}%";
		}
		// invitees
		if($this->inviteesSearch){
			$regexNum = 0;
			foreach($this->inviteesSearch as $key => $items){
				$orArr = [];
				$jKey = json_encode($key);
				$jKey = substr($jKey, 1, (strlen($jKey) - 2));
				$jKey = str_ireplace('\\', '[\\]', $jKey);
				if(is_array($items)){
					foreach($items as $value){
						if(!empty($value)){
							$value = json_encode($value);
							$value = substr($value, 1, (strlen($value) - 2));
							$value = str_ireplace('\\', '[\\]', $value);
							$orArr[] = "t.invitees REGEXP :regexp$regexNum";
							$criteria->params[":regexp$regexNum"] = "(.*\"{$jKey}\":[[]([,]*[\"]*[\\]?[a-zA-Z0-9 ]*[\"]*[,]*)*\"{$value}\"([,]*[\"]*[\\]?[a-zA-Z0-9 ]*[\"]*[,]*)*[]].*)";
							$regexNum++;
						}
					}
					$andArr[] = '(' . implode(' OR ', $orArr) . ')';
				}else{
					$value = $items;
					if(!empty($value)){
						$value = json_encode($value);
						$value = substr($value, 1, (strlen($value) - 2));
						$value = str_ireplace('\\', '[\\]', $value);
						$andArr[] = "t.invitees REGEXP :regexp$regexNum";
						$criteria->params[":regexp$regexNum"] = "(.*\"{$jKey}\":[[]([,]*[\"]*[\\]?[a-zA-Z0-9 ]*[\"]*[,]*)*\"{$value}\"([,]*[\"]*[\\]?[a-zA-Z0-9 ]*[\"]*[,]*)*[]].*)";
						$regexNum++;
					}
				}
			}
		}
		$sql = implode(' AND ', $andArr);
		$criteria->addCondition($sql);
	}
}