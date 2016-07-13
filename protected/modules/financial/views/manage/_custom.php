<?
$B = SiteOptions::getOption(SiteOptions::B);
?>
<div class="plans-labels">
	<div class="col-md-12">
		<div class="factor-title">
			<span>
			تغییرات سفارشی
			</span>
		</div>
	</div>
	<div class="col-md-12">
		<div class=" factor-sections">
			<div class="row">
				<div class="col-md-4">
					<b>
						بدهی آنلاین اعتبارات نقدی کاربران :
					</b>
				</div>
				<div class="col-md-6">
					<div class="col-md-10 direct-ltr">
						<?=number_format($B)?>
					</div>
					<div class="col-md-2">
						تومان
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4">
					<b style="padding-top: 6px;display: block">
						ایجاد تغییرات سفارشی (دستی) :
					</b>
				</div>
				<div class="col-md-6">
					<div class="col-md-8">
						<?
						$this->widget("ext.iWebFunctions.iWebFunctions");
						echo CHtml::textField('custom_change','',array(
							'class' => 'form-control direct-ltr'
						));?>
					</div>
					<div class="col-md-2">
						<?
						echo CHtml::button('+',array(
							'class' => 'btn btn-default pull-left',
							'id' => 'custom_sign',
							'style' => 'font-size: 17px;font-weight: bold;padding: 4px 10px;text-align: center;width: 35px;'
						));
						?>
					</div>
					<div class="unity col-md-2">
						تومان
					</div>
				</div>
				<div class="col-md-2">
					<?
					echo CHtml::ajaxButton('ثبت','custom',array(
						'data' => 'js:{name:"debt",value:$("#custom_sign").val()+$("#custom_change").val()}',
						'success' => 'js:function(data){
							$("#custom-box").html(data);
						}',

					),array(
						'class' => 'form-control btn btn-default submit',
						'id' => 'custom-submit'
					));
					?>
				</div>
			</div>
		</div>
		<div class="factor-final"></div>
	</div>
</div>
<?
Yii::app()->clientScript->registerScript("customChange",'
	$(document).on("keyup","#custom_change",function(e){
		$(this).val(iWebFunctions.splitNumber($(this).val()));
		if(e.keyCode == 13)
			$("#custom-submit").trigger("click");
	});
	$(document).on("click","#custom_sign",function(e){
		$(this).val(($(this).val() == "+")?"-":"+");
		$("#custom_change").focus();
	});
');
?>