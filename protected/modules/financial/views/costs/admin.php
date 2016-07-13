<?php
/* @var $this CostsController */
/* @var $model Costs */

$this->breadcrumbs=array(
	'Site Costs'=>array('admin'),
	'نمایش',
);

$this->menu=array(
	array('label'=>'ایجاد هزینه', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#costs-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?=static::$actionsArray[$this->action->id]['title']?></h1>

<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'costs-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'columns'=>array(
			'id',
			'title',
			array(
				'name' => 'status',
				'value' => 'Costs::$statusList[$data->status]',
				'filter' => Costs::$statusList
			),
			array(
				'name' => 'price',
				'value' => 'number_format($data->price)',
				'filter' => false
			),
			array(
				'name' => 'each_installment',
				'value' => 'number_format($data->each_installment)',
				'filter' => false
			),
			array(
				'name' => 'last_installment_price',
				'value' => 'number_format($data->last_installment_price)',
				'filter' => false
			),
			array(
				'name' => 'remain_installment_num',
				'value' => 'number_format($data->remain_installment_num)',
				'filter' => false
			),
			array(
				'name' => 'debt_sum',
				'value' => 'number_format($data->debt_sum)',
				'filter' => false
			),
			array(
				'name' => 'start_date',
				'value' => 'Yii::app()->jdate->date("Y/m/d",$data->start_date)',
				'filter' => false
			),
			array(
				'name' => 'last_installment',
				'value' => 'Yii::app()->jdate->date("Y/m/d",$data->last_installment)',
				'filter' => false
			),
			array(
				'name' => 'last_pay',
				'value' => '(is_null($data->last_pay))?"-":Yii::app()->jdate->date("Y/m/d",$data->last_pay)',
				'filter' => false
			),
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}{delete}{activate}',
				'buttons' => array(
					'activate' => array(
						'visible'=>'($data->status == Costs::STATUS_DISABLE)',
						'label'=>'',
						'options'=> array(
							'class' => 'fa fa-check-circle-o',
							'title' => '',
							'target' => '_blank',
						),
						'url'=>'Yii::app()->createAbsoluteUrl("financial/costs/activate?id=").$data->id',
						'click'=>"function(){
							$.fn.yiiGridView.update('costs-grid', {
								type:'POST',
								url:$(this).attr('href'),
								success:function(data) {
									  $.fn.yiiGridView.update('costs-grid');
								}
							})
							return false;
						  }
						",
					),
				)

			),
		),
));
Yii::app()->clientScript->registerCss("gridViewButton","
.grid-view .button-column .fa {
    color: #7e569f;
    font-size: 18px;
    padding: 1px 5px;
}
");
?>
<div class="row">
	<?$this->renderPartial('_reports')?>
	<div id="custom-box" class="col-md-6">
		<?$this->renderPartial('_custom')?>
	</div>
</div>
