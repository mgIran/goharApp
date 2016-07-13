<?php
class ContactsCategoriesController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'گروه مخاطبین',
            'module' => 'services,contacts',
            'menu' => true,
            'menu_parent' => 'contacts',
            'menu_name' => 'contacts_categories',
            'url' => 'contacts/categories/index' ,
            'index' => array(
                'title' => 'مدیریت',
                'type' => 'user'
            ),
            'create' => array(
                'title' => 'افزودن گروه',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'contacts',
                'menu_name' => 'contacts_categories_create',
                'url' => 'contacts/categories/create' ,
            ),
            'update' => array(
                'title' => 'ویرایش',
                'type' => 'user'
            ),
            'delete' => array(
                'title' => 'حذف',
                'type' => 'user'
            ),
        );

	public function filters(){
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionCreate(){
        $model = new ContactsCategories;

        $this->performAjaxValidation($model);

        if(isset($_POST['ContactsCategories']))
        {
            $model->attributes=$_POST['ContactsCategories'];
            if($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

	public function actionUpdate($id){
		$model=$this->loadModel($id);

        $this->performAjaxValidation($model);

		if(isset($_POST['ContactsCategories']))
		{
			$model->attributes=$_POST['ContactsCategories'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
        $delete = $this->loadModel($id);
        if($delete->user_id == Yii::app()->user->userID)
            $delete->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
	/**
	 * Manages all models.
	 */
	public function actionIndex(){
        $model = new ContactsCategories('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['ContactsCategories']))
            $model->attributes=$_GET['ContactsCategories'];

        $this->render('index',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id){
		$model=ContactsCategories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='contacts-categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
