<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo CHtml::label('پلن فعلی','current_plan'); ?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo CHtml::textField('current_plan',$model->activePlan->plansBuys->plan->name,array('class'=>'form-control','id'=>'current_plan','style'=>'background:#ffffff;cursor:text;','readonly'=>'readonly')); ?>
    </div>
    <div class="col-md-4" style="padding-left: 0">
        <?php
        $this->widget('ext.iWebAjaxForm.iWebAjaxForm',array(
            'id' => 'change-plan-form',
            'options' => array(
                'theme' => '<div class="ajax-form-overlay"><div class="ajax-form-area"><div class="ajax-form-container"></div><span class="ajax-form-cancel">انصراف</span></div></div>',
                'url' => Yii::app()->createAbsoluteUrl('users/manage/updateValue/?id='.$_GET['id'].'&type=changePlan'),
                'afterShowForm' => 'js:function(){
                    var selected;
                    $("#Users_change_plan option").each(function(){
                        if($("#current_plan").val() == $(this).text()){
                            selected = $(this).val();
                            return;
                        }
                    });
                    $("#Users_change_plan").val(selected);
                }',
                'afterCloseForm' => 'js:function(ev){
                    if(ev == "complete"){
                        $("#current_plan").val($("option[value=\'" + selectedPlanId + "\']").text());
                    }
                }'
            ),
            'htmlOptions' => array(
                'class' => 'form-control btn btn-default submit'
            ),
        ));
        ?>
    </div>
</div>
<?
Yii::app()->clientScript->registerScript("changePlan",'
var selectedPlanId;
    $(document).on("change","#Users_change_plan",function(){
        selectedPlanId = $("#Users_change_plan").val();
    });
');
?>