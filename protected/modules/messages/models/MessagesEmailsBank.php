<?php
class MessagesEmailsBank extends iWebActiveRecord
{

	public function tableName()
	{
		return "{{messages_emails_bank}}";
	}


	public function rules()
	{
		return array(
			array('email,cat_id', 'required'),
			array('email', 'length', 'max'=>255),
            array('email','unique', 'className' => 'MessagesEmailsBank','message'=>'ایمیل شما قبلا ثبت شده است.'),
            array('email', 'email'),
            array('cat_id', 'numerical', 'integerOnly'=>true),
			array('id, email', 'safe', 'on'=>'search'),
		);
	}


	public function relations()
	{
		return array(
            'category' => array(self::BELONGS_TO, 'MessagesEmailsBankCategories', 'cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'email' => 'ایمیل',
            'categories' => 'بخش',
            'cat_id' => 'بخش',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('email',$this->email,true);

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
