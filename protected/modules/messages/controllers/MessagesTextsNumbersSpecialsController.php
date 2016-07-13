<?php
class MessagesTextsNumbersSpecialsController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'مدیریت شماره های اختصاصی',
            'menu' => TRUE,
            'menu_name' => 'texts_numbers_specials',
            'admin' => array(
                'title' => 'مدیریت',
                'type' => 'admin',
                'menu' => TRUE,
                'menu_parent' => 'texts_numbers_specials',
                'menu_name' => 'texts_numbers_specials_admin',
                'url' => 'messages/numbers_specials/admin',
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

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionCreate()
    {
        $model = new MessagesTextsNumbersSpecials;

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesTextsNumbersSpecials']))
        {
            if($_POST['MessagesTextsNumbersSpecials']['prefix_id']=='' OR $_POST['MessagesTextsNumbersSpecials']['prefix_id']==0)
                $_POST['MessagesTextsNumbersSpecials']['prefix_id'] = NULL;

            $model->attributes=$_POST['MessagesTextsNumbersSpecials'];
            if($model->save())
                $this->redirect(array('admin'));
        }

        $prefixes = MessagesTextsNumbersPrefix::model()->findAll();

        $temp = array(
            0 => 'بدون پیش شماره'
        );
        foreach($prefixes as $category){
            $temp[$category->id] = $category->number;
        }
        $prefixes = $temp;

        $this->render('create',array(
            'model'=>$model,
            'prefixes' => $prefixes
        ));
    }

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

        $this->performAjaxValidation($model);

		if(isset($_POST['MessagesTextsNumbersSpecials']))
		{
            if($_POST['MessagesTextsNumbersSpecials']['prefix_id']=='' OR $_POST['MessagesTextsNumbersSpecials']['prefix_id']==0)
                $_POST['MessagesTextsNumbersSpecials']['prefix_id'] = NULL;

			$model->attributes=$_POST['MessagesTextsNumbersSpecials'];
			if($model->save())
				$this->redirect(array('admin'));
		}

        $prefixes = MessagesTextsNumbersPrefix::model()->findAll();

        $temp = array(
            0 => 'بدون پیش شماره'
        );
        foreach($prefixes as $category){
            $temp[$category->id] = $category->number;
        }
        $prefixes = $temp;

		$this->render('update',array(
			'model'=>$model,
            'prefixes' => $prefixes
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('MessagesTextsNumbersSpecials');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $model = new MessagesTextsNumbersSpecials('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesTextsNumbersSpecials']))
            $model->attributes=$_GET['MessagesTextsNumbersSpecials'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id)
	{
		$model = MessagesTextsNumbersSpecials::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-texts-numbers-specials-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}