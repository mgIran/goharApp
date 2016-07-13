<?php

class MessagesMobilesBankController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'بانک شماره موبایل',
            'menu' => true,
            'menu_name' => 'mobiles_bank',
            'type' => 'admin',
            'admin' => array(
                'title' => 'مدیریت',
                'type' => 'admin',
                'menu' => true,
                'menu_parent' => 'mobiles_bank',
                'menu_name' => 'mobiles_bank_admin',
                'url' => 'messages/mobiles/admin' ,
                'otherActions' => 'upload',
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin',
                'url' => 'messages/mobiles/create' ,
                'menu' => true,
                'menu_parent'=>'mobiles_bank',
                'menu_name' => 'mobiles_bank_create',
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
        $model=new MessagesMobilesBank;

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesMobilesBank']))
        {
            $model->attributes=$_POST['MessagesMobilesBank'];
            if($model->save())
                $this->redirect(array('admin'));
        }

        $categories = MessagesMobilesBankCategories::model()->findAll();

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

		if(isset($_POST['MessagesMobilesBank']))
		{
			$model->attributes=$_POST['MessagesMobilesBank'];
			if($model->save())
				$this->redirect(array('admin'));
		}

        $categories = MessagesMobilesBankCategories::model()->findAll();

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
		$dataProvider=new CActiveDataProvider('MessagesMobilesBank');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $model = new MessagesMobilesBank('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesMobilesBank']))
            $model->attributes=$_GET['MessagesMobilesBank'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id)
	{
		$model=MessagesMobilesBank::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-mobiles-bank-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionUpload(){
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
                        $temp = MessagesMobilesBank::model()->findByAttributes(array('mobile'=>$line));
                        if(count($temp)){
                            $count++;
                        }
                        if (preg_match('/^(0|\+98){0,1}9{1}\d{9}$/',$line) AND !count($temp))
                            $array[] = array(
                                'email'=> $line,
                                'mobile' => $catId
                            );
                    }
                }
                fclose($handle);
                if($array != array() AND MessagesMobilesBank::model()->multipleRowInsert($array))
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
            $countMessage = "\r\n$count شماره موبایل تکراری وجود دارد";
        }

        echo json_encode(array(
            'result'=>false,
            'message'=>$countMessage
        ));
        Yii::app()->end();
    }

}
