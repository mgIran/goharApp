<?
$this->widget('ext.iWebFunctions.iWebFunctions');
$phoneNumberPlaceHolder = "21";
if(isset($model->homeCity) AND !empty($model->homeCity->phone_number_prefix)){
    $phoneNumberPlaceHolder = $model->homeCity->phone_number_prefix;
}
$this
?>
<div class="form col-md-12" style="padding-bottom: 50px">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'users-form',
    'enableAjaxValidation'=>true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:function(){
                app.formOnCenter(true);
                return true;
            }',
        'afterValidateAttribute' => 'js:function(){
                app.formOnCenter(true);
                return true;
            }'
    ),
    'htmlOptions' => array(
        'class' => 'col-md-6 pull-right'
    ),
)); ?>
<?php echo $form->errorSummary($model); ?>
<div class="pull-right">
<div class="row">
    <h6 class="pull-right"><?=(isset($title))?$title:static::$actionsArray[$this->action->id]['title']?></h6>
</div>

<?if(!$model->isNewRecord):?>
    <div class="row">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-4 pull-right">
            <a id="big-user-text" href="#" data-backdrop="static" data-toggle="modal" data-target="#upload-avatar">جهت <?= (is_null($model->avatar) OR empty($model->avatar))?"ویرایش":"افزودن" ?> تصویر کلیک کنید.</a>
            <a href="#" class="big-user-icon <?= (is_null($model->avatar) OR empty($model->avatar))?"default-big-user":'' ?>" data-backdrop="static" data-toggle="modal" data-target="#upload-avatar">
                <? if (!is_null($model->avatar) AND !empty($model->avatar)) echo '<img src="' . Yii::app()->createAbsoluteUrl('upload/users/avatars/thumbnails_127x127/'.CHtml::encode($model->avatar)) . '" />'; ?>
            </a>
        </div>
    </div>
<?endif;?>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'first_name'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'first_name',array('class'=>'form-control','placeholder'=>'First Name...')); ?>
        <?php echo $form->error($model,'first_name'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'last_name'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'last_name',array('class'=>'form-control','placeholder'=>'Last Name...')); ?>
        <?php echo $form->error($model,'last_name'); ?>
    </div>
</div>


