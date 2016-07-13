<?php
class PlansSelectController extends Controller{
    public static $actionsArray =
        array(
            'title' => 'پلن ها',
            'index' => array(
                'title' => 'نمایش پلن ها',
                'type' => 'user',
            ),
            'buy' => array(
                'title' => 'خرید پلن',
                'type' => 'user',
            ),
            'report' => array(
                'title' => 'گزارشات',
                'type' => 'user',
                'otherActions' => 'graph',
            ),
            'bank' => array(
                'title' => 'درگاه بانک',
                'type' => 'user',
            ),
            'admin' => array(
                'title' => 'تراکنش های مربوط به خرید پلن',
                'type' => 'admin',
                'menu' => TRUE,
                'menu_parent' => 'manage_plans',
                'menu_name' => 'plan_select_admin',
                'url' => 'plans/select/admin',
                'otherActions' => 'view'
            ),
        );
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function actionIndex(){
		$dataProvider=new CActiveDataProvider('Plans',array(
            'criteria'=>array(
                'limit' => 6,
                'condition' => 'active = 1 AND deleted = 0',
                'order' => 'approved_price ASC'
            ),
        ));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
            'listText' => Pages::model()->findByAttributes(array('name'=>'plans_options_list')),
            'plansFooter' => Pages::model()->findByAttributes(array('name'=>'plans_footer')),
		));
	}

    public function actionBuy($id=NULL,$buyId=NULL){
        if(SiteOptions::getOption('plan_select') == '0')
            throw new CHttpException('خرید پلن','درحال حاضر دسترسی به خرید پلن ندارید');
        $plans = Plans::model()->findAll(array(
            'limit' => 6,
            'condition' => 'approved_price > 0 AND active = 1 AND deleted = 0',
            'order' => 'approved_price ASC'
        ));

        if(is_null($id))
            $model = Plans::model()->findByPk($plans[0]->id);
        else
            $model = Plans::model()->findByPk($id);

        if($model->approved_price == 0 OR $model->active == 0)
            throw new CHttpException('خرید پلن','این پلن قابل خرید نمی باشد.');
        elseif(is_null($model))
            throw new CHttpException('خرید پلن','پلنی انتخاب نشده است.');

        if(!is_null($buyId)){
            $buyModel = PlansBuys::model()->findByPk($buyId);
        }

        if(!isset($buyModel) OR is_null($buyId)){
            $buyModel = new PlansBuys;
            if(!isset($_POST['PlansBuys']))
                Yii::app()->user->setState('tracking_no',rand(100000, 999999));
        }

        $PlansBuysDataProvider = new CActiveDataProvider('PlansBuys',array(
            'criteria'=>array(
                'limit' => 10,
                'with' => 'buy',
                'order'=>'id DESC',
                'condition' => 'user_id = :user',
                'params' => array(
                    ':user' => Yii::app()->user->userID
                ),
            ),
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));

        $price = 0;
        $factorFields = $this->getFactorFields($model,$buyModel,$price);

        if(isset($_POST['PlansBuys'])){
            if($buyModel->isNewRecord) {
                if(is_null($id))
                    $id = $plans[1]->id;
                $buy = new Buys;
                $buy->type = Buys::TYPE_PLAN;
                $buy->status = Buys::STATUS_DOING;
                $buy->qty = 1;
                $buy->title = $model->factor_name;
                $buy->details = json_encode($factorFields);
                $buy->sum_price = $price;
                if($buy->save()){
                    $buyModel->attributes = $_POST['PlansBuys'];
                    $buyModel->buy_id = $buy->id;
                    $buyModel->plan_id = $id;
                    if($buyModel->save()){
                        $this->redirect(Yii::app()->createAbsoluteUrl('plans/select/bank/'.$buyModel->buy_id));
                    }
                }
            }
            else{
                if($_POST['pass'] != '12345')
                    $this->redirect(Yii::app()->createAbsoluteUrl('plans/select/bank/'.$buyModel->buy_id));
                $buyModel->attributes = $_POST['PlansBuys'];

                $buy = $buyModel->buy;
                $buy->attributes = $_POST['Buys'];
                $buy->save();

                // if buy done and kind of plan buy is online active that
                // * this mean if kind of plan buy is delay that's active automatically
                if($buyModel->buy->status == Buys::STATUS_DONE AND $buyModel->charge_kind == PlansBuys::KIND_ONLINE){
//                    if($buyModel->charge_kind == PlansBuys::KIND_ONLINE){
                        $ids = Buys::model()->find(array(
                            'select' => 'GROUP_CONCAT(id) AS id',
                            'condition' => 'user_id = :userId',
                            'params' => array(':userId'=>Yii::app()->user->userID),
                        ));
                        $ids = $ids->id;
                        PlansBuys::model()->updateAll(array('active'=>0),array(
                            'condition' => 'buy_id IN ('.$ids.')',
                        ));
                        $buyModel->active = 1;

                        $plan = $buyModel->plan;
                        $userRoleUpdate = Users::model()->findByPk(Yii::app()->user->userID);
                        $userRoleUpdate->role_id = $plan->role_id;
                        $userRoleUpdate->scenario = 'changeValue';
                        $userRoleUpdate->save();

                        $userRoleUpdate->userInfoClear();

                        $planArray = array(
                            'id' => $plan->id,
                            'date' => $buyModel->buy->date,
                            'name' => $plan->name,
                            'expire_time' => $plan->expire_time,
                        );
                    }
//                    elseif($buyModel->charge_kind == PlansBuys::KIND_DELAY){
//
//                    }
                    Yii::app()->user->setState('plan', json_encode($planArray));
//                }
                $buyModel->save();

                $this->refresh();
            }
        }

        $this->render('buy',array(
            'plans' => $plans,
            'model' => $model,
            'buyModel' => $buyModel,
            'PlansBuysDataProvider'=>$PlansBuysDataProvider,
            'factorFields' => $factorFields,
        ));
    }

    public function actionGraph(){
        Buys::model()->chartMaker(Buys::TYPE_PLAN);
    }

    public function actionReport(){

        $criteria = Buys::model()->reportCriteria();
        $criteria->compare('type',Buys::TYPE_PLAN);

        $PlansBuysDataProvider = new CActiveDataProvider('PlansBuys',array(
            'criteria'=> $criteria,
            'pagination'=>array(
                'pageSize' => 10,
            ),
        ));

        $this->render('//report/index',array(
            'title' => 'پلن',
            'PlansBuysDataProvider'=>$PlansBuysDataProvider,
            'graphUrl' => "plans/select/graph"
        ));
    }

    public function actionBank($id)
    {
        $model = PlansBuys::model()->findByPk($id);
        $buy = $model->buy;
        $this->render('bank',array(
            'model' => $model,
            'buy' => $buy,
        ));
    }

    public function actionAdmin(){

        $model = new PlansBuys('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['PlansBuys']))
            $model->attributes=$_GET['PlansBuys'];

        $this->render('admin',array(
            'model'=>$model,
        ));

    }

    public function actionView($id){

        $buyModel = PlansBuys::model()->findByPk($id);

        $model = Plans::model()->findByPk($buyModel->plan_id);


        $this->render('view',array(
            'buyModel' => $buyModel,
            'model' => $model
        ));
    }

    private function getFactorFields($model,$buyModel,&$price){
        $userPlan = json_decode(Yii::app()->user->plan);
        if($buyModel->isNewRecord OR is_null($buyModel->buy->details)) {
            $currentPlan = Plans::model()->findByPk($userPlan->id);
            $extensionDiscount = intval($currentPlan->extension_discount);
            $extensionPrice = ($model->approved_price * $extensionDiscount) / 100;

            // get tax
            $taxPercent = SiteOptions::model()->findByAttributes(array('name'=>'tax'));
            $taxPercent = floatval($taxPercent->value);
            $taxPrice = ($model->approved_price * $taxPercent) / 100;
            $price = intval($model->approved_price) - $extensionPrice + $taxPrice;

            /*// user credits
            $charge = $this->currentUser->credit_charge;

            $credit =array(
                'label'=>'',
                'value'=>'',
                'unit'=>'',
            );
            if($charge < 0){
                $credit = array(
                    'label'=> '+ بدهی اعتبار نقدی',
                    'value'=> number_format($charge * -1),
                    'unit'=>'تومان',
                );
                $price += $charge * -1;
            }elseif($credit > 0 AND isset($_POST['use_credit']) AND $_POST['user_credit'] === '1'){
                $credit = array(
                    'label'=> 'کسر شده از اعتبار نقدی',
                    'value'=> number_format($price),
                    'unit'=>'تومان',
                );
            }*/

            $factorFields = array(
                array(
                    'label' => 'تعداد (مقدار) محصول :',
                    'value' => '1',
                    'unit'  => 'واحد',
                ),
                array(
                    'label' => 'قیمت واحد',
                    'value' => number_format($model->approved_price),
                    'unit'  => 'تومان',
                ),
                array(
                    'label' => '+ قیمت کل',
                    'value' => number_format($model->approved_price),
                    'unit'  => 'تومان',
                ),
                'border',
                array(
                    'label' => 'مقدار درصد تخفیفی پلن',
                    'value' => $extensionDiscount,
                    'unit'  => 'درصد',
                ),
                array(
                    'label' => '- مبلغ تخفیف پلن',
                    'value' => number_format(ceil($extensionPrice)) ,
                    'unit'  => 'تومان',
                ),
                'border',
                array(
                    'label' => 'درصد مالیات بر ارزش افزوده',
                    'value' => $taxPercent,
                    'unit'  => 'درصد',
                ),
                array(
                    'label' => '+ مبلغ مالیات بر ارزش افزوده',
                    'value' => number_format(ceil($taxPrice)),
                    'unit' => 'تومان',

                ),
                //$credit,
                'final' => array(
                    'label' => 'مبلغ صورت حساب',
                    'value' => $price,
                    'unit'  => 'تومان',
                )
            );
        }
        else {
            $factorFields = json_decode($buyModel->buy->details,true);
        }
        return $factorFields;
    }

}