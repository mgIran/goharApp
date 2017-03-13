<?php
/**
 * This is the model class for table "{{plans_buys}}".
 *
 * The followings are the available columns in table '{{plans_buys}}':
 * @property string $buy_id
 * @property integer $plan_id
 * @property integer $charge_kind
 * @property integer $active
 * @property integer $expire_date
 *
 * The followings are the available model relations:
 * @property Plans $plan
 * @property Buys $buy
 */
class PlansBuys extends iWebActiveRecord
{
    const KIND_ONLINE = 1;
    const KIND_DELAY = 2;


    public static $kindList = array(
        1 => 'آنلاین',
        2 => 'باتاخیر',
    );

    public $user_name,$plan_name,$user_id,$status,$sum_price;
    public $time;
	public function tableName() {
		return '{{plans_buys}}';
	}

	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buy_id,plan_id', 'required'),
			array('plan_id, active,charge_kind,expire_date', 'numerical', 'integerOnly'=>true),
			//array('expire_date','default','value'=> $this->buy->date + ($this->plan->expire_time * 86400)),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('buy_id, plan_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'plan' => array(self::BELONGS_TO, 'Plans', 'plan_id'),
			'buy' => array(self::BELONGS_TO, 'Buys', 'buy_id'),
		);
	}

	public function attributeLabels(){
		return array(
			'buy_id' => 'شناسه خرید',
			'user_id' => 'شناسه کاربر',
			'plan_id' => 'شناسه پلن',
			'date' => 'تاریخ تراکنش',
			'gateway' => 'درگاه',
			'tracking_no' => 'شماره پیگیری',
			'status' => 'وضعیت پرداخت',
            'active' => 'فعال',
            'time' => 'ساعت تراکنش',
            'charge_kind' => 'نوع شارژ',
            'user_name' => 'نام و نام خانوادگی کاربر',
            'plan_name' => 'نام پلن',
            'sum_price' => 'جمع پرداختی',
			//'expire_date' => 'تاریخ اتمام پلن',
		);
	}

	public function search(){
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

        $criteria->with = 'buy';

        //$criteria->addCondition('gateway IS NOT NULL');
		$criteria->compare('buy_id',$this->buy_id);
        $criteria->addCondition('sum_price != 0');
		//$criteria->compare('user_id',$this->buy->user_id);
		//$criteria->compare('plan_id',$this->plan_id);
		//$criteria->compare('date',$this->buy->date);
		//$criteria->compare('gateway',$this->buy->gateway,true);
		//$criteria->compare('tracking_no',$this->buy->tracking_no,true);
		//$criteria->compare('status',$this->buy->status);

		//$criteria->compare('expire_date',$this->expire_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function beforeSave(){
		$this->expire_date = $this->buy->date + ($this->plan->expire_time * 86400);

		return parent::beforeSave();
	}

	public function afterSave(){
		if(intval($this->plan_id) === 2) {
			$credit = new CreditsTransactions;
			$user = $this->buy->user;
			$credit->attributes = array(
				'buy_id' => $this->buy_id,
				'user_id' => $user->id,
				'descriptions' => 'به علت غیر فعال شدن پنل',
				'price' => 0,
				'user_price' => 0,
				'effective' => 0,
			);
			if($credit->save()){
				$user->credit_charge = 0;
				$user->scenario = 'changeValue';
				$user->save();
			}
		}
		return parent::afterSave();
	}

    public function getUrl(){
        return 'plans/select/buy/'.$this->plan_id.'?buyId='.$this->buy_id;
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

    public function get_date_diff($first_time, $last_time)
    {
        $diff=$last_time-$first_time;
        if($diff<0)
            return '0';
        $hour=floor($diff/3600);
        $minute=floor((($diff/3600)-floor($diff/3600))*60);
        $second=floor((($diff/60)-floor($diff/60))*60);

        if($hour == 0 && $minute == 0)
            return $second.' ثانیه';
        else if($hour == 0)
            return $minute.' دقیقه و '.$second.' ثانیه';
        else
        {
            if($hour>24)
            {
                $day=floor($hour/24);
                $hour=$hour%24;
                return $day.' روز و '.$hour.' ساعت و '.$minute.' دقیقه و '.$second.' ثانیه';
            }
            else
                return $hour.' ساعت و '.$minute.' دقیقه و '.$second.' ثانیه';
        }
    }
}
