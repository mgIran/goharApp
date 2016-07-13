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
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'users-legal-documents-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation' => true,
        'action' => Yii::app()->createAbsoluteUrl('users/documents/index') ,
        'htmlOptions' => array(
            'class' => 'col-md-6 col-md-offset-4'
        ),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        )
    )); ?>
        <div class="row">
            <h6 class="pull-left">
                مدارک حقوقی
            </h6>
        </div>

        <div class="row errors">
            <div class="col-md-4 pull-right"></div>
            <div class="col-md-8 pull-right">
                <?php echo $form->errorSummary($model); ?>
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
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'personal_image'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?$label = (!is_null( $model->personal_image) AND !empty($model->personal_image));?>
                <?if($label):?>
                    <span class="btn btn-success col-md-1">✔</span>
                <?endif;?>
                <a class="btn btn-<?=($label?'success':'primary')?> col-md-1<?=($label?1:2)?> align-right" href="#" data-backdrop="static" data-toggle="modal" data-target="#upload-personal_image">
                    جهت <?= ($label)?"ویرایش":"افزودن" ?> تصویر کلیک کنید.
                </a>

            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'national_id'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'national_id',array('class'=>'form-control direct-ltr','placeholder'=>'National ID')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'national_card_front'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?$label = (!is_null( $model->national_card_front) AND !empty($model->national_card_front));?>
                <?if($label):?>
                    <span class="btn btn-success col-md-1">✔</span>
                <?endif;?>
                <a class="btn btn-<?=($label?'success':'primary')?> col-md-1<?=($label?1:2)?> align-right" href="#" data-backdrop="static" data-toggle="modal" data-target="#upload-national_card_front">
                    جهت <?= ($label)?"ویرایش":"افزودن" ?>  کلیک کنید.
                </a>

            </div>
        </div>
        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'national_card_rear'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?$label = (!is_null( $model->national_card_rear) AND !empty($model->national_card_rear));?>
                <?if($label):?>
                    <span class="btn btn-success col-md-1">✔</span>
                <?endif;?>
                <a class="btn btn-<?=($label?'success':'primary')?> col-md-1<?=($label?1:2)?> align-right" href="#" data-backdrop="static" data-toggle="modal" data-target="#upload-national_card_rear">
                    جهت <?= ($label)?"ویرایش":"افزودن" ?>  کلیک کنید.
                </a>

            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'father_name'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'father_name',array('class'=>'form-control','placeholder'=>'Father Name')); ?>
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
                    'label'=>((isset($model->birth_city_id))?$model->birthCity->parent->title:'Birth Town'),
                    'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                    'name'=>'birth_town',
                    'list'=> $towns ,
                    'id' => 'birth_town',
                )); ?>
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
                )); ?>
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
                    'label'=>((isset($model->birth_city_id))?$model->homeCity->parent->title:'Home Town'),
                    'icon' => '<span class="glyphicon glyphicon-chevron-down"></span>',
                    'name'=>'home_town',
                    'list'=> $towns ,
                    'id' => 'home_town',
                )); ?>
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
                )); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'home_postal_code'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'home_postal_code',array('class'=>'form-control direct-ltr','placeholder'=>'Home Postal Code')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'home_address'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textArea($model,'home_address',array('class'=>'form-control','placeholder'=>'Home Address')); ?>
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
                <?php echo $form->textField($model,'home_phone_prefix',array('class'=>'form-control direct-ltr','placeholder'=>'21')); ?>
            </div>
            <div class="col-md-4 pull-right">
                <?php echo $form->textField($model,'home_phone_number',array('class'=>'form-control direct-ltr','placeholder'=>'Home Phone Number')); ?>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'work_postal_code'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textField($model,'work_postal_code',array('class'=>'form-control direct-ltr','placeholder'=>'Work Postal Code')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'work_address'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?php echo $form->textArea($model,'work_address',array('class'=>'form-control','placeholder'=>'Work Address')); ?>
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
                <?php echo $form->textField($model,'work_phone_prefix',array('class'=>'form-control direct-ltr','placeholder'=>'21')); ?>
            </div>
            <div class="col-md-4 pull-right">
                <?php echo $form->textField($model,'work_phone_number',array('class'=>'form-control direct-ltr','placeholder'=>'Work Phone Number')); ?>
            </div>
        </div>
        
        <hr/>

        <div class="row">
            <div class="col-md-4 pull-right">
                <?php echo $form->labelEx($model,'birth_certificate_first'); ?>
            </div>
            <div class="col-md-8 pull-right">
                <?$label = (!is_null( $model->birth_certificate_first) AND !empty($model->birth_certificate_first));?>
                <?if($label):?>
                    <span class="btn btn-success col-md-1">✔</span>
                <?endif;?>
                <a class="btn btn-<?=($label?'success':'primary')?> col-md-1<?=($label?1:2)?> align-right" href="#" data-backdrop="static" data-toggle="modal" data-target="#upload-birth_certificate_first">
                    جهت <?= ($label)?"ویرایش":"افزودن" ?>  کلیک کنید.
                </a>

            </div>
        </div>
        <br/><br/>


        <div class="row">
            <div class="col-md-4 pull-left">
                <?php echo CHtml::submitButton('ثبت', array('class'=>'form-control btn btn-default submit')); ?>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>

<? $this->renderPartial('_upload',array(
    'model'=>$model
));

Yii::app()->clientScript->registerScript("UsersLegalDocuments","
    $('#UsersLegalDocuments_birth_town').on('change',function(){
        var town_id = $(this).val();
        $('#birth_city_id .category-select-head').html('<div class=\"category-select-text\">درحال بارگذاری ...</div>');
        $('#birth_city_id ul').html('&nbsp;&nbsp;&nbsp;...');
        $('#UsersLegalDocuments_birth_city_id').val('');
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
    $('#UsersLegalDocuments_home_town').on('change',function(){
        var town_id = $(this).val();
        $('#home_city_id .category-select-head').html('<div class=\"category-select-text\">درحال بارگذاری ...</div>');
        $('#home_city_id ul').html('&nbsp;&nbsp;&nbsp;...');
        $('#UsersLegalDocuments_home_city_id').val('');
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