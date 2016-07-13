<?php
class MessagesTextsNumbersSpecials extends iWebActiveRecord
{
    const STATUS_DISABLE = 0;
    const STATUS_AVAILABLE = 1;
    const STATUS_RESERVED = 2;
    const STATUS_SOLD = 3;

    public static $statusList = array(
        0 => 'غیر فعال',
        1 => 'موجود',
        2 => 'رزرو شده',
        3 => 'فروخته شده',
    );

	public function tableName(){
        return '{{messages_texts_numbers_specials}}';
	}

	public function rules(){
		return array(
			array('number', 'required'),
            array('number', 'UniqueAttributesValidator', 'with'=>'prefix_id',"message"=>'شماره "{value}" قبلا ثبت شده است، لطفا شماره دیگری را وارد نمایید','on'=>'insert'),
			array('number', 'numerical'),
            array('prefix_id', 'numerical', 'integerOnly'=>true),
            array('view', 'length', 'max'=>30),
            array('number', 'length', 'max'=>15),
            array('price', 'length', 'max'=>20),
            array('view','filter','filter'=>'htmlspecialchars'),
            array('view', 'match', 'pattern'=>'!^[^/]+$!', 'message'=>'نما نمی تواند شامل کاراکتر های خاص باشد.'),
            array('price', 'match', 'pattern'=>'!^[\d,]+$!', 'message'=>'قیمت فقط باید شامل اعداد باشد.'),
			array('id,number,status', 'safe', 'on'=>'search'),
		);
	}

	public function relations(){
        return array(
            'prefix' => array(self::BELONGS_TO, 'MessagesTextsNumbersPrefix', 'prefix_id'),
        );
	}

	public function attributeLabels(){
		return array(
			'id' => 'شناسه',
			'number' => 'شماره',
            'status' => 'وضعیت',
            'view' => 'نما',
            'prefix_id' => 'پیش شماره',
            'prefix' => 'پیش شماره',
            'price' => 'قیمت',
		);
	}

	public function search($join = TRUE){
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('number',$this->number,true);
        $criteria->compare('prefix',(isset($this->prefix->number))?$this->prefix->number:NULL,true);
        $criteria->compare('price',$this->price,true);
        $criteria->compare('status',$this->status);
        $criteria->order="id DESC";

        if($join)
        {
            //$criteria->alias = 'specials';
            //$criteria->join = "LEFT JOIN iw_messages_texts_numbers_buy buy";
            //$criteria->addCondition('specials.prefix_id != buy.prefix_id AND specials.number != buy.number');
            $criteria->addCondition('CONCAT(IF(prefix_id IS NULL,\'\',prefix_id),\'##\',IF(number IS NULL,\'\',number)) NOT IN(SELECT CONCAT(IF(prefix_id IS NULL,\'\',prefix_id),\'##\',IF(number IS NULL,\'\',number)) FROM iw_messages_texts_numbers_buy WHERE special = 1 AND status IN(1,2))');

        }

        //var_dump($criteria);exit;


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

    public function beforeSave(){
        $this->price = str_replace(',','',$this->price);
        return parent::beforeSave();
    }
}
