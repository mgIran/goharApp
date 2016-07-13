<?php
class UsersPlaces extends iWebActiveRecord{
    public $town_search;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{users_places}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
            array('national_id_prefix,postal_code_prefix,phone_number_prefix', 'filter','filter' =>array($this,'tokenize')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, parent_id, town_search', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'UsersPlaces', 'parent_id'),
			'places' => array(self::HAS_MANY, 'UsersPlaces', 'parent_id'),
			'usersLegalDocuments' => array(self::HAS_MANY, 'UsersLegalDocuments', 'home_city_id'),
			'usersLegalDocuments1' => array(self::HAS_MANY, 'UsersLegalDocuments', 'birth_city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'شناسه',
			'title' => 'عنوان',
			'parent_id' => 'نام استان',
            'national_id_prefix' => 'پیش شماره کد ملی',
            'postal_code_prefix' => 'پیش شماره کد پستی',
            'phone_number_prefix' => 'پیش شماره تلفن'
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('parent_id',$this->parent_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function searchTowns()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->alias='town';
        $criteria->compare('town.id',$this->id,true);
        $criteria->compare('town.title',$this->title,true);
        $criteria->addCondition('parent_id IS NULL');

        $dataProvider=null;
        if(isset($_GET['page_size']))
            $dataProvider=new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$_GET['page_size']
                ),
            ));
        else
            $dataProvider=new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>20
                ),
            ));

        return $dataProvider;
    }

    public function searchCities()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->with=array('parent');
        $criteria->alias='city';
        $criteria->compare('city.id',$this->id,true);
        $criteria->compare('city.title',$this->title,true);
        $criteria->compare('parent.title',$this->parent_id,true);
        $criteria->addCondition('city.parent_id IS NOT NULL');

        $dataProvider=null;
        if(isset($_GET['page_size']))
            $dataProvider=new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$_GET['page_size']
                ),
            ));
        else
            $dataProvider=new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>40
                ),
            ));

        return $dataProvider;
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public static function towns(){
        $models = UsersPlaces::model()->findAll('parent_id IS NULL');
        return CHtml::listData($models, 'id', 'title');
    }

    public static function citiesByTown($townId)
    {
        $models = UsersPlaces::model()->findAll('parent_id = ' . $townId);
        return CHtml::listData($models, 'id', 'title');
    }

    public function tokenize($values){
        if(is_array($values)){
            $values = implode(',',$values);
        }
        return $values;
    }
}
