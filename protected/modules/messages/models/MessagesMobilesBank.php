<?php
class MessagesMobilesBank extends iWebActiveRecord
{

	public function tableName()
	{
		return "{{messages_mobiles_bank}}";
	}


	public function rules()
	{
		return array(
			array('mobile,cat_id', 'required'),
			array('mobile', 'length', 'max'=>255),
            array('mobile','unique', 'className' => 'MessagesMobilesBank','message'=>'شماره موبایل شما قبلا ثبت شده است.'),
            array(
                'mobile',
                'match', 'pattern' => '/^(0|\+98){0,1}9{1}\d{9}$/',
                'message'=>'شماره موبایل باید صحیح و بصورت شماره موبایل واقعی ثبت شود.'
            ),
            array('cat_id,mobile', 'numerical', 'integerOnly'=>true),
			array('id, mobile', 'safe', 'on'=>'search'),
		);
	}


	public function relations()
	{
		return array(
            'category' => array(self::BELONGS_TO, 'MessagesMobilesBankCategories', 'cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'mobile' => 'شماره موبایل',
            'categories' => 'بخش',
            'cat_id' => 'بخش',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('mobile',$this->mobile,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function multipleRowInsert($array=array())
    {
        if($array==array())
            return false;
        $builder = Yii::app()->db->schema->commandBuilder;

        $command = $builder->createMultipleInsertCommand($this->tableName(), $array);
        if($command->execute())
            return true;

    }
}
