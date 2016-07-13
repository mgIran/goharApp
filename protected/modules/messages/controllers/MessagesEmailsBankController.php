<?php

class MessagesEmailsBankController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'بانک ایمیل',
            'menu' => true,
            'menu_name' => 'emails_bank',
            'type' => 'admin',
            'admin' => array(
                'title' => 'مدیریت',
                'type' => 'admin',
                'menu' => true,
                'menu_parent' => 'emails_bank',
                'menu_name' => 'emails_bank_admin',
                'url' => 'messages/emails/admin' ,
                'otherActions' => 'upload',
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin',
                'url' => 'messages/emails/create' ,
                'menu' => true,
                'menu_parent'=>'emails_bank',
                'menu_name' => 'emails_bank_create',
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
        $model=new MessagesEmailsBank;

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesEmailsBank']))
        {
            $model->attributes=$_POST['MessagesEmailsBank'];
            if($model->save())
                $this->redirect(array('admin'));
        }

        $categories = MessagesEmailsBankCategories::model()->findAll();

        $temp = array(
            0 => 'بدون والد'
        );
        foreach($categories as $category){
            $temp[$category->id] = $category->getFullName();
        }
        $categories = $temp;

        $this->render('create',array(
            'model'=>$model,
            'categories' => $categories
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

		if(isset($_POST['MessagesEmailsBank']))
		{
			$model->attributes=$_POST['MessagesEmailsBank'];
			if($model->save())
				$this->redirect(array('admin'));
		}

        $categories = MessagesEmailsBankCategories::model()->findAll();

        $temp = array(
            0 => 'بدون والد'
        );
        foreach($categories as $category){
            $temp[$category->id] = $category->getFullName();
        }
        $categories = $temp;

		$this->render('update',array(
			'model'=>$model,
            'categories' => $categories
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
		$dataProvider=new CActiveDataProvider('MessagesEmailsBank');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $model = new MessagesEmailsBank('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesEmailsBank']))
            $model->attributes=$_GET['MessagesEmailsBank'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id)
	{
		$model=MessagesEmailsBank::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-emails-bank-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionUpload()
    {
        $countMessage = 'خطایی در آپلود فایل شما وجود دارد!';
        $count = 0;
        if(isset($_COOKIE['bankValue'])) {
            $catId = $_COOKIE['bankValue'];
        }
        if(!isset($catId) OR @($catId==0))
            $countMessage = "لطفا دسته ای را انتخاب نمایید";
        else
        {
            $array = array();
            $file = $_FILES['userFile'];

            if(array_key_exists('userFile',$_FILES) && $_FILES['userFile']['error'] == 0 ){
                // Upload image
                $handle = fopen($file['tmp_name'], "r");
                if ($handle AND isset($catId) AND $catId!=0) {
                    while (($line = fgets($handle)) !== false) {
                        $line = trim($line);
                        $temp = MessagesEmailsBank::model()->findByAttributes(array('email'=>$line));
                        if(count($temp)){
                            $count++;
                        }
                        if (filter_var($line, FILTER_VALIDATE_EMAIL) AND !count($temp))
                            $array[] = array(
                                'email'=> $line,
                                'cat_id' => $catId
                            );
                    }
                }
                fclose($handle);
                if($array != array() AND MessagesEmailsBank::model()->multipleRowInsert($array))
                {
                    echo json_encode(array(
                        'result'=>true,
                        'fileName'=>$file['name'],
                        'ID'=>'0' // ID of image record in database
                    ));
                    Yii::app()->end();
                }
            }
        }

        if($count){
            $countMessage = "\r\n$count ایمیل تکراری وجود دارد";
        }

        echo json_encode(array(
            'result'=>false,
            'message'=>$countMessage
        ));
        Yii::app()->end();
    }

}
