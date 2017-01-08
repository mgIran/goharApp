<?php

/**
 * This is the model class for table "{{tickets_content}}".
 *
 * The followings are the available columns in table '{{tickets_content}}':
 * @property integer $id
 * @property integer $ticket_id
 * @property string $text
 * @property string $file
 * @property integer $admin_id
 * @property string $date
 *
 * The followings are the available model relations:
 * @property Tickets $ticket
 * @property Admins $admin
 */
class TicketsContent extends iWebActiveRecord
{
	public $tableLabel;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tickets_content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ticket_id, admin_id', 'numerical', 'integerOnly'=>true),
            array('date','default','value'=>time()),
            array('text','required'),
            array('file','file','allowEmpty'=>true, 'types'=>'jpg, gif, jpeg, png, doc, docx, bmp, zip, rar, pdf'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ticket_id, text, file, admin_id, date', 'safe', 'on'=>'search'),
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
			'ticket' => array(self::BELONGS_TO, 'Tickets', 'ticket_id'),
			'admin' => array(self::BELONGS_TO, 'Admins', 'admin_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'ticket_id' => 'تیکت',
			'text' => 'متن',
			'file' => 'فایل',
			'admin_id' => 'از طرف',
			'date' => 'تاریخ',
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
		$criteria->compare('id',$this->id);
		$criteria->compare('ticket_id',$this->ticket_id);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('file',$this->file,true);
		$criteria->compare('admin_id',$this->admin_id);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TicketsContent the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

    public function beforeSave()
    {
        if(Yii::app()->user->type == 'admin')
            $this->admin_id = Yii::app()->user->userID;

        $uploadedFile = CUploadedFile::getInstance($this, 'file');
        if ($uploadedFile) {
            $newName= md5(microtime(true)) . '_' . $uploadedFile->name;
            $this->file = $newName;
            if(!is_dir(Yii::getPathOfAlias('webroot').'/uploads/files/'))
                mkdir(Yii::getPathOfAlias('webroot').'/uploads/files/');
            $uploadedFile->saveAs('uploads/files/'. $newName);
        }
        return parent::beforeSave();
    }

}
