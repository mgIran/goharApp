<?php
class MessagesTextsUsersNumbersController extends Controller {
    public static $actionsArray =
        array(
            'title' => 'مدیریت خطوط',
            'menu' => TRUE,
            'menu_name' => 'users_numbers',
            'admin' => array(
                'title' => 'مدیریت',
                'type' => 'admin',
                'menu' => TRUE,
                'menu_parent' => 'users_numbers',
                'menu_name' => 'users_numbers_admin',
                'url' => 'messages/numbers/admin',
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin',
                'menu' => TRUE,
                'menu_parent' => 'users_numbers',
                'menu_name' => 'users_numbers_create',
                'url' => 'messages/numbers/create',
                'otherActions' => 'users',
            ),
            'update' => array(
                'title' => 'ویرایش',
                'type' => 'admin',
                'otherActions' => 'users',
            ),
            'delete' => array(
                'title' => 'حذف',
                'type' => 'admin',
            ),
        );

	public $layout='//layouts/main';

	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public function actionCreate()
    {
        $model=new MessagesTextsUsersNumbers;

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesTextsUsersNumbers']))
        {
            //var_dump($_POST['MessagesTextsUsersNumbers']);exit;
            $model->attributes=$_POST['MessagesTextsUsersNumbers'];
            if($model->save())
            {
                Yii::app()->user->setFlash('success','شماره خط با موفقیت ثبت شد.');
                $this->redirect(array('admin'));
            }
            else{
                Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
            }
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesTextsUsersNumbers']))
        {
            $model->attributes=$_POST['MessagesTextsUsersNumbers'];
            if($model->save())
            {
                Yii::app()->user->setFlash('success','شماره خط با موفقیت ویرایش شد.');
                $this->redirect(array('index'));
            }
            else
                Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');

        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    public function actionUsers($term){
        $model = Users::model()->findAll("(email REGEXP :email OR id = :email) AND status = 1 AND deleted = 0",array(
            ':email' => is_numeric($term)?$term:iWebHelper::searchArabicAndPersian($term),
        ));

        if(!empty($model))
        {
            $temp = array();
            foreach($model as $item){
                $temp[] = array(
                    'id' => $item->id,
                    'value' => $item->id . ' - ' . $item->email,
                );
            }
            echo json_encode($temp);
        }
    }

    public function actionAdmin(){

        $model = new MessagesTextsUsersNumbers('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesTextsUsersNumbers']))
            $model->attributes=$_GET['MessagesTextsUsersNumbers'];

        $this->render('admin',array(
            'model'=>$model,
        ));

    }

    public function loadModel($id){
        $model=MessagesTextsUsersNumbers::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {

        if(isset($_POST['ajax']) && $_POST['ajax']==='messages-texts-users-numbers-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }





}
