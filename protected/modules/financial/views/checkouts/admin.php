<? if (($flashMessage = Yii::app()->user->getFlash('success')) !== null): ?>    <div class="alert alert-success">
    <i class="fa fa-check-square-o fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('info')) !== null): ?>    <div class="alert alert-info">
    <i class="fa fa-info-circle fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('failed')) !== null): ?>    <div class="alert alert-failed">
    <i class="fa fa-frown-o fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif;?>

<h1><?=static::$actionsArray[$this->action->id]['title']?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'checkouts-grid',
    'summaryText' => '',
    'filter' => $model,
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name' => 'holder_name',
            'value' => '$data->user->holder_name',
        ),
        array(
            'name' => 'national_id',
            'value' => '$data->user->national_id',
        ),
        array(
            'name' => 'mobile',
            'value' => '$data->user->mobile',
        ),
        array(
            'name' => 'email',
            'value' => '$data->user->email',
        ),
        array(
            'name' => 'iban',
            'value' => '"IR".$data->user->iban',
        ),
        array(
            'filter' => false,
            'name' => 'reqPrice',
            'value' => 'number_format($data->price)',
        ),
        array(
            'filter' => false,
            'name' => 'wage',
            'value' => 'number_format($data->wage)',
        ),
        array(
            'filter' => false,
            'name' => 'price',
            'value' => 'number_format((ceil($data->price * 100 / floatval(100 + $data->wage))))',
        ),
        array(
            'filter' => false,
            'name' => 'req_date',
            'value' => 'Yii::app()->jdate->date("Y/m/d",$data->req_date)',
        ),
        array(
            'class'=>'CButtonColumn',
            'deleteButtonUrl' => 'Yii::app()->createAbsoluteUrl("financial/checkouts/delete?id=".$data->id)',
            'template'=>'{pay}{delete}',
            'buttons' => array(
                'pay' => array(
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-file-text-o',
                        'title' => 'اطلاعات واریز',
                        'target' => '_blank',
                    ),
                    'url'=>'Yii::app()->createUrl("financial/checkouts/pay?id=".$data->id)',
                ),

            )
        )
    ),
));

?>

<div class="row">
    <?$this->renderPartial('_export',array(
        'model' => $model
    ));?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'checkouts-export-grid',
    'summaryText' => '',
    'dataProvider'=>$exportModel->search(),
    'columns'=>array(
        'id',
        array(
            'name' => 'price',
            'value' => 'number_format($data->price)',
        ),
        array(
            'name' => 'export_date',
            'value' => 'Yii::app()->jdate->date("Y/m/d",$data->export_date)',
        ),
        array(
            'header'=>'فایل ورودی',
            'class'=>'CButtonColumn',
            'template'=>'{download}{import}',
            'buttons' => array(
                'download' => array(
                    'visible'=>'!is_null($data->import_file)',
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-cloud-download',
                        'title' => 'دانلود',
                        'target' => '_blank',
                    ),
                    'url'=>'(!is_null($data->import_file))?Yii::app()->createUrl("financial/checkouts/download?type=import&id=".$data->id):"#"',
                ),
                'import' => array(
                    'visible'=>'is_null($data->import_file)',
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-download',
                        'title' => '',
                        'target' => '_blank',
                        'onClick' => 'importId = $(this).parents("tr").find("td:first").text();',
                        'data-backdrop' => 'static',
                        'data-toggle' => 'modal',
                        'data-target' => '#upload-checkouts',
                    ),
                    'url'=>'"#"',
                ),
            )
        ),
        array(
            'class'=>'CButtonColumn',
            'deleteButtonUrl' => 'Yii::app()->createAbsoluteUrl("financial/checkouts/export_delete?id=".$data->id)',
            'template'=>'{download}{delete}',
            'buttons' => array(
                'download' => array(
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-cloud-download',
                        'title' => 'دانلود خروجی',
                        'target' => '_blank',
                    ),
                    'url'=>'Yii::app()->createUrl("financial/checkouts/download?id=".$data->id)',
                ),

            )
        ),
    ),
));

?>

<?
$this->renderPartial("_upload");
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
