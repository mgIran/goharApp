<?php
class MessagesTextsInboxController extends Controller{
    public static $actionsArray =
        array(
            'title' => 'صندوق پیام های دریافت شده',
            'type' => 'user',
            'module' => 'messages,contacts',
            'index' => array(
                'title' => 'پیامک های دریافت شده',
                'type' => 'user',
                'menu' => true,
                'url' => 'messages/texts_inbox',
                'menu_parent' => 'texts_send',
                'menu_name' => 'texts_inbox',
            ),
            'delete' => array(
                'title' => 'حذف',
                'type' => 'user'
            ),
            'view' => array(
                'title' => 'نمایش',
                'type' => 'user'
            )
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
        $model = new MessagesTextsInbox('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesTextsInbox']))
            $model->attributes=$_GET['MessagesTextsInbox'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id){
        $delete = $this->loadModel($id);
        if($delete->user_id == Yii::app()->user->userID)
            $delete->delete();

        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionView($id){
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function loadModel($id){
        $model=MessagesTextsInbox::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}