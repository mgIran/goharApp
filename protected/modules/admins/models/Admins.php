<?php

class Admins extends iWebActiveRecord
{
	public $repeat_password;
    public $old_password;
    public $passwordSet;
	
    public function tableName()
	{
		return '{{admins}}';
	}

	public function rules()
	{
		return array(
			array('role_id,email,first_name,last_name', 'required'),
            array('user_name, password,repeat_password','required','on'=>'insert'),
            array('old_password, password, repeat_password','canBeEmpty','on'=>'changePassword'),
            array('password, repeat_password','canBeEmpty2','on'=>'update'),
            array('password, repeat_password','required','on'=>'recoverPassword'),
            array('old_password', 'findPasswords', 'on' => 'changePassword'),
			array('email', 'CEmailValidator'),
			array('role_id, mobile', 'numerical', 'integerOnly'=>true,'message'=>'{attribute} باید عددی باشد.'),
			array('user_name', 'length','min'=>5,'max'=>254),
			array('first_name,last_name', 'length', 'min'=>3,'max'=>100),
            array('email','length','min'=>5,'max'=>254),
			array('mobile', 'length', 'min'=>11,'max'=>11),
            array('status', 'default', 'value'=>1),
            array('password,passwordSet,repeat_password,old_password,avatar','safe'),

			array('id, role_id, user_name, password, status, first_name, last_name, email, mobile', 'safe', 'on'=>'search'),
            array('repeat_password', 'compare', 'compareAttribute'=>'password', 'message'=>"رمز عبور و تکرار رمز عبور یکسان نیستند"),
            //array('repeat_password', 'compare', 'compareAttribute'=>'password', 'message'=>"رمز عبور و تکرار رمز عبور یکسان نیستند" , 'allowEmpty'=>TRUE, 'on'=>'update' ),
			//array('password', 'CRegularExpressionValidator', 'pattern'=>"/^(?=.*\d(?=.*\d))(?=.*[a-zA-Z](?=.*[a-zA-Z])).{5,}$/","message"=>"رمز عبور ایمن نیست"),
			array('user_name','unique', 'className' => 'Admins',"message"=>'نام مدیری "{value}" قبلا ثبت شده است، لطفا نام کاربری دیگری را وارد نمایید','on'=>'insert'),
			array('email','unique', 'className' => 'Admins',"message"=>'این پست الکترونیک قبلا ثبت شده است!','on'=>'insert'),
			array('mobile','unique', 'className' => 'Admins',"message"=>'این شماره قبلا ثبت شده است!','on'=>'insert'),
            array('user_name, email, mobile, first_name, last_name', 'filter', 'filter'=>'trim'),
		);
	}
    
    public function findPasswords($attribute, $params)
    {
        if($this->passwordSet==="1" && $this->old_password!=='')
        {
            $user = Admins::model()->findByPk(Yii::app()->user->userID);
            //Yii::import('ext.bcrypt');
            $bcrypt = new Bcrypt();
                if (!$bcrypt->verify(md5(sha1($this->old_password)), $user->password))            
                    $this->addError($attribute, 'رمز عبور فعلی را با دقت وارد نمائید.');
        }
          
    }
    
    public function canBeEmpty($attribute, $params)
    {
        if ($this->passwordSet==="1")
        {
            switch ($attribute)
            {
                case 'old_password':
                    if(empty($this->old_password))
                        $this->addError($attribute, "رمز عبور فعلی را وارد کنید.");break;
                case 'password':
                    if(empty($this->password))
                        $this->addError($attribute, "رمز عبور جدید را وارد کنید.");break;
                case 'repeat_password':
                    if(empty($this->repeat_password))
                        $this->addError($attribute, "تکرار رمز عبور جدید را وارد کنید.");break;
            }
        }
    }
    
    public function canBeEmpty2($attribute, $params)
    {
        if ($this->passwordSet==="1")
        {
            switch ($attribute)
            {
                case 'password':
                    if(empty($this->password))
                        $this->addError($attribute, "رمز عبور جدید را وارد کنید.");break;
                case 'repeat_password':
                    if(empty($this->repeat_password))
                        $this->addError($attribute, "تکرار رمز عبور جدید را وارد کنید.");break;
            }
        }
    }
    
	public function beforeSave()
    {
       if(isset($this->password)){
            $bCrypt = new Bcrypt;
            $hash = $bCrypt->hash(md5(sha1($this->password)));
            $this->password=$hash;
       }      
 		
        return parent::beforeSave();
    }

	public function relations()
	{
		return array(
			'AdminsRoles' => array(self::BELONGS_TO, 'AdminsRoles', 'role_id'),
			'AdminsOptions' => array(self::HAS_MANY, 'AdminsOptions', 'admin_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'شناسه',
			'role_id' => 'نقش',
			'user_name' => 'نام کاربری',
			'password' => 'رمز عبور',
			'repeat_password' => 'تکرار رمز عبور',
            'old_password' => 'رمز عبور فعلی',
			'status' => 'وضعیت',
			'first_name' => 'نام',
			'last_name' => 'نام خانوادگی',
			'email' => 'پست الکترونیک',
			'mobile' => 'همراه',
			'AdminsRoles' => 'نقش',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('AdminsRoles',$this->AdminsRoles);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile',$this->mobile,true);
        $criteria->order="id DESC";
        $criteria->condition = 'deleted=0';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
