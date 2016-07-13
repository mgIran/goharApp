<?php
class MessagesEmailsTemplatesController extends Controller{
    public static $actionsArray =
        array(
            'title' => 'قالب های ایمیل',
            'type' => 'admin',
            'menu' => true,
            'menu_name' => 'emails_templates',
            'create' => array(
                'title' => 'ایجاد قالب',
                'type' => 'admin',
                'menu' => true,
                'menu_parent' => 'emails_templates',
                'menu_name' => 'emails_templates_create',
                'url' => 'messages/emails_templates/create'
            ),
            'update' => array(
                'title' => 'ویرایش قالب',
                'type' => 'admin',
            ),
            'delete' => array(
                'title' => 'حذف قالب',
                'type' => 'admin',
            ),
            'admin' => array(
                'title' => 'مدیریت قالب ها',
                'type' => 'admin',
                'menu' => true,
                'url' => 'messages/emails_templates/admin',
                'menu_parent' => 'emails_templates',
                'menu_name' => 'emails_templates_admin',
            ),
        );

	public $layout='//layouts/main';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

    public function actionAdmin(){
        $model = new MessagesEmailsTemplates('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesEmailsTemplates']))
            $model->attributes=$_GET['MessagesEmailsTemplates'];
        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    public function actionCreate(){
        $model = new MessagesEmailsTemplates;
        $this->performAjaxValidation($model);
        if(isset($_POST['MessagesEmailsTemplates'])){
            $model->attributes = $_POST['MessagesEmailsTemplates'];
            if($model->save()){
                Yii::app()->user->setFlash('success','قالب با موفقیت ثبت شد.');
                $this->redirect(array('admin'));
            }
            else
                Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
        }
        $this->render('create',array(
            'model' => $model
        ));
    }

    public function actionUpdate($id){
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);
        if(isset($_POST['MessagesEmailsTemplates'])){
            $model->attributes = $_POST['MessagesEmailsTemplates'];
            if($model->save()){
                Yii::app()->user->setFlash('success','قالب با موفقیت ویرایش شد.');
                $this->redirect(array('admin'));
            }
            else
                Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
        }
        $this->render('update',array(
            'model' => $model
        ));
    }

    public function actionDelete($id)
    {
        $delete = $this->loadModel($id);

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function loadModel($id){
        $model=MessagesEmailsTemplates::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='messages-emails-templates'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}