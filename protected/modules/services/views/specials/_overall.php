<div class="row">
    <div class="dynamic-fields">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <div class="dynamic-fields-row">
                <div class="col-md-5 pull-right">
                    عنوان گزینه
                </div>
                <div class="col-md-1 pull-right"></div>
                <div class="col-md-5 pull-right">
                    کلیدواژه
                </div>
                <div class="col-md-1 pull-right"></div>
            </div>

            <?if(!isset($_POST['SpecialServices']['fields'])):?>
                <div class="dynamic-fields-row">
                    <div class="col-md-5 pull-right">
                        <input name="SpecialServices[fields][0][title]" type="text" class="form-control">
                    </div>
                    <div class="col-md-1 pull-right"></div>
                    <div class="col-md-5 pull-right">
                        <input name="SpecialServices[fields][0][value]" type="text" class="form-control keywords-field">
                    </div>
                    <div class="col-md-1 pull-right action-links">
                        <a class="remove-link" href="#">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </div>
                </div>
            <?else:?>
                <?foreach($_POST['SpecialServices']['fields'] as $key=>$field):?>
                    <div class="dynamic-fields-row">
                        <div class="col-md-5 pull-right">
                            <input name="SpecialServices[fields][<?=$key?>][title]" value="<?=$field['title']?>" type="text" class="form-control">
                        </div>
                        <div class="col-md-1 pull-right"></div>
                        <div class="col-md-5 pull-right">
                            <input name="SpecialServices[fields][<?=$key?>][value]" value="<?=$field['value']?>" type="text" class="form-control keywords-field">
                        </div>
                    </div>
                <?endforeach;?>
            <?endif;?>
        </div>
    </div>
    <div class="col-md-12">
        <?php echo $form->error($model,'fields'); ?>
    </div>
</div>
<?if($this->_type == SpecialServices::TYPE_OVERALL):?>
    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($overallModel,'start_time'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php //echo $form->textField($overallModel,'start_time',array('class'=>'form-control','placeholder'=>'Start Time...'));
            //echo CHtml::textField('start_time',);
            $this->widget('ext.JalaliDatePicker.JalaliDatePicker',array('textField'=>'start_time',
                'options'=>array(
                    'changeMonth'=>'true',
                    'changeYear'=>'true',
                    'showButtonPanel'=>'true',
                    'changeDate' => 'true',
                ),
                'model' => $overallModel
            ));

            $this->widget('ext.timepicker.timepicker', array(
                'model'=>$overallModel,
                'name'=>'start_time',
                'skin' => 'new',
                'options' => array(
                    'htmlOptions' => array('placeholder'=>'Start Time...')
                )
            ));

            ?>

            <? echo $form->error($overallModel,'start_time')?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($overallModel,'interval'); ?>
        </div>
        <div class="col-md-2 pull-right">
            <?php echo $form->textField($overallModel,'interval',array('class'=>'form-control','placeholder'=>'Interval...')); ?>
        </div>
        <div class="col-md-1 pull-right" style="padding:8px">
                دقیقه
        </div>
        <div class="col-md-2 pull-right">
            <?php echo $form->labelEx($overallModel,'quantity'); ?>
        </div>
        <div class="col-md-2 pull-right">
            <?php echo $form->textField($overallModel,'quantity',array('class'=>'form-control','placeholder'=>'Quantity...')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <? echo $form->error($overallModel,'interval')?>
            <? echo $form->error($overallModel,'quantity')?>
        </div>
    </div>

    <?
    Yii::app()->clientScript->registerScript('startTime',"
    jQuery(function($) {
        $(document).on('click','.timepicker.hasDatepicker',function(){
            $('.ui-datepicker-trigger').trigger('click');
        });
        setTimeout(function(){
            $('.timepicker.hasDatepicker').addClass('form-control');
            $('.ui-datepicker-trigger').hide();
        },1000);
    });
    ",CClientScript::POS_END);
    ?>
<?endif;?>
