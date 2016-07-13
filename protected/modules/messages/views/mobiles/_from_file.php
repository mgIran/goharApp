<?
$model = new MessagesMobilesBank;
$categories = MessagesMobilesBankCategories::model()->findAll();

$temp = array();
foreach($categories as $category){
    $temp[$category->id] = $category->getFullName();
}
$categories = $temp;

Yii::app()->clientScript->registerScript('uploadFile',"
    setCookie('bankValue', 0, 1);
    $('#MessagesMobilesBank_cat_id li').on('click',function(){
        setCookie('bankValue', $(this).data('value'), 1);
    });
    function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = \"expires=\"+d.toUTCString();
            document.cookie = cname + \"=\" + cvalue + \"; \" + expires;
        }
");
Yii::app()->clientScript->registerCss("uploadFile","
    .iwiu-image-gallery-container{
        display: none !important;
    }
    .iwiu-result-message{
        position:relative;
    }
");
?>
<div class="modal fade" id="upload-txt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 350px;">
        <div class="modal-content col-md-12">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">ثبت شماره موبایل به بانک از طریق آپلود فایل</h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'id'=>'messages-mobiles-bank-form',
                        'enableAjaxValidation'=>true,
                    )); ?>
                    <?php echo $form->errorSummary($model); ?>


                    <div class="row">
                        <div class="col-md-4 pull-right">
                            <?php echo $form->labelEx($model,'cat_id'); ?>
                        </div>
                        <div class="col-md-8 pull-right">
                            <?php $this->widget('ext.iWebDropDown.iWebDropDown', array(

                                'label'=>'انتخاب کنید',
                                'name'=>'MessagesMobilesBank[cat_id]',
                                'id'=>'MessagesMobilesBank_cat_id',
                                'list'=> $categories,
                                'icon' => '<div class="glyphicon glyphicon-chevron-down"></div>',
                                'allOption'=>true,
                            )); ?>
                        </div>
                    </div>
                    <div class="row">
                        <?

                        $this->widget('application.extensions.iWebUploader.iWebUploader',
                            array(
                                'type' => 'image',
                                'upload' => array(
                                    'url' => 'upload',
                                    'allowedFileTypes' => array('text/plain'),
                                    'allowedFileExtensions' => array('.txt'),
                                    'queueFiles' => 1
                                ),
                                'delete' => array(
                                    'url' => '#'
                                )
                            )
                        );
                        ?>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
