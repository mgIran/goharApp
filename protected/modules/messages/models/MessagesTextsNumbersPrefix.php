<?php
class MessagesTextsNumbersPrefix extends iWebActiveRecord
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    public static $statusList = array(
        1 => 'فعال',
        0 => 'غیر فعال',
    );

	public function tableName()
	{
        return '{{messages_texts_numbers_prefix}}';
	}
	public function rules()
	{
		return array(
			array('number,minimum_number,maximum_number', 'required'),
            array('number', 'unique'),
			array('number', 'numerical'),
            array('status', 'numerical','integerOnly' => TRUE),
			array('number', 'length', 'max'=>15,'min'=>2),
            array('minimum_number,maximum_number','length' , 'max'=>2,'min'=>1),
			array('id,number,status', 'safe', 'on'=>'search'),
            array('minimum_number','compare','compareAttribute'=>'maximum_number','operator'=>'<=','message'=>'حداقل تعداد ارقام باید کوچک تر از حداکثر تعداد ارقام باشد.'),
            array('maximum_number','compare','compareAttribute'=>'minimum_number','operator'=>'>=','message'=>'حداقل تعداد ارقام باید کوچک تر از حداکثر تعداد ارقام باشد.'),
		);
	}

	public function relations()
	{
        return array();
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'number' => 'پیش شماره',
            'status' => 'وضعیت',
            'minimum_number' => 'حداقل تعداد ارقام',
            'maximum_number' => 'حداکثر تعداد ارقام',
		);
	}



	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('number',$this->number,true);
        $criteria->compare('status',$this->status);
        $criteria->order="id DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
