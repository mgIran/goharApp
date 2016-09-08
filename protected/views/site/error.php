<?php
/* @var $this SiteController */
/* @var $error array */
?>
<div class="index panel panel-default rtl page-error col-lg-12 col-md-12 col-sm-12 col-xs-12" >
    <div class="error-code"><?php echo $code; ?></div>

    <div class="error-message">
    <?php
    echo CHtml::encode($message);
    ?>
    </div>
</div>