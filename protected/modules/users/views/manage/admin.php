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
    array('label'=>'افزودن کاربر', 'url'=>array('create')),
    //array('label'=>'نقش کاربران', 'url'=>array('roles/admin')),
);
?>

<h1>مدیریت کاربران</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'users-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'first_name',
        'last_name',
        'email',
        'account_number',
        'mobile',
        'national_id',
        'iban',
        array(
            'name'=>'activePlan',
            'value'=>'$data->activePlan->plansBuys->plan->name',
            'filter'=>false
        ),
        array(
            'header'=>'زمان مانده از پلن',
            'value'=>'$data->activePlan->plansBuys->get_date_diff(time(), $data->activePlan->plansBuys->expire_date)',
            'filter'=>false
        ),
        array(
            'header'=>'وضعیت پنل',
            'value'=>'($data->activePlan->plansBuys->active)?"فعال":"غیرفعال"',
            'filter'=>false
        ),
        array(
            'header'=>'وضعیت اطلاعات',
            'value'=>'($data->userInfoStatus())?"کامل است":"ناقص است"',
            'filter'=>false
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons' => array(
                'delete' => array(
                    'click'=>'js:CGridViewDeleteConfirm',
                )
            )
        ),
    ),
)); ?>
