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
 * @property string $min_age_guest
 * @property string $max_age_guest
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
			array('type, subject, conductor, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, complete_address, invitees, filter_type', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('filter_type', 'length', 'max'=>20),
			array('sexed_guest', 'length', 'max'=>255),
			array('min_age_guest, max_age_guest', 'length', 'max'=>3),
			array('type, subject, conductor, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, complete_address, invitees', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, title, type, subject, conductor, sexed_guest, min_age_guest, max_age_guest, town, main_street, by_street, boulevard, afew_ways, squary, bridge, quarter, area_code, postal_code, complete_address, invitees', 'safe', 'on'=>'search'),
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
			'min_age_guest' => 'Min Age Guest',
			'max_age_guest' => 'Max Age Guest',
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
			'complete_address' => 'Complete Address',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('conductor',$this->conductor,true);
		$criteria->compare('sexed_guest',$this->sexed_guest,true);
		$criteria->compare('min_age_guest',$this->min_age_guest,true);
		$criteria->compare('max_age_guest',$this->max_age_guest,true);
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
		$criteria->compare('invitees',$this->invitees,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventFilters the static model class
	 */
	public static function model($className=__CLASS__)
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
}
