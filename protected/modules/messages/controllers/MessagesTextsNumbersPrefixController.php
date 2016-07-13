<?php
class MessagesTextsNumbersPrefixController extends Controller {
    public static $actionsArray =
        array(
            'title' => 'مدیریت پیش شماره ها',
            'menu' => TRUE,
            'menu_parent' => 'texts_numbers_specials',
            'menu_name' => 'texts_numbers_prefix_admin',
            'url' => 'messages/numbers_prefix/admin',
            'admin' => array(
                'title' => 'مدیریت',
                'type' => 'admin'
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin'
            ),
            'update' => array(
                'title' => 'ویرایش',
                'type' => 'admin'
            ),
            'delete' => array(
                'title' => 'حذف',
                'type' => 'admin'
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
        $model = new MessagesTextsNumbersPrefix;

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesTextsNumbersPrefix']))
        {
            $model->attributes = $_POST['MessagesTextsNumbersPrefix'];

            if($model->save())
                $this->redirect(array('admin'));
        }


        $this->render('create',array(
            'model'=>$model,
        ));
    }

	public function actionUpdate($id){
		$model=$this->loadModel($id);

        $this->performAjaxValidation($model);

		if(isset($_POST['MessagesTextsNumbersPrefix']))
		{
			$model->attributes = $_POST['MessagesTextsNumbersPrefix'];
			if($model->save())
				$this->redirect(array('admin'));
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('MessagesTextsNumbersPrefix');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
        $model = new MessagesTextsNumbersPrefix('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesTextsNumbersPrefix']))
            $model->attributes = $_GET['MessagesTextsNumbersPrefix'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id){
		$model = MessagesTextsNumbersPrefix::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-texts-numbers-prefix-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}