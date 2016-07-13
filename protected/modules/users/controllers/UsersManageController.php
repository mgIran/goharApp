<?php
class UsersManageController extends Controller {
    public static $actionsArray =
        array(
            'title' => 'مدیریت کاربران',
            'menu' => true,
            'menu_name' => 'manage_users',
            'type' => 'admin',
            'admin' => array(
                'title' => 'نمایش',
                'type' => 'admin',
                'menu' => true,
                'url' => 'users/manage/admin',
                'menu_parent'=>'manage_users',
                'menu_name' => 'manage_users_admin',
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin',
                'menu' => true,
                'url' => 'users/manage/create' ,
                'menu_parent'=>'manage_users',
                'menu_name' => 'manage_users_create',
            ),
            'update' => array(
                'title' => 'ویرایش',
                'type' => 'admin',
                'otherActions' => 'updateValue'
            ),
            'delete' => array(
                'title' => 'حذف',
                'otherActions' => array('deleteSelected'),
                'type' => 'admin'
            )
        );

	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
    
	public function actionCreate(){

        $this->beginClip('pageCaption');
        $this->widget('Caption',array(
            'icon'=>'white-user',
            'title'=>'مدیریت کاربران'
        ));
        $this->endClip();
        
        $model=new Users;
        $model->scenario='adminCreate';
		
		$this->performAjaxValidation($model);
        
		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
            $model->user_name = $model->email;
            $model->role_id = $model->getDefaultRoleId();

            if($model->save()) {
                $buy = new Buys;
                $buy->scenario = 'user_register';
                $buy->user_id = $model->id;
                $buy->status = Buys::STATUS_DONE;
                $buy->type = Buys::TYPE_PLAN;
                if ($buy->save()) {
                    $registerFlag = 1;
                    Yii::import('application.modules.plans.models.*');
                    $selectFreePlan = new PlansBuys;
                    $selectFreePlan->attributes = array(
                        'buy_id' => $buy->id,
                        'plan_id' => 3,
                        'active' => 1,
                    );
                    if ($selectFreePlan->save()){
                        $registerFlag = 2;
                    }

                    if ($registerFlag == 2) {

                        Yii::app()->user->setFlash('success','کاربر جدید اضافه گردید!');
                        $this->redirect(array('update','id'=>$model->id));


                    } else {
                        $model = Users::model()->findByPk($model->id);
                        $model->delete();
                        Yii::app()->user->setFlash('danger', 'خطا در هنگام ثبت!');
                    }
                }

            }
		}
        
        $roles = UsersRoles::model()->findAll('name != \'@\' AND name != \'*\'');
        $temp = array();
        foreach($roles as $role){
            $temp[$role->id] = $role->title;
        }
        $roles = $temp;
        
