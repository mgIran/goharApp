<?php
class MessagesTextsDraftsController extends Controller{
    public static $actionsArray =
        array(
            'title' => 'پیش نویس ها (پیامک)',
            'type' => 'user',
            'module' => 'messages,contacts',
            'create' => array(
                'title' => 'ایجاد پیش نویس',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'texts_send',
                'menu_name' => 'texts_drafts_create',
                'url' => 'messages/texts_drafts/create'
            ),
            'update' => array(
                'title' => 'ویرایش پیش نویس',
                'type' => 'user',
            ),
            'delete' => array(
                'title' => 'حذف پیش نویس',
                'type' => 'user',
            ),
            'index' => array(
                'title' => 'پیش نویس ها',
                'type' => 'user',
                'menu' => true,
                'url' => 'messages/texts_drafts/index',
                'menu_parent' => 'texts_send',
                'menu_name' => 'texts_drafts_index',
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

    public function actionIndex(){
        $model = new MessagesTextsDrafts('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesTextsDrafts']))
            $model->attributes=$_GET['MessagesTextsDrafts'];
        $this->render('index',array(
            'model'=>$model,
        ));
    }

    public function actionCreate(){
        $model = new MessagesTextsDrafts;
        $this->performAjaxValidation($model);
        if(isset($_POST['MessagesTextsDrafts'])){
            $model->attributes = $_POST['MessagesTextsDrafts'];
            if($model->save()){
                Yii::app()->user->setFlash('success','پیش نویس با موفقیت ثبت شد.');
                $this->redirect(array('index'));
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
        if(isset($_POST['MessagesTextsDrafts'])){
            $model->attributes = $_POST['MessagesTextsDrafts'];
            if($model->save()){
                Yii::app()->user->setFlash('success','پیش نویس با موفقیت ویرایش شد.');
                $this->redirect(array('index'));
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
        if($delete->user_id == Yii::app()->user->userID)
            $delete->delete();

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function loadModel($id){
        $model=MessagesTextsDrafts::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='messages-texts-drafts'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}