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

    <h1>دفترچه مخاطبین</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'contacts-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'first_name',
        'last_name',
        'mobile',
        'email',
        array(
            'name' => 'category',
            'value' => '$data->category->title'
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

    <a  data-toggle="modal" data-backdrop="static" data-target="#upload-txt" href="#" class="btn btn-primary" style="margin-left: 10px" title="ثبت مخاطب به بانک از طریق آپلود فایل" href="">ثبت مخاطب به بانک از طریق آپلود فایل</a>

<? $this->renderPartial('_from_file')?>