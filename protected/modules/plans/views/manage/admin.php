<? if (($flashMessage = Yii::app()->user->getFlash('success')) !== null): ?>    <div class="alert alert-success">
    <i class="fa fa-check-square-o fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('info')) !== null): ?>    <div class="alert alert-info">
    <i class="fa fa-info-circle fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('failed')) !== null): ?>    <div class="alert alert-failed">
    <i class="fa fa-frown-o fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?>

<?php
$this->menu=array(
    array('label'=>'افزودن پلن', 'url'=>array('create')),
);
?>

    <h1>مدیریت پلن ها</h1>


<?php


$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'static-plans-manage-grid',
    'summaryText' => '',
    'dataProvider'=>$model->search(TRUE),
    'columns'=>array(
        array(
            'header' => 'عنوان پلن استاتیک',
            'value' => '$data->name',
        ),
        array(
            'header' => 'تعداد کاربران',
            'value' => 'count($data->role->Users)',
        ),
        array(
            'header' => 'نحوه عضویت اتوماتیک',
            'value' => 'Plans::$joinCondition[$data->id]',
        ),
        array(
            'header' => 'اعتبار زمانی پلن',
            'value' => 'Plans::$creditTime[$data->id]',
        ),
        array(
            'header' => 'قوانین/تنظیمات',
            'type' => 'raw',
            'value' => 'str_replace("LINK",CHtml::link("تنظیمات",Yii::app()->createAbsoluteUrl("plans/manage/update/?id=3")),Plans::$policy[$data->id])',
        ),
        array(
            //'header' => 'خروجی ها',
            'class'=>'CButtonColumn',
            'template'=>'{users}{emails}{numbers}',
            'buttons' => array(
                'users' => array(
                    'label'=>'',
                    'options'=> array(
                        'style' => 'font-size:14px',
                        'class' => 'fa fa-users',
                        'title' => 'لیست کاربران'
                    ),
                    'url'=>'Yii::app()->createUrl("users/manage/admin/?Users[role_id]=".$data->role_id)',
                ),
                'emails' => array(
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-envelope-o',
                        'title' => 'خروجی ایمیل کاربران',
                        'target' => '_blank',
                    ),
                    'url'=>'Yii::app()->createUrl("plans/manage/export/?type=email&id=".$data->role_id)',
                ),
                'numbers' => array(
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-mobile',
                        'title' => 'خروجی موبایل کاربران',
                        'target' => '_blank',
                    ),
                    'url'=>'Yii::app()->createUrl("plans/manage/export/?type=mobile&id=".$data->role_id)',
                ),
            )
        ),
    ),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'plans-manage-grid',
    'summaryText' => '',
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'header' => 'عنوان پلن',
            'value' => '$data->name',
        ),
        array(
            'header' => 'تعداد کاربران',
            'value' => 'count($data->role->Users)',
        ),
        array(
            'header' => 'قابل فروش',
            'value' => 'Plans::$statusList[$data->active]',
        ),
        array(
            'name' => 'approved_price',
            'value' => 'number_format($data->approved_price)',
        ),
        array(
            //'header' => 'خروجی ها',
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}{users}{emails}{numbers}',
            'buttons' => array(
                'users' => array(
                    'label'=>'',
                    'options'=> array(
                        'style' => 'font-size:14px',
                        'class' => 'fa fa-users',
                        'title' => 'لیست کاربران'
                    ),
                    'url'=>'Yii::app()->createUrl("users/manage/admin/?Users[role_id]=".$data->role_id)',
                ),
                'emails' => array(
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-envelope-o',
                        'title' => 'خروجی ایمیل کاربران',
                        'target' => '_blank',
                    ),
                    'url'=>'Yii::app()->createUrl("plans/manage/export/?type=email&id=".$data->role_id)',
                ),
                'numbers' => array(
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-mobile',
                        'title' => 'خروجی موبایل کاربران',
                        'target' => '_blank',
                    ),
                    'url'=>'Yii::app()->createUrl("plans/manage/export/?type=mobile&id=".$data->role_id)',
                ),
            )
        ),
    ),
)); ?>
<?
Yii::app()->clientScript->registerCss("gridViewButton","
.grid-view .button-column{
    width: 100px;
}
.grid-view .button-column .fa{
    padding:1px 5px;color:#7e569f;font-size:18px
}
#plans-manage-grid .button-column{
    width:140px;
}
");
?>