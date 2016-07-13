<?php
Yii::import('application.modules.messages.models.MessagesTextsUsersNumbers');
class SpecialServices extends iWebActiveRecord {
    public $fields;
    const   STATUS_DISABLE = 0,
            STATUS_ENABLE = 1,
            STATUS_DONE = 2;
    public $participants_num;

    public static $statusList = array(
        0 => 'غیر فعال',
        1 => 'فعال',
        2 => 'تمام شده'
    );

    const   TYPE_POLL = 1,
            TYPE_COMPETITION = 2,
            TYPE_SCORING = 3,
            TYPE_OVERALL = 4,
            TYPE_JOINING = 5;
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return '{{special_services}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('fields','fieldsCheck'),
			array('title, number_id,status', 'required'),
			array('type, user_id, status, number_id', 'numerical', 'integerOnly'=>true),
			array('title, auto_answer', 'length', 'max'=>255),
			array('details', 'safe'),
            array('date', 'filter', 'filter'=>'time'),
            array('user_id','filter','filter'=>'iWebHelper::getUserId'),


			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, user_id, title, auto_answer, status, date, details, number_id', 'safe', 'on'=>'search'),
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
            'number' => array(self::BELONGS_TO, 'MessagesTextsUsersNumbers', 'number_id'),
			'specialServicesAnswers' => array(self::HAS_MANY, 'SpecialServicesAnswers', 'service_id'),
            'specialServicesOverallTime' => array(self::HAS_ONE, 'SpecialServicesOverallTime', 'service_id'),
		);
	}

    public function fieldsCheck(){
        foreach($this->fields as $field){
            if(empty($field['title']) OR empty($field['value'])){
                $this->addError('fields','لطفا گزینه هارا کامل پر نمایید.');
                break;
            }
        }
        return false;
    }

    public function getParticipantsNum(){
        $num = 0;
        foreach($this->specialServicesAnswers as $answers){
            $num += count($answers->specialServicesSendedAnswers);
        }
        return $num;
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'type' => 'نوع',
			'user_id' => 'کاربر',
			'title' => 'عنوان',
			'auto_answer' => 'پاسخ اتوماتیک',
			'status' => 'وضعیت',
			'date' => 'تاریخ',
			'details' => 'جزئیات',
			'number_id' => 'شماره خط اختصاصی',
            'fields' => 'گزینه ها',
            'participants_num' => 'تعداد شرکت کنندگان',
		);
	}

    public function beforeSave(){
        if($this->number->user_id != Yii::app()->user->userID){
            $this->addError('number_id','این شماره خط متعلق به شما نمی باشد.');
            return FALSE;
        }
        if($this->number->status != MessagesTextsUsersNumbers::STATUS_ENABLE){
            $this->addError('number_id','شما قادر به استفاده از این خط نمی باشید.');
            return FALSE;
        }
        return parent::beforeSave();
    }

    public function afterSave(){
        $this->number->status = MessagesTextsUsersNumbers::STATUS_USING;
        $this->number->save();
        return parent::afterSave();
    }

    public function afterDelete(){
        $this->number->status = MessagesTextsUsersNumbers::STATUS_ENABLE;
        $this->number->save();
        return parent::afterDelete();
    }

    public function makeDetails($json = FALSE){
        $answers = $this->specialServicesAnswers;
        $tempAnswers = array();
        $sum = 0;

        foreach($answers as $answer){
            $temp = array();
            $temp['title'] = ($answer->answer_title=='failedSpecialService')?'پاسخ های ناموفق':$answer->answer_title;
            $temp['num'] = number_format(count($answer->specialServicesSendedAnswers));
            $temp['percent'] = count($answer->specialServicesSendedAnswers);
            $sum += count($answer->specialServicesSendedAnswers);
            $tempAnswers[] = $temp;
        }

        $answers = array();
        foreach($tempAnswers as $answer){
            if($sum)
                $answer['percent'] = $answer['percent'] * 100 / $sum;
            $answers[] = $answer;
        }

        $this->details = json_encode($answers);
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
		$criteria->compare('user_id',Yii::app()->user->userID);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('auto_answer',$this->auto_answer,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('date',$this->date);
		$criteria->compare('details',$this->details,true);
		$criteria->compare('type',$this->type);
        $criteria->compare('number_id',$this->number_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SpecialServices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}