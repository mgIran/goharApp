<?php
class AdminsManageController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'مدیریت مدیران',
            'menu' => true,
            'menu_name' => 'manage_admins',
            'type' => 'admin',
            'admin' => array(
                'title' => 'نمایش',
                'type' => 'admin',
                'menu' => true,
                'url' => 'admins/manage/admin' ,
                'menu_parent'=>'manage_admins',
                'menu_name' => 'manage_admins_admin',
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin',
                'menu' => true,
                'url' => 'admins/manage/create' ,
                'menu_parent'=>'manage_admins',
                'menu_name' => 'manage_admins_create',
            ),
            'update' => array(
                'title' => 'ویرایش',
                'type' => 'admin'
            ),
            'delete' => array(
                'title' => 'حذف',
                'otherActions' => array('deleteSelected'),
                'type' => 'admin'
            ),
            'setting' => array(
                'title' => 'تنظیمات شخصی',
                'type' => 'admin',
            ),
        );

	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
    
	public function actionCreate(){
        $this->beginClip('pageCaption');
        $this->widget('Caption',array(
            'icon'=>'white-user',
            'title'=>'مدیریت مدیران'
        ));
        $this->endClip();
        
        $model=new Admins;
		
		$this->performAjaxValidation($model);
        
		if(isset($_POST['Admins']))
		{
			$model->attributes=$_POST['Admins'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success','مدیر جدید اضافه گردید!');
				$this->redirect(array('admin'));
			}
			else
				Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
		}

        $roles = AdminsRoles::model()->findAll('name != \'@\'');
        $temp = array();
        foreach($roles as $role){
            $temp[$role->id] = $role->title;
        }

		$this->render('create',array(
			'model'=>$model,
            'roles'=>$temp
		));
	}

	public function actionUpdate($id){
        $this->beginClip('pageCaption');
        $this->widget('Caption',array(
            'icon'=>'white-user',
            'title'=>'مدیریت مدیران'
        ));
        $this->endClip();
        
		$model = $this->loadModel($id);
        
        $this->performAjaxValidation($model);

		if(isset($_POST['Admins']))
		{

			$model->attributes = $_POST['Admins'];
            if($_POST['Admins']['passwordSet']==='0')
                unset($model->password);
			if($model->save()){
				Yii::app()->user->setFlash('success','"'.$model->first_name." ".$model->last_name.'" با موفقیت ویرایش شد');
				$this->redirect(array('admin'));
			}
			else
				Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
		}
        
        $roles = AdminsRoles::model()->findAll('name != \'@\'');
        $temp = array();
        foreach($roles as $role){
            $temp[$role->id] = $role->title;
        }
        $roles = $temp;

		$this->render('update',array(
			'model'=>$model,
            'roles'=>$temp
		));
	}

	public function actionDelete($id,$showMessage=true){
        if($id!=1 AND $id!=2)
        {
            $model = $this->loadModel($id);
            $model->deleted = 1;
            $model->save();
        }
    }

    public function actionDeleteSelected()
    {
        foreach ($_POST['selectedItems'] as $modelId)
            $this->actionDelete($modelId,FALSE);
    }
    
	public function actionAdmin(){

        $model = new Admins('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Admins']))
            $model->attributes=$_GET['Admins'];

        $this->render('admin',array(
            'model'=>$model,
        ));

	}
    
    public function actionSetting(){
        $this->beginClip('pageCaption');
        $this->widget('Caption',array(
            'icon'=>'white-user',
            'title'=>'تنظیمات'
        ));
        $this->endClip();
        
		$model=$this->loadModel(Yii::app()->user->userID);
        $model->scenario = 'changePassword';
        
        $this->performAjaxValidation($model);

		if(isset($_POST['Admins']))
		{    
			$model->attributes=$_POST['Admins'];
            if($_POST['Admins']['passwordSet']==='0'){
                unset($model->password);
            }            
			if($model->save()){
                Yii::app()->user->setState('avatar', $model->avatar);
				Yii::app()->user->setFlash('success',"تنظیمات اعمال شدند");
				$this->refresh();
			}
			else
				Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
		}
        
		$this->render('setting',array(
			'model'=>$model,
		));
	}
    
    public function loadModel($id){
		$model = Admins::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='admins-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}