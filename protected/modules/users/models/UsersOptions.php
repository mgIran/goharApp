<?php

/**
 * This is the model class for table "iw_users_options".
 *
 * The followings are the available columns in table 'iw_users_options':
 * @property integer $id
 * @property integer $user_id
 * @property string $options
 * @property string $value
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UsersOptions extends iWebActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{users_options}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, options', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('options', 'length', 'max'=>50),
			array('value', 'length', 'max'=>120),
            array('options, value', 'filter', 'filter'=>'trim'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, options, value', 'safe', 'on'=>'search'),
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
			'user_id' => 'user',
			'options' => 'Options',
			'value' => 'Value',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('options',$this->options,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersOptions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function generateActivateCode($id){
        $activateCode = UsersOptions::model()->deleteAll("options = 'activate_code'");
        $newActivateCode = new UsersOptions;
        $newActivateCode->user_id = $id;
        $newActivateCode->options = 'activate_code';

        $limit = array("_","__","___",",","-","."," ");
        $time=microtime();
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, 20) as $k) $rand .= $seed[$k];
        $hash= substr($time,10,4).$rand;
        $hash=str_replace($limit,"",$hash);
        $newActivateCode->value = $hash;
        if($newActivateCode->save())
            return $hash;
        else
            return false;
    }
}
