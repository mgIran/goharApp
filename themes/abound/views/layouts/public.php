<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?= $this->siteName.(!empty($this->pageTitle)?' - '.$this->pageTitle:'') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ad ,tablo ,تابلو ,آگهی , دیوار ، شیپور">
    <meta name="author" content="App Mobasheri">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<?php
	  $baseUrl = Yii::app()->theme->baseUrl; 
	  $cs = Yii::app()->getClientScript();
	  Yii::app()->clientScript->registerCoreScript('jquery');
	?>
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/css/fontiran.css">
	<?php
	  $cs->registerCssFile($baseUrl.'/css/bootstrap.min.css');
      $cs->registerCssFile($baseUrl.'/css/bootstrap-reset.css');
	  $cs->registerCssFile($baseUrl.'/css/bootstrap-responsive.min.css');
      $cs->registerCssFile($baseUrl.'/css/persian-datepicker-0.4.5.min.css');
      $cs->registerCssFile($baseUrl.'/css/persian-datepicker-custom.css');
	  $cs->registerCssFile($baseUrl.'/css/abound.css');
      $cs->registerCssFile($baseUrl.'/css/rtl.css');
	  $cs->registerCssFile($baseUrl.'/css/style-blue.css');
      $cs->registerCssFile($baseUrl.'/css/font-awesome.css');
      $cs->registerCoreScript('jquery.ui');
      $cs->registerScriptFile($baseUrl.'/js/persian-datepicker-0.4.5.min.js');
      $cs->registerScriptFile($baseUrl.'/js/persian-date.js');
	  $cs->registerScriptFile($baseUrl.'/js/bootstrap.min.js');
	  $cs->registerScriptFile($baseUrl.'/js/jquery.form.js');
	?>
  </head>

<body>
    <section class="container">
        <div class="container-fluid">
            <?php echo $content; ?>
        </div>
    </section>
</body>
</html>