<? if(($flashMessage = Yii::app()->user->getFlash('success')) !== null):?>    <div class="alert alert-success">
        <i class="fa fa-check-square-o fa-lg"></i>
        <?=$flashMessage;?>    </div>
<? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('info')) !== null):?>    <div class="alert alert-info">
        <i class="fa fa-info-circle fa-lg"></i>
        <?=$flashMessage;?>    </div>
<? endif;?><? if(($flashMessage = Yii::app()->user->getFlash('danger')) !== null):?>    <div class="alert alert-danger">
        <i class="fa fa-frown-o fa-lg"></i>
        <?=$flashMessage;?>    </div>
<? endif;?>

<?
CHtml::$afterRequiredLabel = '';
CHtml::$beforeRequiredLabel = '';
?>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'users-form',
        'enableAjaxValidation'=>(($model->scenario=='register')?false:true),
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:function(form, data, hasError){
                if(hasError)
                {
                    $(".captcha-container img").trigger("click");
                    $("#Users_verifyCode").val("");
                }
                app.formOnCenter(true);
                return true;
            }',
            'afterValidateAttribute' => 'js:function(){
                app.formOnCenter(true);
                return true;
            }'
        )
    )); ?>
    <div class="row">
        <h6 class="pull-left"><?=static::$actionsArray[$this->action->id]['title']?></h6>
    </div>
    <div class="row errors">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <?php //echo $form->errorSummary($model); ?>
            <?php echo $form->error($model,'first_name'); ?>
            <?php echo $form->error($model,'last_name'); ?>
            <?php echo $form->error($model,'mobile'); ?>
            <?php echo $form->error($model,'email'); ?>
            <?php echo $form->error($model,'password'); ?>
            <?php echo $form->error($model,'repeat_password'); ?>
            <?php echo $form->error($model,'national_id'); ?>
            <?php echo $form->error($model,'father_name'); ?>
            <?php echo $form->error($model,'birth_town'); ?>
            <?php echo $form->error($model,'birth_city_id'); ?>
            <?php echo $form->error($model,'home_town'); ?>
            <?php echo $form->error($model,'home_city_id'); ?>
            <?php echo $form->error($model,'home_postal_code'); ?>
            <?php echo $form->error($model,'home_address'); ?>
            <?php echo $form->error($model,'home_phone_number'); ?>
            <?php echo $form->error($model,'work_postal_code'); ?>
            <?php echo $form->error($model,'work_address'); ?>
            <?php echo $form->error($model,'work_phone_number'); ?>
            <?php echo $form->error($model,'verifyCode'); ?>


        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'full_name'); ?>
        </div>
        <div class="col-md-4 pull-left">
            <?php echo $form->textField($model,'first_name',array('class'=>'form-control direct-ltr pull-left rtl-focus','style'=>'padding-left:11px;padding-right:11px','placeholder'=>'First Name...')); ?>
        </div>
        <div class="col-md-4 pull-right" style="padding-left: 6px">
            <?php echo $form->textField($model,'last_name',array('class'=>'form-control direct-ltr pull-left rtl-focus','style'=>'padding-left:11px;padding-right:11px','placeholder'=>'Last Name...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'mobile'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'mobile',array('class'=>'form-control direct-ltr','placeholder'=>'Mobile...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'email'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->textField($model,'email',array('class'=>'form-control direct-ltr','placeholder'=>'Email...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'birth_town'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php
            $this->widget('ext.iWebDropDown.iWebDropDown', array(
                'model' => $model,
                //'label'=>((isset($model->birth_city_id))?$model->birthCity->parent->title:'Birth Town'),
                'label'=>'Birth Town',
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'name'=>'birth_town',
                'list'=> UsersPlaces::towns() ,
                'id' => 'birth_town',
                //'value'=>((isset($model->birth_city_id))?$model->birthCity->parent_id:NULL),
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
                //'label'=>((isset($model->birth_city_id))?$model->birthCity->title:'Birth City'),
                'label'=>'Birth City',
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'name'=>'birth_city_id',
                'id' => 'birth_city_id',
                //'value' => $model->birth_city_id
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
                //'label'=>((isset($model->home_city_id))?$model->homeCity->parent->title:'Home Town'),
                'label'=>'Home Town',
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'name'=>'home_town',
                'list'=> UsersPlaces::towns() ,
                'id' => 'home_town',
                //'value'=>((isset($model->home_city_id))?$model->homeCity->parent_id:NULL),
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
                //'label'=>((isset($model->home_city_id))?$model->homeCity->title:'Home City'),
                'label'=>'Home City',
                'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                'name'=>'home_city_id',
                'id' => 'home_city_id',
                'value' => $model->home_city_id,
            )); ?>
            <?php echo $form->error($model,'home_city_id'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'password'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->passwordField($model,'password',array('class'=>'form-control direct-ltr','placeholder'=>'Password...')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-right">
            <?php echo $form->labelEx($model,'repeat_password'); ?>
        </div>
        <div class="col-md-8 pull-right">
            <?php echo $form->passwordField($model,'repeat_password',array('class'=>'form-control direct-ltr','placeholder'=>'Password again...')); ?>
        </div>
    </div>

    <?php if(CCaptcha::checkRequirements()): ?>
        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'verifyCode'); ?>
            </div>
            <div class="col-md-4 pull-left captcha-container">
                <?php $this->widget('CCaptcha',array(
                    'clickableImage' => true ,
                    'showRefreshButton' => false
                )); ?>
            </div>
            <div class="col-md-4 pull-left" style="padding-left: 6px">
                <?php echo $form->textField($model,'verifyCode',array('class'=>'form-control direct-ltr','placeholder'=>'Enter the code','maxlength'=>7)); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8 pull-left">
            <?php echo $form->checkBox($model,'sitePolicy',array('class'=>'pull-right css-checkbox','style'=>'margin-left:4px;')); ?>
            <?php echo $form->label($model,'sitePolicy',array('class'=>'pull-right css-label')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 pull-left">
            <?php echo CHtml::submitButton('ثبت نام', array('class'=>'form-control btn btn-default submit')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
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
");
?>

<div class="form col-md-6 col-md-offset-3">
    <div class="row">
        <?= $page->text?>
    </div>
</div>