<?php

/**
 * This is the model class for table "{{tickets}}".
 *
 * The followings are the available columns in table '{{tickets}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property integer $cat_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property TicketsCategories $cat
 * @property Users $user
 * @property TicketsContent[] $ticketsContents
 */
class Tickets extends iWebActiveRecord
{
	public $text,$file;

    const STATUS_NO_REPLY=0;
    const STATUS_ANSWERED=1;
    const STATUS_CLOSED=2;


    private static $_statusList = array(
        0 => 'در انتظار پاسخ',
        1 => 'پاسخ داده شده',
        2 => 'بسته شده'
    );

    public static function statusList()
    {
        return self::$_statusList;
    }

    const PRIORITY_LOW=0;
    const PRIORITY_NORMAL=1;
    const PRIORITY_HIGH=2;
    const PRIORITY_URGENT=3;

    private static $_priorityList = array(
        '0'=>'پائین',
        '1'=>'متوسط',
        '2'=>'بالا',
        '3'=>'اضطراری'
    );

    public static function priorityList()
    {
        return self::$_priorityList;
    }


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tickets}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, cat_id, status, priority', 'numerical', 'integerOnly'=>true),
            array('user_id','filter','filter'=>'iWebHelper::getUserId','except' => 'admin_insert'),
            array('cat_id, title, text, priority', 'required'),
			array('title', 'length', 'max'=>500),
            array('file','file','allowEmpty'=>true, 'types'=>'jpg, gif, jpeg, png, doc, docx, bmp, zip, rar, pdf'),
			array('id, user_id, title, cat_id, status', 'safe', 'on'=>'search'),
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
			'cat' => array(self::BELONGS_TO, 'TicketsCategories', 'cat_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'ticketsContents' => array(self::HAS_MANY, 'TicketsContent', 'ticket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'user_id' => 'درخواست کننده',
			'title' => 'عنوان',
			'cat_id' => 'بخش',
			'status' => 'وضعیت',
            'priority'=>'اولویت',
            'text' => 'متن پیام',
            'file' => 'فایل ضمیمه'
		);
	}

	public function search(){
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('cat_id',$this->cat_id);
		$criteria->compare('status',$this->status);
        $criteria->compare('priority',$this->priority);
        $criteria->addCondition('cat_id = 8');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

    public static function categories(){
        $models = TicketsCategories::model()->findAll();
        return CHtml::listData($models, 'id', 'title');
    }

    public function beforeSave(){
        $uploadedFile = CUploadedFile::getInstance($this, 'file');
        if ($uploadedFile) {
            $newName= md5(microtime(true)) . '_' . $uploadedFile->name;
            $this->file = $newName;
            if(!is_dir(Yii::getPathOfAlias('webroot').'/upload/files/'))
                mkdir(Yii::getPathOfAlias('webroot').'/upload/files/');
            $uploadedFile->saveAs('upload/files/'. $newName);
            return true;
        }
        return true;
    }

    public function beforeDelete(){
        $contentRecords = TicketsContent::model()->findAll('ticket_id='. $this->id);
        foreach ($contentRecords as $content)
        {
            $file=Yii::getPathOfAlias('webroot').'/upload/files/'.$content->file;
            if (!is_null($content->file) AND !empty($content->file)) {
                if(file_exists($file))
                    unlink($file);
            }
        }
        return true;
    }

    public static function showStatus($id){
        $output='';
        switch($id)
        {
            case 0:
                $output='در انتظار پاسخ';break;
            case 1:
                $output='پاسخ داده شده';break;
            case 2:
                $output='بسته شده';break;
            default:
                $output= 'نامشخص';break;
        }
        return $output;
    }

    public static function showStatusLabel($id){
        $output='';
        switch($id)
        {
            case 0:
                $output='<label class="lbl lbl-orange">در انتظار پاسخ</label>';break;
            case 1:
                $output='<label class="lbl lbl-green">پاسخ داده شده</label>';break;
            case 2:
                $output='<label class="lbl lbl-gray">بسته شده</label>';break;
            default:
                $output='<label class="">نامشخص</label>';break;
        }
        return $output;
    }

    public static function showPriority($id){
        $output='';
        switch($id)
        {
            case 0:
                $output='پائین';break;
            case 1:
                $output='متوسط';break;
            case 2:
                $output='بالا';break;
            case 3:
                $output='اضطراری';break;
            default:
                $output= 'نامشخص';break;
        }
        return $output;
    }

    public static function lastTalkTime($id){
        $lastTalk = TicketsContent::model()->find(array(
            'condition'=>'ticket_id =' .$id,
            'order'=>'id DESC',
            'limit'=>1,
        ));
        return $lastTalk->date;
    }


}
