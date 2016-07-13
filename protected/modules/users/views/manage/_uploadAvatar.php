<div class="modal fade" id="upload-avatar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">آپلود تصویر</h4>
            </div>
            <div class="modal-body">
                <p>
                    <?php
                    $options = array(
                        'folderPath' => 'upload/users/avatars/thumbnails_127x127',
                        'width' => '127',
                        'height' => '127',
                        'hash' => 'uniqueid',
                        'otherSizes' => array(
                            array('upload/users/avatars/thumbnails_72x72','72'),
                            array('upload/users/avatars/thumbnails_45x45','45')
                        ),
                        'afterCrop' => "js:function(imageName){
                            $('.big-user-icon').html('<img />');
                            $('.big-user-icon img').attr('src',createAbsoluteUrl('upload/users/avatars/thumbnails_127x127/'+imageName));
                            $('#upload-avatar').modal('hide');
                            $('#Users_avatar').val(imageName);
                        }",
                        'afterDelete'=> "js:function(){
                            $('#Users_avatar').val('');
                            $('.big-user-icon img').remove();
                            $('.big-user-icon').addClass('default-big-user');
                            $('.big-user-icon img').removeAttr('src');
                            //$('.image-crop-form').attr('style','display: none!important');
                            $('.big-user-icon').addClass('default-big-user');
                        }",
                        /*'afterCancel' => "js:function(){
                            //$('.image-crop-form').attr('style','display: none!important');
                        }",*/
                        /*'beforeLoad' => "js:function(){
                            //$('.image-crop-form').attr('style','display: block!important');
                            return true;
                        }",*/
                    );
                    if($isSetting)
                        $options["model_id"] = $model->id;

                    $this->widget('ext.iWebCrop.iWebCrop',array(
                        'model'=> $model,
                        'src' => $model->avatar,
                        'name'=> 'avatar',
                        'options' => $options
                    ));
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>
