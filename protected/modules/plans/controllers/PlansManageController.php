<?php
class PlansManageController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'مدیریت پلن ها',
            'menu' => true,
            'menu_name' => 'manage_plans',
            'admin' => array(
                'title' => 'مدیریت',
                'type' => 'admin',
                'menu' => true,
                'url' => 'plans/manage/admin' ,
                'menu_parent'=>'manage_plans',
                'menu_name' => 'manage_plans_admin',
                'otherActions' => 'export'
            ),
            'create' => array(
                'title' => 'افزودن پلن جدید',
                'type' => 'admin',
                'url' => 'plans/manage/create' ,
                'menu' => true,
                'menu_parent'=>'manage_plans',
                'menu_name' => 'manage_plans_create',
            ),
            'update' => array(
                'title' => 'ویرایش پلن',
                'type' => 'admin'
            ),
            'settings' => array(
                'title' => 'تنظیمات',
                'type' => 'admin',
                'url' => 'plans/manage/settings' ,
                'menu' => true,
                'menu_parent'=>'manage_plans',
                'menu_name' => 'manage_plans_settings',
            ),
        );

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function actionCreate()
	{
		$model = new Plans;
        if(@!class_exists('UsersRoles'))
            Yii::import("application.modules.users.models.*");
        $rolesModel = new UsersRoles;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Plans'])){
            $_POST['Plans']['required_fields'] = $_POST['Users'];
			$model->attributes = $_POST['Plans'];
            $rolesModel->attributes = $_POST['UsersRoles'];
            $rolesModel->title = $model->name;
            if($rolesModel->save())
            {
                $model->role_id = $rolesModel->id;
                if($model->save())
                    $this->redirect(array('admin'));
            }
		}
		$this->render('create',array(
			'model' => $model,
            'rolesModel' => $rolesModel
		));
	}


	public function actionUpdate($id){
		$model=$this->loadModel($id);
        $agency = array_values(json_decode($model->agency,TRUE));

        $rolesModel = $model->role;


		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

        if(isset($_POST['Plans'])){

            $_POST['Plans']['required_fields'] = $_POST['Users'];
            $model->attributes = $_POST['Plans'];
            $rolesModel->attributes = $_POST['UsersRoles'];
            $rolesModel->title = $model->name;
            if($rolesModel->save())
            {
                $model->role_id = $rolesModel->id;
                if($model->save())
                    $this->redirect(array('admin'));
            }
        }

		$this->render('update',array(
			'model'=>$model,
            'rolesModel' => $rolesModel
		));
	}


	public function actionDelete($id){
        if($id >= 1 AND $id <= 4)
            throw new CHttpException(404,'این پلن قابل حذف نمی باشد.');

        $model = $this->loadModel($id);
        if(count($model->role->Users) !== 0)
            throw new CHttpException(404,'این پلن قابل حذف نمی باشد.');

        $model->deleted = 1;
        $model->save();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionAdmin(){
        $staticModel = new Plans('search');

        $model = new Plans('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Plans']))
            $model->attributes=$_GET['Plans'];

        $this->render('admin',array(
            'model'=>$model,
            'staticModel'=>$staticModel,
        ));
	}

    public function actionSettings(){
        $criteria = new CDbCriteria;
        $criteria->addInCondition('name',array('plan_investment','tax','plan_max_discount','plan_select','disable_login'));
        $model = SiteOptions::model()->findAll($criteria);

        $pagesModel = Pages::model()->find("name = 'plan_buy_about'");

        $settings = array();
        foreach($model as $item){
            $settings[$item->name] = $item;
        }

        if(isset($_POST['Pages'])){
            $pagesModel->attributes = $_POST['Pages'];
            $post = $_POST['Pages'];
            if($pagesModel->save()){
                $post['plan_investment'] = str_replace(',','',$post['plan_investment']).$post['plan_investment_radio'];
                foreach($settings as $key=>$setting){
                    if(is_array($post[$key]))
                        $post[$key] = json_encode($post[$key]);
                    $setting['value'] = $post[$key];
                    $setting->save();
                }
                $this->refresh();
            }
        }

        $this->render('settings',array(
            'settings' => $settings,
            'pagesModel' => $pagesModel
        ));
    }

	public function loadModel($id)
	{
        if(in_array($id,array('1','2','4')))
            throw new CHttpException(404,'The requested page does not exist.');
		$model=Plans::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='plans-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionExport($type,$id){
        if($type != 'mobile' AND $type != 'email')
            throw new CHttpException(404,'The requested page does not exist.');

        $model = new Users('search');
        $model->unsetAttributes();
        $model->role_id = $id;

        $widget = $this->createWidget('ext.EExcelView.EExcelView', array(
            'dataProvider'=>$model->search(),
            'title'=>'کاربران',
            'autoWidth' => false,
            'grid_mode'=>'export',
            'filename'=> Yii::getPathOfAlias('webroot').'/protected/'.$type.'s.xlsx',
            'exportType'=>'Excel2007',
            'disablePaging'=>true,
            'stream'=>false,
            'columns'=> array(
                array(
                    'header' => $type,
                    'value' => '$data->'.$type
                )
            ),
        ));
        $widget->run();

        if (file_exists('protected/'.$type.'s.xlsx'))
            Yii::app()->getRequest()->sendFile(time(). '.xlsx', file_get_contents('protected/'.$type.'s.xlsx'));
        else
            Yii::app()->user->setFlash('danger', 'متاسفانه خطائی رخ داد، لطفا دوباره امتحان کنید.');

    }

}
