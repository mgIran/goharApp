<?php

/**
 * This is the model class for table "{{users_logins}}".
 *
 * The followings are the available columns in table '{{users_logins}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property integer $status
 * @property integer $time
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UsersLogins extends iWebActiveRecord
{
    const STATUS_DONE = 1;
    const STATUS_FAILED = 0;

    public static $statusList = array(
        0 => array(
            'value' => 'ناموفق',
            'label' => 'danger'
        ),
        1 => array(
            'value' => 'موفق',
            'label' => 'success'
        ),
    );
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{users_logins}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, status, time', 'numerical', 'integerOnly'=>true),
            array('time','default',
                'value'=>time(),
                'setOnEmpty'=>false,'on'=>'insert'),
            array('ip','default',
                'value'=>Yii::app()->request->getUserHostAddress(),
                'setOnEmpty'=>false,'on'=>'insert'),
			array('ip', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, ip, status, time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
			'ip' => 'Ip',
			'status' => 'Status',
			'time' => 'Time',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('time',$this->time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersLogins the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
