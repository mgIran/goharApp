<?
$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery.tagit.css')?>
<? $cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/tagit.ui-zendesk.css')?>
<? $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/tag-it.min.js',CClientScript::POS_END)?>

<script>
    $(function(){
        $('#emails-tags').tagit({
            placeholderText:'اضافه کردن ایمیل بصورت دستی' ,
            fieldName: 'tags[]'/*,
            beforeTagAdded: function(evt, ui) {
                if(!IsEmail(ui.tagLabel))
                    return false;
            }*/
        });
    });
</script>

<div class="row">
    <ul id="emails-tags" style="margin: 10px 0;width: 98%;"></ul>
</div>
<div class="row">
    <input type="text" style="width: 98%" name="title" placeholder="عنوان خبرنامه" >
</div>
<div class="row">
    <textarea style="width: 98%;margin-bottom: 10px" class="ckeditor" name="text"></textarea>
</div>
<div class="row">
    <button type="submit" class="btn btn-success">ارسال</button>
</div>





