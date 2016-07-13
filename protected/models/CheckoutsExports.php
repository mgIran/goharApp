<?php

/**
 * This is the model class for table "{{checkouts_exports}}".
 *
 * The followings are the available columns in table '{{checkouts_exports}}':
 * @property integer $id
 * @property string $export_file
 * @property string $import_file
 * @property integer $export_date
 * @property string $price
 *
 * The followings are the available model relations:
 * @property Checkouts[] $checkouts
 */
class CheckoutsExports extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{checkouts_exports}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('export_file','filter','filter'=>'uniqid','on'=>'insert'),
			array('export_date','filter','filter'=>'time','on'=>'insert'),
			//array('export_file, export_date', 'required'),
			array('export_date', 'numerical', 'integerOnly'=>true),
			array('export_file, import_file', 'length', 'max'=>100),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, export_file, import_file, export_date, price', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'checkouts' => array(self::HAS_MANY, 'Checkouts', 'export_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'شناسه',
			'export_file' => 'فایل خروجی',
			'import_file' => 'فایل ورودی',
			'export_date' => 'تاریخ ایجاد خروجی',
			'price' => 'جمع کل مبلغ',
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
		$criteria->compare('export_file',$this->export_file,true);
		$criteria->compare('import_file',$this->import_file,true);
		$criteria->compare('export_date',$this->export_date);
		$criteria->compare('price',$this->price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CheckoutsExports the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
