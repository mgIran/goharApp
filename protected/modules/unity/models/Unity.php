<?php

/**
 * This is the model class for table "{{unity}}".
 *
 * The followings are the available columns in table '{{unity}}':
 * @property string $id
 * @property string $subject
 * @property string $content
 * @property string $date
 * @property string $notices_date
 * @property string $poster
 * @property string $receiver_count
 * @property string $status
 */
class Unity extends CActiveRecord
{
	public $status;
	public $statusLabels=array(
		'waiting'=>'منتظر اطلاع رسانی',
		'informing'=>'در حال اطلاع رسانی',
		'running'=>'در حال همصدایی',
		'end'=>'اتمام همصدایی'
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{unity}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('subject, content, poster, date, notices_date', 'required'),
			array('date, notices_date', 'length', 'max'=>20),
			array('subject', 'length', 'max'=>511),
			array('poster', 'length', 'max'=>500),
			array('receiver_count', 'length', 'max'=>10),
			array('content, status', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, notices_date, subject, content, poster, receiver_count', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'subject' => 'موضوع',
            'content' => 'محتوا',
            'date' => 'زمان همصدایی',
            'notices_date' => 'تاریخ اطلاع رسانی',
            'poster' => 'پوستر',
			'receiver_count' => 'تعداد گیرندگان',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('poster',$this->poster,true);
		$criteria->compare('receiver_count',$this->receiver_count,true);

        if ($this->status == "waiting")
            $criteria->addCondition("notices_date > :time");
        elseif ($this->status == "informing")
            $criteria->addCondition("notices_date <= :time AND date > :time");
        elseif ($this->status == "running")
            $criteria->addCondition("date = :time");
        elseif ($this->status == "end")
            $criteria->addCondition("date < :time");

        if (!is_null($this->status) and !empty($this->status))
            $criteria->params[":time"] = time();

        $criteria->order = 'id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Unity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getStatus()
    {
        if(is_null($this->notices_date) or is_null($this->date))
            return "";
        elseif(time() < $this->notices_date)
            return "waiting";
        elseif(time() >= $this->notices_date and time() < $this->date)
            return "informing";
        elseif(time() == $this->date)
            return "running";
        elseif(time() > $this->date)
            return "end";
        else
            return "";
    }
}