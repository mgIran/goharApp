<?php

/**
 * This is the model class for table "{{messages_texts_users_numbers}}".
 *
 * The followings are the available columns in table '{{messages_texts_users_numbers}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $number
 * @property integer $status
 */
class MessagesTextsUsersNumbers extends iWebActiveRecord {
    const STATUS_DISABLE = 0,
          STATUS_ENABLE = 1,
          STATUS_USING = 2;

    public static $statusList = array(
        0 => 'غیر فعال',
        1 => 'فعال',
        2 => 'درحال استفاده',
    );

    public $user_name;

	public function tableName()
	{
		return '{{messages_texts_users_numbers}}';
	}

	public function rules()
	{
		return array(
			array('number, status', 'required'),
            array('user_id','filter','filter'=>array($this,'userIdToInteger')),
			array('status', 'numerical', 'integerOnly' => TRUE),
            array('user_id','default', 'setOnEmpty' => TRUE, 'value' => NULL),
            array('user_id','exist','className'=>'Users','attributeName' => 'id','criteria' => 'status = 1 AND deleted = 0'),
			array('number', 'length', 'max'=>30),

			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, number, status', 'safe', 'on'=>'search'),
		);
	}

    public function userIdToInteger($userId){

        $userId = intval($userId);
        if($userId == 0)
            return NULL;
        else
            return $userId;
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
			'id' => 'شناسه',
			'user_id' => 'کاربر',
			'number' => 'شماره خط',
			'status' => 'وضعیت',
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
		$criteria->compare('number',$this->number,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessagesTextsUsersNumbers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
