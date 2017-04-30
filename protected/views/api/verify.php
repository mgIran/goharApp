<?php
if($status == AppTransactions::TRANSACTION_UNPAID):
?>
<div class="row-fluid">
	<div class="span12">
		<div class="alert alert-warning text-center">
			<?= $message ?>
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
				<?= $message ?>
				<br>
			</div>
		</div>
	</div>
<?
endif;