<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'mobile'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'mobile',array('class'=>'form-control direct-ltr just-number','placeholder'=>'Mobile...')); ?>
        <?php echo $form->error($model,'mobile'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php if($model->scenario=='adminCreate'):?>
            <?php echo CHtml::label('پست الکترونیک *','Users_email'); ?>
        <?php else:?>
            <?php echo $form->labelEx($model,'email'); ?>
        <?php endif;?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'email',array('class'=>'form-control direct-ltr','placeholder'=>'Email...')); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'father_name'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'father_name',array('class'=>'form-control just-letter','placeholder'=>'Father Name')); ?>
        <?php echo $form->error($model,'father_name'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'national_id'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'national_id',array('class'=>'form-control direct-ltr just-number','placeholder'=>'National ID')); ?>
        <?php echo $form->error($model,'national_id'); ?>
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'birth_town'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php
        $this->widget('ext.iWebDropDown.iWebDropDown', array(
            'model' => $model,
            'label'=>((isset($model->birth_city_id))?$model->birthCity->parent->title:'Birth Town'),
            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
            'name'=>'birth_town',
            'list'=> UsersPlaces::towns() ,
            'id' => 'birth_town',
            'value'=>((isset($model->birth_city_id))?$model->birthCity->parent_id:NULL),
        )); ?>
        <?php echo $form->error($model,'birth_town'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'birth_city_id'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php
        $this->widget('ext.iWebDropDown.iWebDropDown', array(
            'model' => $model,
            'label'=>((isset($model->birth_city_id))?$model->birthCity->title:'Birth City'),
            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
            'name'=>'birth_city_id',
            'id' => 'birth_city_id',
            'value' => $model->birth_city_id,
            'list'=> (isset($model->birth_city_id)?UsersPlaces::citiesByTown($model->birthCity->parent_id):null),
        )); ?>
        <?php echo $form->error($model,'birth_city_id'); ?>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'home_town'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php

        $this->widget('ext.iWebDropDown.iWebDropDown', array(
            'model' => $model,
            'label'=>((isset($model->home_city_id))?$model->homeCity->parent->title:'Home Town'),
            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
            'name'=>'home_town',
            'list'=> UsersPlaces::towns() ,
            'id' => 'home_town',
            'value'=>((isset($model->home_city_id))?$model->homeCity->parent_id:NULL),
        )); ?>
        <?php echo $form->error($model,'home_town'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'home_city_id'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php
        $this->widget('ext.iWebDropDown.iWebDropDown', array(
            'model' => $model,
            'label'=>((isset($model->home_city_id))?$model->homeCity->title:'Home City'),
            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
            'name'=>'home_city_id',
            'id' => 'home_city_id',
            'value' => $model->home_city_id,
            'list'=> (isset($model->home_city_id)?UsersPlaces::citiesByTown($model->homeCity->parent_id):null),
        )); ?>
        <?php echo $form->error($model,'home_city_id'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'home_postal_code'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'home_postal_code',array('class'=>'form-control direct-ltr just-number','placeholder'=>'Home Postal Code')); ?>
        <?php echo $form->error($model,'home_postal_code'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'home_address'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textArea($model,'home_address',array('class'=>'form-control','placeholder'=>'Home Address')); ?>
        <?php echo $form->error($model,'home_address'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'home_phone_number'); ?>
    </div>
    <div class="col-md-1 pull-left">
        <label class="pull-right" style="padding: 6px;font-size: 16px">0</label>
    </div>
    <div class="col-md-2 pull-left">
        <?php echo $form->textField($model,'home_phone_prefix',array('class'=>'form-control direct-ltr just-number','placeholder'=>$phoneNumberPlaceHolder)); ?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo $form->textField($model,'home_phone_number',array('class'=>'form-control direct-ltr just-number','placeholder'=>'Home Phone Number')); ?>
        <?php echo $form->error($model,'home_phone_prefix'); ?>
        <?php echo $form->error($model,'home_phone_number'); ?>
    </div>
</div>

<hr/>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'work_town'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php
        //var_dump($model->work_city_id);exit;
        $this->widget('ext.iWebDropDown.iWebDropDown', array(
            'model' => $model,
            'label'=>((isset($model->work_city_id))?$model->workCity->parent->title:'work Town'),
            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
            'name'=>'work_town',
            'list'=> UsersPlaces::towns() ,
            'id' => 'work_town',
            'value'=>((isset($model->work_city_id))?$model->workCity->parent_id:NULL),
        )); ?>
        <?php echo $form->error($model,'work_town'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'work_city_id'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php
        $this->widget('ext.iWebDropDown.iWebDropDown', array(
            'model' => $model,
            'label'=>((isset($model->work_city_id))?$model->workCity->title:'work City'),
            'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
            'name'=>'work_city_id',
            'id' => 'work_city_id',
            'value' => $model->work_city_id,
            'list'=> (isset($model->work_city_id)?UsersPlaces::citiesByTown($model->workCity->parent_id):null),
        )); ?>
        <?php echo $form->error($model,'work_city_id'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'work_postal_code'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textField($model,'work_postal_code',array('class'=>'form-control direct-ltr just-number','placeholder'=>'Work Postal Code')); ?>
        <?php echo $form->error($model,'work_postal_code'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'work_address'); ?>
    </div>
    <div class="col-md-8 pull-right">
        <?php echo $form->textArea($model,'work_address',array('class'=>'form-control','placeholder'=>'Work Address')); ?>
        <?php echo $form->error($model,'work_address'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <?php echo $form->labelEx($model,'work_phone_number'); ?>
    </div>
    <div class="col-md-1 pull-left">
        <label class="pull-right" style="padding: 6px;font-size: 16px">0</label>
    </div>
    <div class="col-md-2 pull-left">
        <?php echo $form->textField($model,'work_phone_prefix',array('class'=>'form-control direct-ltr just-number','placeholder'=>$phoneNumberPlaceHolder)); ?>
    </div>
    <div class="col-md-4 pull-right">
        <?php echo $form->textField($model,'work_phone_number',array('class'=>'form-control direct-ltr just-number','placeholder'=>'Work Phone Number')); ?>
        <?php echo $form->error($model,'work_phone_prefix'); ?>
        <?php echo $form->error($model,'work_phone_number'); ?>
    </div>
</div>
</div>
<?if(!$model->isNewRecord):?>
    <!-- change password button-->
    <div class="row">
        <div class="col-md-8 pull-left">
            <?php echo CHtml::tag('div', array('id'=>'ch-password-btn','class'=>'form-control btn btn-default submit'),'تغییر رمز عبور'); ?>
        </div>
    </div>
<?endif;?>
    <!-- change password block -->
    <div id="ch-password-div" style="<?=(!$model->isNewRecord)?"display:none":""?>">
        <?if(!$model->isNewRecord):?>
            <?php echo $form->hiddenField($model, 'passwordSet', array('value'=>0)); ?>

            <!--<div class="row">
                <div class="col-md-4 pull-right">
                    <?php /*echo $form->labelEx($model,'old_password'); */?>
                </div>
                <div class="col-md-8 pull-right">
                    <?php /*echo $form->passwordField($model,'old_password',array('class'=>'form-control direct-ltr','placeholder'=>'Old Password...')); */?>
                    <?php /*echo $form->error($model,'old_password'); */?>
                </div>
            </div>-->
        <?endif;?>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php if($model->scenario=='adminCreate'):?>
                    <?php echo CHtml::label('رمز عبور *','Users_password'); ?>
                <?php else:?>
                    <?php echo $form->labelEx($model,'password'); ?>
                <?php endif;?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control direct-ltr','placeholder'=>'Password...','value'=>'')); ?>
                <?php echo $form->error($model,'password'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php if($model->scenario=='adminCreate'):?>
                    <?php echo CHtml::label('تکرار رمز عبور *','Users_repeat_password'); ?>
                <?php else:?>
                    <?php echo $form->labelEx($model,'repeat_password'); ?>
                <?php endif;?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->passwordField($model,'repeat_password',array('class'=>'form-control direct-ltr','placeholder'=>'Password again...')); ?>
                <?php echo $form->error($model,'repeat_password'); ?>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-md-4 pull-left">
        <?php echo CHtml::submitButton(($model->isNewRecord)?'ثبت کاربر جدید':'ویرایش', array('class'=>'form-control btn btn-default submit')); ?>
    </div>
</div>

<?if(!is_null($model->agent_id)):?>
    <div class="row">
        <div class="col-md-4 pull-right">
            <? echo CHtml::label('نام نماینده','agent-name')?>

        </div>
        <div class="col-md-8 pull-right">
            <?
            $agent = $model->agent;
            echo CHtml::textField('agent-name', $agent->first_name." ".$agent->last_name,array(
                'class'=>'form-control',
                'readonly'=>'readonly',
                'style' => 'cursor:default;'
            ));
            ?>
        </div>
    </div>
<?endif;?>

<div class="row">
    <div class="col-md-4 pull-right">
        <? echo CHtml::label('لینک نمایندگی','agent-link')?>

    </div>
    <div class="col-md-8 pull-right" style="background: #fff;">

        <? echo CHtml::textField('agent-link',(!$model->isNewRecord)?Yii::app()->createAbsoluteUrl("users/account/register?agentId=".base64_encode($model->id)):'در حال حاضر لینک نمایندگی ندارد',array(
            'class'=>'form-control direct-ltr',
            'readonly'=>'readonly',
            'style' => 'cursor:text;'.(($model->isNewRecord)?'direction:rtl !important':''),
        ));?>
    </div>
</div>

<?php $this->endWidget(); ?>
<?if(!$model->isNewRecord):?>
    <div class="col-md-6">
        <div class="row">
            <h6 class="pull-right">
                مدارک حقوقی
            </h6>
        </div>
        <?$this->renderPartial('users.views.partials._legal_documents',array(
            'model' => $model,
            'form' => $form
        ))?>
    </div>

    <?if(isset($chargeForm) AND $chargeForm):?>
        <div class="col-md-6">
            <br/>
            <div class="row">
                <h6 class="pull-right">
        ویرایش اعتبارها
        </h6>
            </div>
            <?$this->renderPartial('users.views.partials._charge_form',array(
                'model' => $model
        ))?>
        </div>
        <div class="col-md-6" style="margin-top: 18px">
            <div class="row">
                <h6 class="pull-right">
                    مشخصات پلن
                </h6>
            </div>
            <?$this->renderPartial('users.views.account._plan',array(
                'model' => $model
            ))?>
        </div>
        <div class="col-md-6" style="margin-top: 18px">
            <div class="row">
                <h6 class="pull-right">
                    اطلاعات حساب بانکی
                </h6>
            </div>
            <?$this->renderPartial('users.views.account._bank_account',array(
                'model' => $model
            ))?>
        </div>
    <?endif;?>
<?endif;?>

</div>
<?
Yii::app()->clientScript->registerScript("Users","
    $('#Users_birth_town').on('change',function(){
        var town_id = $(this).val();
        $('#birth_city_id .category-select-head').html('<div class=\"category-select-text\">درحال بارگذاری ...</div>');
        $('#birth_city_id ul').html('&nbsp;&nbsp;&nbsp;...');
        $('#Users_birth_city_id').val('');
        $.ajax({
            url : createAbsoluteUrl('users/places/getCities/'+town_id),
            success : function(data){
                $('#birth_city_id ul').html(data);
                $('#birth_city_id .category-select-head').html(
                    '<div class=\"flash\">' +
                        '<div class=\"glyphicon glyphicon-chevron-down\"></div>' +
                    '</div>' +
                    '<div class=\"category-select-text\">شهرستان</div>'
                );
            }
        });
    });
    $('#Users_home_town').on('change',function(){
        var town_id = $(this).val();
        $('#home_city_id .category-select-head').html('<div class=\"category-select-text\">درحال بارگذاری ...</div>');
        $('#home_city_id ul').html('&nbsp;&nbsp;&nbsp;...');
        $('#Users_home_city_id').val('');
        $.ajax({
            url : createAbsoluteUrl('users/places/getCities/'+town_id),
            success : function(data){
                $('#home_city_id ul').html(data);
                $('#home_city_id .category-select-head').html(
                    '<div class=\"flash\">' +
                        '<div class=\"glyphicon glyphicon-chevron-down\"></div>' +
                    '</div>' +
                    '<div class=\"category-select-text\">شهرستان</div>'
                );
            }
        });
    });

    $('#Users_work_town').on('change',function(){
        var town_id = $(this).val();
        $('#work_city_id .category-select-head').html('<div class=\"category-select-text\">درحال بارگذاری ...</div>');
        $('#work_city_id ul').html('&nbsp;&nbsp;&nbsp;...');
        $('#Users_work_city_id').val('');
        $.ajax({
            url : createAbsoluteUrl('users/places/getCities/'+town_id),
            success : function(data){
                $('#work_city_id ul').html(data);
                $('#work_city_id .category-select-head').html(
                    '<div class=\"flash\">' +
                        '<div class=\"glyphicon glyphicon-chevron-down\"></div>' +
                    '</div>' +
                    '<div class=\"category-select-text\">شهرستان</div>'
                );
            }
        });
    });
");

 $this->renderPartial('users.views.account._uploadAvatar',array(
    'model'=>$model,
    'isSetting' => true
));


Yii::app()->clientScript->registerCss('rowReset','
.row{margin:0}
');