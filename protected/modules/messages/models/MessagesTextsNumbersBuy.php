<?php
class MessagesTextsNumbersBuy extends iWebActiveRecord
{

    const SPECIAL_YES = 1;
    const SPECIAL_NO = 0;
    public static $specialList = array(
        1 => 'بله',
        0 => 'خیر',
    );
    public $user_name,$user_number,$user_id,$sum_price,$status;

    public $time;
	public function tableName(){
		return '{{messages_texts_numbers_buy}}';
	}

	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buy_id,number', 'required'),
            array('number', 'UniqueAttributesValidator', 'with'=>'prefix_id',"message"=>'شماره "{value}" قبلا ثبت شده است، لطفا شماره دیگری را وارد نمایید','on'=>'insert'),
            array('number', 'numerical'),
            array('prefix_id', 'numerical', 'integerOnly'=>true),
            array('number', 'length', 'max'=>15),
            array('special','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,number,special', 'safe', 'on'=>'search'),
		);
	}

	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'buy' => array(self::BELONGS_TO, 'Buys', 'buy_id'),
            'prefix' => array(self::BELONGS_TO, 'MessagesTextsNumbersPrefix', 'prefix_id'),
		);
	}

	public function attributeLabels(){
		return array(
			'id' => 'شناسه',
            'charge_kind' => 'نوع شارژ',
            'number' => 'شماره',
            'prefix_id' => 'پیش شماره',
            'prefix' => 'پیش شماره',
            'user_name' => 'نام و نام خانوادگی کاربر',
            'user_number' => 'شماره خط',
            'special' => 'خط اختصاصی',
            'status' => 'وضعیت',
            'sum_price' => 'جمع پرداختی',
            'user_id' => 'شناسه کاربر',
		);
	}

	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
        $criteria->alias = 'numbers';
        $criteria->with = array('buy','buy.user','prefix');

    	$criteria->compare('buy_id',$this->buy_id);
        $criteria->compare('special',$this->special);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('buy.status',$this->status);
        //$criteria->compare('user_name',$this->user_name);

        $criteria->compare('sum_price',$this->sum_price);

        if(!empty($this->user_number)){
            $criteria->addSearchCondition('CONCAT(IF(prefix_id IS NOT NULL,prefix.number,\'\'),\' \',numbers.number)',$this->user_number);
            //$criteria->addCondition('CONCAT(IF(prefix_id IS NOT NULL,prefix_id,\'\'),\' \',number),\' \',last_name) LIKE :userNumber');
            //$criteria->params[':userNumber'] = iWebHelper::searchArabicAndPersian($this->user_number);
        }

        if(!empty($this->user_name)){
            $criteria->addCondition('CONCAT(first_name,\' \',last_name) REGEXP :userName');
            $criteria->params[':userName'] = iWebHelper::searchArabicAndPersian($this->user_name);
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlansBuys the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
