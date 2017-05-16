<?php
if($status == AppTransactions::TRANSACTION_UNPAID):
?>

<script language="javascript" type="text/javascript">
	function postRefId (refIdValue) {
		var form = document.createElement("form");
		form.setAttribute("method", "POST");
		form.setAttribute("action", "https://bpm.shaparak.ir/pgwchannel/startpay.mellat");
		form.setAttribute("target", "_self");
		var hiddenField = document.createElement("input");
		hiddenField.setAttribute("name", "RefId");
		hiddenField.setAttribute("value", refIdValue);
		form.appendChild(hiddenField);

		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	}
	postRefId('<?php echo $ReferenceId; ?>');
</script>
<div class="row-fluid">
	<div class="span12">
		<div class="alert alert-info text-center">
			<strong><?php echo Yii::t('rezvan', 'Transfer to the Portal Bank'); ?></strong>

			<?php echo Yii::t('rezvan', 'You will be transferred to the bank site...'); ?>
			<br>
		</div>
	</div>
</div>
<?php
elseif($status == AppTransactions::TRANSACTION_PAID):
?>

	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-success text-center">
				تراکنش موردنظر با موفقیت پرداخت گردیده است.
				<br>
			</div>
		</div>
	</div>
<?
elseif($status == AppTransactions::TRANSACTION_DELETED):
?>

	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-danger text-center">
				تراکنش موردنظر با از سیستم حذف گردیده و امکان پرداخت آن میسر نمی باشد.
				<br>
			</div>
		</div>
	</div>
<?
endif;
