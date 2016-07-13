<?php
class MessagesTextsNumbersCheck extends iWebActiveRecord {
    public $prefix_id,$number,$show_message,$isSpecial = FALSE;

    public function tableName(){
        return '{{messages_texts_numbers_prefix}}';
    }

	public function rules(){
		return array(
			array('prefix_id,number', 'required'),
			array('prefix_id,number', 'numerical', 'integerOnly'=>true),
            array('prefix_id,number', 'check'),
            array('number', 'checkLimit'),
            //array('number', 'length', 'max'=>(isset($this->prefix)?$this->prefix->maximum_number:1),'min'=>(isset($this->prefix)?$this->prefix->minimum_number:1)),
		);
	}

	public function relations(){
		return array(
            'prefix' => array(self::BELONGS_TO, 'MessagesTextsNumbersPrefix', 'prefix_id'),
		);
	}

	public function attributeLabels(){
		return array(
            'number' => 'شماره',
            'prefix_id' => 'پیش شماره',
            'prefix' => 'پیش شماره',
		);
	}

    public function check($attribute,$params){
        $count = MessagesTextsNumbersBuy::model()->count(array(
            'with' => 'buy',
            'condition' => 'prefix_id = :prefixId AND number = :num AND status != :status',
            'params' => array(
                ':prefixId' => $this->prefix_id,
                ':num' => $this->number,
                ':status' => Buys::STATUS_FAILED
            )
        ));
        if($count)
            $this->addError('prefix_id', 'شماره مورد نظر شما ثبت یا روزو شده می باشد.');
        else{

            $special = MessagesTextsNumbersSpecials::model()->find('prefix_id = :prefixId AND number = :num',array(
                ':prefixId' => $this->prefix_id,
                ':num' => $this->number
            ));

            if(!is_null($special))
                if($special->status == 1)
                    $this->isSpecial = $special;
                elseif($special->status != 1)
                    $this->addError('prefix_id', 'شماره مورد نظر شما ثبت یا روزو شده می باشد.');
        }
    }

    public function checkLimit($attribute,$params) {
        $min = intval($this->prefix->minimum_number);
        $max = intval($this->prefix->maximum_number);
        $length = strlen($this->prefix->number.$this->number);
        $message = 'شماره باید ';
        $message2 = ' رقم باشد.';
        if($length < $min)
            $this->addError('number', $message.'حداقل "'.$min.'"'.$message2);
        if($length > $max)
            $this->addError('number', $message.'حداکثر "'.$max.'"'.$message2);
    }

    protected function afterValidate(){
        $this->checkError();

        return parent::afterValidate();
    }

    public function checkError($message = true){
        if($message)
            if(count($this->errors))
                $this->addError('show_message','<div class="alert alert-danger" role="alert">شماره موردنظر قابل ثبت نمی باشد.</div>');
            else {
                if($this->isSpecial){
                    $link = Yii::app()->createAbsoluteUrl("messages/numbers_buy/buy?special_id=".$this->isSpecial->id);
                    $this->addError('show_message','<div class="alert alert-info" role="alert">
        خط مورد نظر از خطوط آماده فروش می باشد.
                        <br/><br/>
                        قیمت خط '
                        . number_format($this->isSpecial->price) . '
             تومان                  می باشد.
             برای خرید <a href="'.$link.'">کلیک</a> نمایید.
                        </div>');
                }
                else{
                    $price = $this->getPrice($this->prefix->number,$this->number);
                    if($price)
                        $this->addError('show_message','<div class="alert alert-success" role="alert">
        تبریک، این شماره خط مجازی آزاد است
                        <br/><br/>
                        قیمت خط '
                            . number_format($price) . '
             تومان                  می باشد.
                        </div>');
                    else{
                        $link = "#";
                        $this->addError('show_message','<div class="alert alert-info" role="alert">
                        بررسی و فعالسازی استعلام این خط مجازی 48 ساعت  طول می کشد، اگر موافق هستید،
                        <a href="'.$link.'">کلیک</a>
                         نمایید.
                        </div>');
                    }
                }
            }
        else{
            return count($this->errors);
        }
    }

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

    public static function getPrice($prefix,$number){
        $len = strlen($prefix.$number);

        $basePrice = MessagesTextsNumbersBasePrices::model()->findByPk($len);
        if(!is_null($basePrice)){
            $arrayNumber = str_split($number);
            $temp = array();
            foreach($arrayNumber as $num){

                if(!isset($temp[$num]))
                    $temp[$num] = 1;
                else
                    $temp[$num]++;
            }
            $joint = 0;
            foreach($temp as $num){
                if($num != 1)
                    $joint += $num;
            }
            if($joint != 0)
                $joint = $joint / 10;

            $series = 0;
            foreach($arrayNumber as $num){
                if(!isset($tmp))
                    $tmp = $num;
                elseif(($tmp + 1 == $num) OR ($tmp - 1 == $num) OR $tmp == $num)
                    $series += 2;
            }

            if($series != 0)
                $series = $series / 10;

            $price = $basePrice->price * (1 + $joint + $series);

            return $price;
        }
        else{
            return FALSE;
        }
    }
}
