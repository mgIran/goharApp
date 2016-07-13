<?
Yii::app()->clientScript->registerScript('pageScripts', "
    $('body').css('overflow','hidden');
    $('#main-footer').attr('style', 'position: fixed; bottom: 0;width: 100%;');
");?>

<div class="row">  

    <!-- Head buttons-->
    <div class="row">
        <div class="header-buttons-area">
            <a title="ویرایش" class="btn btn-info big-btn left-margin-10" href="<?= createUrl('//users/update/'.$model->id)?>"><i class="fa fa-edit fa-lg"></i></a>
            <a title="لیست مدیران" class="list-btn left-margin-10" href="<?= createUrl('//users/admin')?>"></a>
            <a class="list-btn btn-back" href="<?= createUrl('//users/admin')?>" title="بازگشت"></a>
        </div>
    </div>
    
    <div class="form-content">
        
            <!-- Right form -->
            <div class="form-right" style="margin-right: 17%;">

                <!-- Avatar -->
                <div id="avatar-upload">                                       
                    <div class="big-user-icon <?= (IsNullOrEmpty($model->avatar))?"default-big-user":'' ?>">
                        <? if (!is_null($model->avatar) AND !empty($model->avatar)) echo '<img src="' . Yii::app()->createAbsoluteUrl('upload/admins/avatars/thumbnails_127x127/'.CHtml::encode($model->avatar)) . '" />'; ?>
                    </div>                    
                </div>

            </div>

            <!-- Middle form -->
            <div class="form-middle">

                <h4>اطلاعات مدیر</h4>

                
                <div class="row">
                    <span class="cms-span input-type right-float width-40 left-margin-10">
                        <?= $model->first_name ?>
                    </span>
                    <span class="element-fill">
                        <span class="cms-span full-width input-type"><?= $model->last_name ?></span>
                    </span>       
                </div>                
                
                <div class="row">
                    <span class="cms-span input-type full-width">
                        <?= CHtml::activeLabel($model, 'role_id') . ': ' . $model->UsersRoles->title ?>
                    </span>      
                </div>
                
                <div class="row">
                    <span class="cms-span input-type full-width">
                        <?= CHtml::activeLabel($model, 'user_name') . ': ' . $model->user_name ?>
                    </span>      
                </div>
                
                <div class="row">
                    <span class="cms-span input-type full-width">
                        <?= CHtml::activeLabel($model, 'email') . ': ' . $model->email ?>
                    </span>      
                </div>
                
                <div class="row">
                    <span class="cms-span input-type full-width">
                        <?= CHtml::activeLabel($model, 'mobile') . ': ' . fanum($model->mobile) ?>
                    </span>      
                </div>
               
            </div>
            
    </div>
    
</div>