<?
$labels = $model->attributeLabels();
$array = array('personal_image','national_card_front','national_card_rear','birth_certificate_first','business_license','activity_permission');
foreach($array as $item):?>
    <div class="modal fade" id="upload-<?=$item?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">آپلود <?=$labels[$item]?></h4>
                </div>
                <div class="modal-body">
                    <p>
                        <?php
                        $options = array(
                            'folderPath' => 'upload/users/'.$model->id.'/'.$item,
                            'width' => '120',
                            'height' => '160',
                            'hash' => 'uniqueid',
                            'afterCrop' => "js:function(imageName){
                                $('#upload-$item').modal('hide');
                                element = $('a[data-target=\"#upload-$item\"]');
                                element.removeClass('col-md-12').addClass('col-md-11');
                                element.removeClass('btn-primary').addClass('btn-success');
                                $('<span class=\"btn btn-success col-md-1\">✔</span>').insertBefore(element);
                            }",
                            'afterDelete' => "js:function(){
                                element = $('a[data-target=\"#upload-$item\"]');
                                element.removeClass('col-md-11').addClass('col-md-12');
                                element.prev('.btn.btn-success').remove();
                                element.removeClass('btn-success').addClass('btn-primary');
                            }",
                            //'model_id' => $model->id,
                        );

                        $this->widget('ext.iWebCrop.iWebCrop',array(
                            'model'=> $model,
                            'src' => $model->$item,
                            'name'=> $item,
                            'options' => $options,
                            'id' => $item . '-widget',
                        ));
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?endforeach;?>