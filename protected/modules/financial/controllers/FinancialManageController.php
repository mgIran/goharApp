<?php
class FinancialManageController extends Controller
{
    public static $actionsArray = array(
        'title' => 'امور مالی',
        'type' => 'all',
        'menu' => TRUE,
        'menu_name' => 'financial_manage',
        'settings' => array(
            'title' => 'تنظیمات فاکتور و درگاه های بانکی',
            'type' => 'admin',
            'menu' => TRUE,
            'menu_name' => 'financial_manage_settings',
            'menu_parent' => 'financial_manage',
            'url' => 'financial/manage/settings'
        ),
        'users' => array(
            'title' => 'تنظیمات اعتبار نقدی کاربران',
            'type' => 'admin',
            'menu' => TRUE,
            'menu_name' => 'financial_manage_users',
            'menu_parent' => 'financial_manage',
            'url' => 'financial/manage/users',
            'otherActions' => 'custom',
        ),
        'transactions' => array(
            'title' => 'گزارشات اعتبار موجودی نقدی',
            'type' => 'user',
            'menu' => TRUE,
            'menu_name' => 'financial_manage_transactions',
            'menu_parent' => 'financial_manage',
            'url' => 'financial/manage/transactions'
        ),
        'reports' => array(
            'title' => 'گزارشات مالی',
            'type' => 'admin',
            'menu' => TRUE,
            'menu_name' => 'financial_manage_reports',
            'menu_parent' => 'financial_manage',
            'url' => 'financial/manage/reports',
            'otherActions' => 'Days,Months',
        )
   );

    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actionSettings(){
        $criteria = new CDbCriteria;
        $criteria->addInCondition('name',array('parsian_gateway_status','ghavamin_gateway_status','mellat_gateway_status'));
        $model = SiteOptions::model()->findAll($criteria);

        $pagesModel = Pages::model()->find("name = 'seller_desc'");

        $buyHelp = Pages::model()->find("name = 'buy_help'");

        $settings = array();
        foreach($model as $item){
            $settings[$item->name] = $item;
        }

        if(isset($_POST['Pages'])){
            $pagesModel->attributes = $_POST['Pages'];
            $post = $_POST['Pages'];
            if($pagesModel->save()){
                $buyHelp->text = $_POST['Pages']['buy_help'];
                $buyHelp->save();
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
            'pagesModel' => $pagesModel,
            'buyHelp' => $buyHelp
        ));
    }

    public function actionUsers(){
        $criteria = new CDbCriteria;
        $criteria->addInCondition('name',array('minimum_credit','moderation_credit_limit'));
        $model = SiteOptions::model()->findAll($criteria);

        $settings = array();
        foreach($model as $item){
            $settings[$item->name] = $item;
        }

        $this->render('users',array(
            'settings' => $settings
        ));
    }

    public function actionTransactions(){
        $model = new CreditsTransactions('search');
        $model->unsetAttributes();

        if(isset($_GET['CreditsTransactions']))
            $model->attributes = $_GET['CreditsTransactions'];

        $model->user_id = Yii::app()->user->userID;

        // get checkouts count
        $lastCheckout = Checkouts::model()->find(array(
            'condition' => 'user_id = :userId',
            'params' => array(
                ":userId" => Yii::app()->user->userID
            ),
            'order' => 'id DESC'
        ));

        $checkoutsModel = NULL;
        $minimumCredit = SiteOptions::getOption('minimum_credit');
        if($this->currentUser->credit_charge >= $minimumCredit AND (is_null($lastCheckout) OR $lastCheckout->status != Checkouts::STATUS_REQUESTED)){
            $checkoutsModel = new Checkouts;
            $checkoutsModel->price = number_format($minimumCredit);


            $plan = $this->currentUser->activePlan->plansBuys->plan;
            $discountSections = json_decode($plan->extension_discount_sections,TRUE);
            if(isset($discountSections['credits_buy']) AND $discountSections['credits_buy']){
                $wage = floatval($plan->extension_discount);
            }
            else {
                $wage = 0;
            }
            $checkoutsModel->reqPrice = number_format(ceil($minimumCredit * 100 / floatval(100 + $wage)));
            $checkoutsModel->wage = $wage;

            $this->performAjaxValidationCheckouts($checkoutsModel);
            if(isset($_POST['Checkouts'])){
                $checkoutsModel->attributes = $_POST['Checkouts'];

                $transaction = Yii::app()->db->beginTransaction();
                try {
                    if(!$checkoutsModel->save())
                        $transaction->rollback();
                    else {
                        $this->currentUser->scenario = 'changeValue';
                        $this->currentUser->credit_charge -= $checkoutsModel->price;
                        if(!$this->currentUser->save())
                            $transaction->rollback();
                        else
                            Yii::app()->user->setFlash('success',"درخواست تسویه حساب شما با موفقیت ارسال شد.");
                    }
                    $transaction->commit();
                } catch (Exception $ex) {
                    Yii::app()->user->setFlash('danger','خطا در هنگام ثبت!');
                }
                $this->refresh();
            }
        }

        $this->render('transactions',array(
            'model'=>$model,
            'lastCheckout' => $lastCheckout
        ));
    }

    public function actionReports(){
        $daysValues = array('avg','sum');

        $daysValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 7)','>','AVG'));
        $daysValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 15)','>','AVG'));
        $daysValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 30)','>','AVG'));
        $daysValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 45)','>','AVG'));
        $daysValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 60)','>','AVG'));

        $daysValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 7)','>'));
        $daysValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 15)','>'));
        $daysValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 30)','>'));
        $daysValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 45)','>'));
        $daysValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 60)','>'));

        $monthsValues = array('avg','sum');

        $monthsValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 90)','>','AVG'));
        $monthsValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 180)','>','AVG'));
        $monthsValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 270)','>','AVG'));
        $monthsValues['avg'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 365)','>','AVG'));

        $monthsValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 90)','>'));
        $monthsValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 180)','>'));
        $monthsValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 270)','>'));
        $monthsValues['sum'][] = round(Buys::getPrice('SUBDATE(CURRENT_DATE, 365)','>'));

        $this->render('reports',array(
            'daysValues' => $daysValues,
            'monthsValues' => $monthsValues
        ));
    }

    public function actionDays(){
        $values = $_POST['values'];
        $this->renderPartial('_days_graph',array(
            'values' => $values
        ));
    }

    public function actionMonths(){
        $values = $_POST['values'];
        $this->renderPartial('_months_graph',array(
            'values' => $values
        ));
    }

    public function actionCustom($name,$value){
        if($name == 'debt')
            $name = 'users_debt';
        elseif($name == 'moderation')
            $name = 'moderation_credit_limit';
        elseif($name == 'minimum')
            $name = 'minimum_credit';
        $value = intval(str_replace(',','',$value));
        $model = SiteOptions::model()->findByAttributes(array('name'=>$name));
        if($name == 'users_debt') {
            $model->value += $value;
            if($model->save())
                $this->renderPartial('_custom');
        }
        else {
            $model->value = $value;
            $model->save();
        }

    }

    protected function performAjaxValidationCheckouts($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='checkouts-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}