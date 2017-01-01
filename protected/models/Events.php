<?php

/**
 * This is the model class for table "{{events}}".
 *
 * The followings are the available columns in table '{{events}}':
 * @property string $id
 * @property string $creator_type
 * @property string $creator_id
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
 * @property string $max_more_days
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
 *
 * The followings are the available model relations:
 * @property EventCategories[] $iwEventCategories
 */
class Events extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{events}}';
	}

	public $state;
	public $city;
	public $type1;
	public $type2;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('creator_type, creator_id, type1, subject1, sexed_guest, min_age_guests, max_age_guests, start_date_run, long_days_run, start_time_run, end_time_run, state_id, city_id, complete_address', 'required'),
			array('activator_area_code, activator_postal_code', 'numerical', 'integerOnly'=>true),
			array('subject1, subject2, conductor1, conductor2, reception, ceremony_poster', 'length', 'max'=>256),
			array('sexed_guest', 'length', 'max'=>6),
			array('min_age_guests, max_age_guests, long_days_run, max_more_days, more_days, area_code', 'length', 'max'=>2),
			array('start_date_run, start_time_run, end_time_run', 'length', 'max'=>20),
			array('state_id, city_id, postal_code', 'length', 'max'=>10),
			array('creator_type', 'length', 'max'=>50),
			array('creator_id', 'length', 'max'=>11),
			array('town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter', 'length', 'max'=>25),
			array('type1, type2, state, city, complete_details, invitees', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('creator_type, creator_id, type1, state, city, subject1, subject2, conductor1, conductor2, sexed_guest, min_age_guests, max_age_guests, start_date_run, long_days_run, start_time_run, end_time_run, max_more_days, more_days, state_id, city_id, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, complete_address, complete_details, reception, invitees, activator_area_code, activator_postal_code, ceremony_poster', 'safe', 'on'=>'search'),
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
			'categories' => array(self::MANY_MANY, 'EventCategories', '{{event_category_rel}}(event_id, category_id)'),
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
			'max_more_days' => 'Max More Days',
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
			'type1' => 'نوع مراسم',
			'type2' => 'نوع مراسم',
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
		$criteria->compare('creator_type',$this->creator_type,true);
		$criteria->compare('creator_id',$this->creator_id,true);
		$criteria->compare('subject1',$this->subject1,true);
		$criteria->compare('subject2',$this->subject2,true);
		$criteria->compare('conductor1',$this->conductor1,true);
		$criteria->compare('conductor2',$this->conductor2,true);
		$criteria->compare('sexed_guest',$this->sexed_guest,true);
		$criteria->compare('min_age_guests',$this->min_age_guests,true);
		$criteria->compare('max_age_guests',$this->max_age_guests,true);
		$criteria->compare('start_date_run',$this->start_date_run,true);
		$criteria->compare('long_days_run',$this->long_days_run,true);
		$criteria->compare('start_time_run',$this->start_time_run,true);
		$criteria->compare('end_time_run',$this->end_time_run,true);
		$criteria->compare('max_more_days',$this->max_more_days,true);
		$criteria->compare('more_days',$this->more_days,true);
		$criteria->compare('state_id',$this->state_id,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('town',$this->town,true);
		$criteria->compare('main_street',$this->main_street,true);
		$criteria->compare('by_street',$this->by_street,true);
		$criteria->compare('boulevard',$this->boulevard,true);
		$criteria->compare('afew_ways',$this->afew_ways,true);
		$criteria->compare('squary',$this->squary,true);
		$criteria->compare('bridge',$this->bridge,true);
		$criteria->compare('quarter',$this->quarter,true);
		$criteria->compare('area_code',$this->area_code,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('complete_address',$this->complete_address,true);
		$criteria->compare('complete_details',$this->complete_details,true);
		$criteria->compare('reception',$this->reception,true);
		$criteria->compare('invitees',$this->invitees,true);
		$criteria->compare('activator_area_code',$this->activator_area_code);
		$criteria->compare('activator_postal_code',$this->activator_postal_code);
		$criteria->compare('ceremony_poster',$this->ceremony_poster,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Events the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function afterSave(){
		if($this->type1)
		{
			$model = EventCategories::model()->findByAttributes(array('title'=>$this->type1));
			if($model)
			{
				$rel = new EventCategoryRel();
				$rel->event_id = $this->id;
				$rel->category_id = $model->id;
				$rel->save(false);
			}
		}
		if($this->type2)
		{
			$model = EventCategories::model()->findAllByAttributes(array('title'=>$this->type2));
			if($model)
			{
				$rel = new EventCategoryRel();
				$rel->event_id = $this->id;
				$rel->category_id = $model->id;
				$rel->save(false);
			}
		}
		parent::afterSave();
	}
}
