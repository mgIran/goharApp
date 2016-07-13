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
$this->breadcrumbs=array('مدیران', 'مدیریت');
$this->menu=array(
    array('label'=>'افزودن مدیر', 'url'=>array('create')),
    array('label'=>'نقش مدیران', 'url'=>array('roles/admin')),
);
?>

<h1>مدیریت مدیران</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'admins-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'first_name',
        'last_name',
        'email',
        array(
            'name'=>'role_id',
            'value'=>'$data->AdminsRoles->title'
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
