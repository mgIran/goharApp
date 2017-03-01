<?php

/**
 * This is the model class for table "{{notifications}}".
 *
 * The followings are the available columns in table '{{notifications}}':
 * @property string $id
 * @property string $subject
 * @property string $send_date
 * @property string $expire_date
 * @property string $content
 * @property string $status
 * @property string $poster
 * @property string $visit
 */
class Notifications extends CActiveRecord
{
    public $status;
	public $statusLabels=array(
		'waiting'=>'در صف انتظار',
		'sending'=>'در حال ارسال',
		'end'=>'ارسال شده'
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{notifications}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject, send_date, expire_date, content', 'required'),
			array('subject', 'length', 'max'=>511),
			array('send_date, expire_date', 'length', 'max'=>20),
			array('poster', 'length', 'max'=>500),
			array('visit', 'length', 'max'=>10),
			array('status', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, subject, send_date, expire_date, content, poster, visit', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'subject' => 'موضوع',
			'send_date' => 'تاریخ شروع ارسال',
			'expire_date' => 'تاریخ انقضاء',
			'content' => 'محتوا',
			'poster' => 'پوستر',
			'visit' => 'Visit',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('send_date', $this->send_date, true);
        $criteria->compare('expire_date', $this->expire_date, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('poster', $this->poster, true);
        $criteria->compare('visit', $this->visit, true);

        if ($this->status == "waiting")
            $criteria->addCondition("send_date > :time");
        elseif ($this->status == "sending")
            $criteria->addCondition("send_date <= :time AND expire_date > :time");
        elseif ($this->status == "end")
            $criteria->addCondition("expire_date < :time");

        if (!is_null($this->status) and !empty($this->status))
            $criteria->params[":time"] = time();

        $criteria->order = 'id DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notifications the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getStatus()
	{
		if(is_null($this->send_date) or is_null($this->expire_date))
			return "";
		elseif(time() < $this->send_date)
			return "waiting";
		elseif(time() >= $this->send_date and time() < $this->expire_date)
			return "sending";
		elseif(time() >= $this->expire_date)
			return "end";
        else
            return "";
	}
}
