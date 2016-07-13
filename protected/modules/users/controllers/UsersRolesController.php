<?php

class UsersRolesController extends Controller
{
    /*public static $actionsArray =
        array(
            'title' => 'نقش کاربران',
            'menu' => true,
            'url' => 'users/roles/admin' ,
            'menu_parent'=>'manage_users',
            'menu_name' => 'users_roles_admin',
            'admin' => array(
                'title' => 'نمایش',
                'type' => 'admin',
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin',
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
        );*/

	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,deleteSelected', // we only allow deletion via POST request
		);
	}

	public function actionCreate(){
		$model=new UsersRoles;
		$this->performAjaxValidation($model);

		if(isset($_POST['UsersRoles']))
		{
			$model->attributes=$_POST['UsersRoles'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success','نقش جدید اضافه گردید!');
                $this->redirect(array('admin'));
			}
			else
				Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id){
		$model=$this->loadModel($id);
		$this->performAjaxValidation($model);
		
		if(isset($_POST['UsersRoles']))
		{
			$model->attributes=$_POST['UsersRoles'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success','مجوز ها اعمال شد !');
                $this->redirect(array('admin'));
			}
			else
				Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
				
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
    
    public function actionDelete($id,$showMessage=true){
        if($id!=1 AND $id!=2)
		    $this->loadModel($id)->delete();
        //if($showMessage)
        //    Yii::app()->user->setFlash('success', 'دیدگاه ها حذف شدند.');
	}
    
     public function actionDeleteSelected(){
        foreach ($_POST['selectedItems'] as $modelId)
            $this->actionDelete($modelId,FALSE);
        //Yii::app()->user->setFlash('success', 'دیدگاه ها حذف شدند.');
    }

	public function actionAdmin(){

        $model = new UsersRoles('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['UsersRoles']))
            $model->attributes=$_GET['UsersRoles'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id){
		$model=UsersRoles::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-roles-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}