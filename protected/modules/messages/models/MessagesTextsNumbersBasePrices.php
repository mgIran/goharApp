<?php
class MessagesTextsNumbersBasePrices extends iWebActiveRecord
{
	public function tableName()
	{
        return '{{messages_texts_numbers_base_prices}}';
	}
	public function rules()
	{
		return array(
			array('id,price', 'required'),
            array('id', 'unique'),
			array('id', 'numerical'),
			array('id', 'length', 'max'=>2,'min'=>1),
            array('price', 'match', 'pattern'=>'!^[\d,]+$!', 'message'=>'قیمت فقط باید شامل اعداد باشد.'),
			array('id,price', 'safe', 'on'=>'search')
		);
	}

	public function relations()
	{
        return array();
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'تعداد ارقام',
			'price' => 'قیمت'
		);
	}



	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('price',$this->price,true);
        $criteria->order="id DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave(){
        $this->price = str_replace(',','',$this->price);
        return parent::beforeSave();
    }
}
