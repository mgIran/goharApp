<?php

/**
 * This is the model class for table "iw_log".
 *
 * The followings are the available columns in table 'iw_log':
 * @property string $id
 * @property string $time
 * @property integer $user_id
 * @property string $ip
 * @property integer $module_id
 * @property string $action
 * @property string $info
 *
 * The followings are the available model relations:
 * @property IwModules $module
 * @property IwUsers $user
 */
class Log extends iWebActiveRecord
{
    public $module_name;

    public static $types = array(
        'admin' => 'مدیر',
        'user' => 'کاربر سایت'
    );
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('module', 'required'),
            array('user_id', 'numerical', 'integerOnly'=>true,'allowEmpty'=>TRUE),
            array('pk', 'numerical', 'integerOnly'=>true),
            array('time','default',
                'value'=>time(),
                'setOnEmpty'=>false,'on'=>'insert'),
            array('ip','default',
                'value'=>Yii::app()->request->getUserHostAddress(),
                'setOnEmpty'=>false,'on'=>'insert'),
            array('action', 'length', 'max'=>40),
            array('info', 'length', 'max'=>255),
            array('module', 'length', 'max'=>30),
            array('type','safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, time, user_id, ip, module, action, info','filter','filter'=>'trim'),
            array('id, time, user_id, ip, action, info,module_name,user', 'safe', 'on'=>'search'),

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
            'admin' => array(self::BELONGS_TO, 'Admins', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'شناسه',
            'time' => 'زمان',
            'user_id' => 'توسط',
            'ip' => 'IP',
            'module' => 'نام ماژول',
            'action' => 'عملیات',
            'info' => 'اطلاعات',
            'type' => 'نوع کاربر',
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
        $criteria->alias = 'log';
        //$criteria->select="log.*,CONCAT_WS(' / ',modules_parent.title,modules.title) AS module_name";
        $criteria->compare('id',$this->id,true);
        $criteria->compare('time',$this->time,true);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('ip',$this->ip,true);
        $criteria->compare('action',$this->action,true);
        $criteria->compare('info',$this->info,true);
        $criteria->compare('pk',$this->pk,true);
        $criteria->addCondition('SUBSTR(`module` FROM LENGTH(`module`)-2 FOR 3) != \'Rel\'');
        //$criteria->join= 'JOIN iw_modules modules ON (log.module = modules.name)';
        //$criteria->join.= 'LEFT JOIN iw_modules modules_parent ON (modules.parent_id = modules_parent.id)';
        $criteria->order="log.id DESC";
        $criteria->group="log.id";
        return new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=> Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public function getUser(){
        if(!is_null($this->type)){
            if($this->type == 'user')
            {
                Yii::import("application.modules.users.models.*");
                return $this->user->first_name." ".$this->user->last_name;
            }
            elseif($this->type == 'admin'){
                Yii::import("application.modules.admins.models.*");
                return $this->admin->first_name." ".$this->admin->last_name;
            }
        }
        return 'ناشناس';

    }

    public function getModule(){
        $className = $this->module;
        if(@class_exists($className) AND isset($className::$moduleName))
            return $className::$moduleName;
        return 'نامشخص';

    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your iWebActiveRecord descendants!
     * @param string $className active record class name.
     * @return Log the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
