<?php
class MessagesTextsBuy extends Buys
{
    public $time,$user_name;

	public function attributeLabels(){
		return array(
			'id' => 'شناسه',
			'user_id' => 'شناسه کاربر',
			'date' => 'تاریخ تراکنش',
			'gateway' => 'درگاه',
			'tracking_no' => 'شماره پیگیری',
			'status' => 'وضعیت پرداخت',
            'active' => 'فعال',
            'time' => 'ساعت تراکنش',
            'charge_kind' => 'نوع شارژ',
            'qty' => 'تعداد صفحات',
            'user_name' => 'نام و نام خانوادگی کاربر',
            'sum_price' => 'جمع پرداختی'
		);
	}

	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
        if(Yii::app()->user->type == 'user')
            $criteria->compare('user_id',Yii::app()->user->userID);
        else
            $criteria->compare('user_id',$this->user_id);

		$criteria->compare('qty',$this->qty);
		$criteria->compare('date',$this->date);
		$criteria->compare('gateway',$this->gateway,true);
		$criteria->compare('tracking_no',$this->tracking_no,true);
		$criteria->compare('status',$this->status);
        $criteria->compare('type',Buys::TYPE_PAGE);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getUrl(){
        return 'messages/texts_buy/?buyId='.$this->id;
    }

    public static function model($className=__CLASS__){
        return parent::model($className);
    }
}
