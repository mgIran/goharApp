<?php

class ContactsController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'دفترچه مخاطبین',
            'module' => 'services,contacts',
            'menu' => true,
            'menu_name' => 'contacts',
            'type' => 'user',
            'index' => array(
                'title' => 'لیست مخاطبین',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'contacts',
                'menu_name' => 'contacts_index',
                'url' => 'contacts/manage/index' ,
                'otherActions' => 'upload',
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'user',
                'url' => 'contacts/manage/create' ,
                'menu' => true,
                'menu_parent'=>'contacts',
                'menu_name' => 'contacts_create',
                'url' => 'contacts/manage/create' ,
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
        $model=new Contacts;

        $this->performAjaxValidation($model);

        if(isset($_POST['Contacts']))
        {
            $model->attributes=$_POST['Contacts'];
            if($model->save())
            {
                Yii::app()->user->setFlash('success','مخاطب با موفقیت ثبت شد.');
                $this->redirect(array('index'));
            }
            else{
                Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
            }
        }

        $categories = ContactsCategories::model()->findAllByAttributes(array("user_id"=>Yii::app()->user->userID));
        $categories = CHtml::listData($categories,'id','title');

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

		if(isset($_POST['Contacts']))
		{
			$model->attributes=$_POST['Contacts'];
			if($model->save())
            {
                Yii::app()->user->setFlash('success','مخاطب با موفقیت ویرایش شد.');
				$this->redirect(array('index'));
            }
            else
                Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');

		}

        $categories = ContactsCategories::model()->findAllByAttributes(array("user_id"=>Yii::app()->user->userID));
        $categories = CHtml::listData($categories,'id','title');

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
        $delete = $this->loadModel($id);
        if($delete->category->user_id == Yii::app()->user->userID)
            $delete->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}


	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
        $model = new Contacts('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Contacts']))
            $model->attributes=$_GET['Contacts'];

        $this->render('index',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id)
	{
		$model=Contacts::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{

		if(isset($_POST['ajax']) && $_POST['ajax']==='contacts-form')
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
                        $temp = Contacts::model()->findByAttributes(array('mobile'=>$line));
                        if(count($temp)){
                            $count++;
                        }
                        if (preg_match('/^(0|\+98){0,1}9{1}\d{9}$/',$line) AND !count($temp))
                            $array[] = array(
                                'mobile'=> $line,
                                'cat_id' => $catId
                            );
                    }
                }
                fclose($handle);
                if($array != array() AND Contacts::model()->multipleRowInsert($array))
                {
                    echo json_encode(
                        array(
                            'result'=>true,
                            'fileName'=>$file['name'],
                            'ID'=>'0' // ID of image record in database
                        )
                    );
                    Yii::app()->end();
                }
            }
        }

        if($count){
            $countMessage = "\r\n$count شماره تکراری وجود دارد";
        }

        echo json_encode(array(
            'result'=>false,
            'message'=>$countMessage
        ));
        Yii::app()->end();
    }

}
