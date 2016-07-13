<div class="row">
    <div class="dynamic-fields">
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-8 pull-right">
            <div class="dynamic-fields-row">
                <div class="col-md-5 pull-right">
                    عنوان گزینه ها
                </div>
                <div class="col-md-1 pull-right"></div>
                <div class="col-md-5 pull-right">
                    کلیدواژه ها
                </div>
                <div class="col-md-1 pull-right"></div>
            </div>

            <?if(!isset($_POST['SpecialServices']['fields'])):?>
                <div class="dynamic-fields-row">
                    <div class="col-md-5 pull-right">
                        <input name="SpecialServices[fields][0][title]" type="text" class="form-control">
                    </div>
                    <div class="col-md-1 pull-right"></div>
                    <div class="col-md-5 pull-right">
                        <input name="SpecialServices[fields][0][value]" type="text" class="form-control keywords-field">
                    </div>
                    <div class="col-md-1 pull-right action-links">
                        <a class="remove-link" href="#">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </div>
                </div>
            <?else:?>
                <?foreach($_POST['SpecialServices']['fields'] as $key=>$field):?>
                    <div class="dynamic-fields-row">
                        <div class="col-md-5 pull-right">
                            <input name="SpecialServices[fields][<?=$key?>][title]" value="<?=$field['title']?>" type="text" class="form-control">
                        </div>
                        <div class="col-md-1 pull-right"></div>
                        <div class="col-md-5 pull-right">
                            <input name="SpecialServices[fields][<?=$key?>][value]" value="<?=$field['value']?>" type="text" class="form-control keywords-field">
                        </div>
                        <div class="col-md-1 pull-right action-links">
                            <a class="remove-link" href="#">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </div>
                <?endforeach;?>
            <?endif;?>
        </div>

        <div class="col-md-4 pull-right"></div>
        <div class="col-md-1 pull-right">
            <a class="add-link" href="#">
                <i class="fa fa-plus"></i>
            </a>
        </div>
        <div class="col-md-4 pull-right"></div>
        <div class="col-md-4 pull-right" style="font-size: 11px;line-height: 38px">* کلیدواژه ها را با "،" یا "," از یکدیگر جدا نمایید.</div>
    </div>
    <div class="col-md-12">
        <?php echo $form->error($model,'fields'); ?>
    </div>
</div>
<?

Yii::app()->clientScript->registerScript('fields',"

    $('#special-services-form').on(\"keyup keypress\", function(e) {
      var code = e.keyCode || e.which;
      if (code  == 13) {
        e.preventDefault();
        return false;
      }
    });

    num = $('.dynamic-fields-row').length - 1;
    $('body').on('click', '.add-link', function(){
        $('.dynamic-fields .dynamic-fields-row:last').after(
            '<div class=\"dynamic-fields-row\">' +
                        '<div class=\"col-md-5 pull-right\">'+
                            '<input name=\"SpecialServices[fields][' + num + '][title]\" type=\"text\" class=\"form-control\">'+
                        '</div>'+
                        '<div class=\"col-md-1 pull-right\"></div>'+
                        '<div class=\"col-md-5 pull-right\">'+
                            '<input name=\"SpecialServices[fields][1][value]\" type=\"text\" class=\"form-control keywords-field\">'+
                        '</div>'+
                        '<div class=\"col-md-1 pull-right action-links\">'+
                            '<a class=\"remove-link\" href=\"#\"> ' +
                                '<i class=\"fa fa-trash-o\"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>'
        );
        num++;
        $('.dynamic-fields .dynamic-fields-row:last input:first').focus();
        return false;
    });

    $('body').on('click', '.remove-link', function(){
        $(this).parents('.dynamic-fields-row').remove();
        return false;
    });


",CClientScript::POS_END);
?>