		$this->render('create',array(
			'model'=>$model,
            'roles'=>$roles
		));
	}

	public function actionUpdate($id){
        $this->beginClip('pageCaption');
        $this->widget('Caption',array(
            'icon'=>'white-user',
            'title'=>'مدیریت کاربران'
        ));
        $this->endClip();

		$model = $this->loadModel($id);

        $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
            $model->attributes=$_POST['Users'];
            if($_POST['Users']['passwordSet']==='0')
                unset($model->password);
			if($model->save())
			{
                $model->userInfoClear();
				Yii::app()->user->setFlash('success','"'.$model->first_name." ".$model->last_name.'" با موفقیت ویرایش شد');
				$this->redirect(array('admin'));
			}
			else
				Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
		}

        $roles = UsersRoles::model()->findAll('name != \'@\' AND name != \'*\'');
        $temp = array();
        foreach($roles as $role){
            $temp[$role->id] = $role->title;
        }
        $roles = $temp;

		$this->render('update',array(
			'model'=>$model,
            'roles'=>$roles
		));
	}

    public function actionUpdateValue($id,$type = 'sms'){

        $model = $this->loadModel($id);
        $fields = array();

        $this->performAjaxValidation($model);

        switch($type){
            case 'credit':
                $fields[] = array(
                    'type' => 'textField',
                    'name' => 'credit_charge',
                    'english_title' => 'Credit Charge',
                    'ltr' => TRUE
                );
                $fields[] = array(
                    'type' => 'textArea',
                    'name' => 'charge_desc',
                    'english_title' => 'Charge Description',
                );
            break;
            case 'changePlan':
                $list = Plans::model()->findAll('active = 1 AND deleted = 0');
                $list = CHtml::listData($list,'id','name');
                $fields[] = array(
                    'type' => 'dropDownList',
                    'name' => 'change_plan',
                    'english_title' => 'Change Plan',
                    'list' => $list
                );
            break;
            case 'sms':
                $fields[] = array(
                    'type' => 'textField',
                    'name' => 'sms_charge',
                    'english_title' => 'SMS Charge',
                );
            break;
        }

        if(isset($_POST['Users'])) {
            $model->scenario = 'changeValue';
            $planFlag = 0;
            if($type == 'changePlan' AND isset($_POST['Users']['change_plan'])){
                $planFlag = 1;
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    // disable other plans
                    $ids = Buys::model()->find(array(
                        'select' => 'GROUP_CONCAT(id) AS id',
                        'condition' => 'user_id = :userId',
                        'params' => array(
                            ':userId' => $id
                        )
                    ));
                    $ids = $ids->id;
                    PlansBuys::model()->updateAll(array('active'=>0),array(
                        'condition' => 'buy_id IN ('.$ids.')',
                    ));
                    // disable other plans end

                    $buy = new Buys;
                    $buy->scenario = 'admin_change_plan';
                    $buy->user_id = $id;
                    $buy->status = Buys::STATUS_DONE;
                    $buy->type = Buys::TYPE_PLAN;
                    if(!$buy->save()){
                        // @todo: send message for user to notify him,his plan has changed
                        $transaction->rollback();
                    }

                    Yii::import('application.modules.plans.models.*');
                    $planId = $_POST['Users']['change_plan'];
                    $selectFreePlan = new PlansBuys;
                    $selectFreePlan->attributes = array(
                        'buy_id' => $buy->id,
                        'plan_id' => $planId,
                        'active' => 1,
                    );

                    if(!$selectFreePlan->save())
                        $transaction->rollback();
                    else {
                        $model->scenario = 'changeValue';
                        $model->role_id = $selectFreePlan->plan->role_id;
                        if($model->save())
                            $planFlag = 2;
                    }
                    $transaction->commit();
                } catch (Exception $ex) {
                    Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
                    $transaction->rollback();
                }
            }
            elseif($type == 'credit' AND isset($_POST['Users']['credit_charge'])){
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    $buy = new Buys;
                    $buy->scenario = 'admin_charge_credit';
                    $buy->user_id = $id;
                    $buy->status = Buys::STATUS_DONE;
                    $buy->type = Buys::TYPE_CREDIT_CHARGE;
                    if(!$buy->save())
                        $transaction->rollback();

                    Yii::import('application.modules.plans.models.*');
                    $sum = $buy->user->credit_charge + intval($_POST['Users']['credit_charge']);
                    $credit = new CreditsTransactions;
                    $credit->attributes = array(
                        'buy_id' => $buy->id,
                        'user_id' => $id,
                        'descriptions' => $_POST['Users']['charge_desc'],
                        'price' => intval($_POST['Users']['credit_charge']),
                        'user_price' => $sum
                    );

                    if(!$credit->save())
                        $transaction->rollback();
                    else {
                        $model->credit_charge = $sum;
                    }
                    $transaction->commit();
                }catch(Exception $ex){
                    Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
                }
            }
            else
                $model->sms_charge = $_POST['Users']['sms_charge'];

            if($planFlag){
                if($planFlag == 2){
                    Yii::app()->user->setFlash('success','پلن با موفقیت ثبت شد.');
                }
            }elseif($model->save())
                Yii::app()->user->setFlash('success','اعتبار ها با موفقیت ثبت شدند.');
            else
                Yii::app()->user->setFlash('danger','خطایی در هنگام ویرایش اعتبار ها رخ داده است.');
        }
        $this->renderPartial("_update_value",array(
            'model' => $model,
            'fields' => $fields
        ));
    }

	public function actionDelete($id ,$showMessage=true){
        $model = $this->loadModel($id);
        if($this->getModule()->deletePermanently)
            $model->delete();
        else {
            $model->scenario = 'delete';
            $model->deleted = 1;
            $model->save();
        }
    }

    public function actionDeleteSelected()
    {
        foreach ($_POST['selectedItems'] as $modelId)
            $this->actionDelete($modelId,FALSE);
    }
    
	public function actionAdmin()
	{
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Users']))
            $model->attributes=$_GET['Users'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}
    public function loadModel($id)
    {
        $model=Users::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}