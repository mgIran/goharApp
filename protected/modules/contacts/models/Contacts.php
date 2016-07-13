<?php

/**
 * This is the model class for table "{{contacts}}".
 *
 * The followings are the available columns in table '{{contacts}}':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $mobile
 * @property string $email
 * @property integer $cat_id
 *
 * The followings are the available model relations:
 * @property ContactsCategories $cat
 */
class Contacts extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{contacts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id', 'required'),
			array('cat_id', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name', 'length', 'max'=>255),
			array('mobile', 'length', 'max'=>15),
			array('email', 'length', 'max'=>100),
            array('email', 'email'),
            array('email,mobile','default', 'setOnEmpty' => true, 'value' => null),
            array(
                'mobile',
                'match', 'pattern' => '/^(0|\+98){0,1}9{1}\d{9}$/',
                'message'=>'شماره موبایل باید صحیح و بصورت شماره موبایل واقعی ثبت شود.'
            ),
            array('email', 'isUnique',"message"=>'ایمیل "{value}" ثبت شده است، لطفا ایمیل  دیگری را وارد نمایید','on'=>array('insert')),
            array('mobile', 'isUnique',"message"=>'شماره  "{value}" ثبت شده است، لطفا شماره موبایل دیگری را وارد نمایید','on'=>array('insert')),
            //array('mobile', 'UniqueAttributesValidator', 'with'=>'cat_id',"message"=>'شماره  "{value}" ثبت شده است، لطفا شماره موبایل دیگری را وارد نمایید','on'=>array('insert')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, first_name, last_name, mobile, email, cat_id', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'ContactsCategories', 'cat_id'),
		);
	}

    public function isUnique($attribute,$params){
        $uniqueValidator = new CUniqueValidator();
        $uniqueValidator->attributes = array($attribute);
        if(isset($params['message']))
            $uniqueValidator->message = $params['message'];

        $criteria = new CDbCriteria();
        $criteria->together = TRUE;
        $criteria->with = 'category';
        $criteria->compare('category.user_id',Yii::app()->user->userID);

        $uniqueValidator->criteria = $criteria;

        $uniqueValidator->validate($this);
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'first_name' => 'نام',
			'last_name' => 'نام خانوادگی',
			'mobile' => 'شماره موبایل',
			'email' => 'ایمیل',
			'cat_id' => 'گروه',
            'category' => 'گروه',
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('cat_id',$this->cat_id);

        $criteria->together = TRUE;
        $criteria->with = 'category';
        $criteria->compare('category.user_id',Yii::app()->user->userID);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contacts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
