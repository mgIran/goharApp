<div class="col-md-12">
    <div class="plans-labels" style="float: right;overflow: visible">
        <div class="col-md-6 pull-right">
            <div class="factor-sections col-md-12" style="padding-top: 15px;padding-bottom: 14px;overflow: visible">
                <div class="row">
                    <div class="title col-md-4">انتخاب (تمدید یا تغییر) پلن جدید</div>
                    <div class="value select-plan col-md-8">
                        <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(
                            //'model' => $plans,
                            'label'=> $model->name,
                            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                            'name'=>'select_plan',
                            'list'=> CHtml::listData($plans,'id','name') ,
                            'id'=>'select_plan',
                            'options' => array(
                                //'fixedContent' => TRUE,
                                'afterSelect' => 'js:function(value,text){
                                    window.location.href = createAbsoluteUrl("plans/select/buy/"+value);
                                }',
                            )
                        )); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 pull-right">
            <div class="factor-sections plan-change col-md-12">
                <div class="row">
                    <div class="title col-md-12">
                        <input <?=(!$buyModel->isNewRecord?'disabled':'')?> <?=($buyModel->charge_kind==PlansBuys::KIND_ONLINE?'checked="checked"':'')?> value="online" name="plan_change" type="radio" id="online_change" />
                        <label for="online_change"> (آنلاین) تبدیل پلن فعلی به پلن درحال خرید، بلافاصله بعد از پرداخت وجه (همان لحظه) صورت گیرد. </label>
                    </div>
                </div>
                <div class="row">
                    <div class="title col-md-12">
                        <input <?=(!$buyModel->isNewRecord?'disabled':'')?> <?=($buyModel->charge_kind==PlansBuys::KIND_DELAY?'checked="checked"':'')?> value="delay" name="plan_change" type="radio" id="delay_change" />
                        <label for="delay_change"> (با تاخیر) تبدیل پلن فعلی به پلن درحال خرید بعد از اتمام اعتبار زمانی پلن فعلی (10 روز بعد) صورت گیرد </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="factor-final"></div>
    </div>
    <div class="clearfix"></div>
    <br/>
    <div class="clearfix"></div>
    <div class="plans-labels">
        <div class="col-md-6 pull-right">
            <?$this->renderPartial('_factor',array(
                    'model' => $model,
                    'factorFields' => $factorFields,
                    'buyModel' => $buyModel,
                )
            )?>
        </div>
        <div class="col-md-6 pull-right">
            <?$this->renderPartial('//report/_desc')?>
            <div class="factor-sections factor-box col-md-12">
                <?$this->renderPartial('_form', array(
                        'buyModel' => $buyModel,
                        'factorFields' => $factorFields
                    )
                )?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="clearfix"></div>
<br>
<div class="col-md-12">
    <div class="plans-labels">
        <?$this->renderPartial('//report/_recent',array(
                'title' => 'پلن',
                'dataProvider' => $PlansBuysDataProvider
            )
        )?>
    </div>
</div>
<?
Yii::app()->clientScript->registerScript("charge_kind",'
    $(document).on("click","#online_change",function(){
        $("#PlansBuys_charge_kind").val(1);
    });
    $(document).on("click","#delay_change",function(){
        $("#PlansBuys_charge_kind").val(2);
    });
',CClientScript::POS_END);
Yii::app()->clientScript->registerCss("charge","
.category-select .flash{
    top:9px;
}
");
?>