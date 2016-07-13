<div class="modal fade" id="other-legal-documents" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 1372px">
        <div class="modal-content col-md-12">
            <div class="modal-header">
                <button type="button" class="close pull-left" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">آپلود مدارک حقوقی بیشتر</h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    <div class="row">
                        <?
                        if(Yii::app()->user->type == 'user')
                            $userId = Yii::app()->user->userID;
                        elseif(Yii::app()->user->type == 'admin')
                            $userId = $_GET['id'];

                        $userUrl = "";
                        if($this->id=='manage' AND $this->action->id == 'update' AND isset($_GET['id'])){
                            $userUrl = "?user_id=".$_GET['id'];
                        }

                        $filePath = 'upload/users/legal_documents/'.$userId.'/other/';
                        $DbImages = json_decode($model->other_legal_documents,TRUE);
                        $images = array();
                        if(!is_null($DbImages))
                            foreach ($DbImages as $image) {
                                $images[] = array(
                                    'id' => $image['id'],
                                    'title' => $image['title'],
                                    'img' => Yii::app()->CreateAbsoluteUrl($filePath . $image['file']),
                                    'date' => ''
                                );
                            }

                        $this->widget('application.extensions.iWebUploader.iWebUploader',
                            array(
                                'type' => 'image',
                                'upload' => array(
                                    'url' => Yii::app()->createAbsoluteUrl('/users/account/uploadOther'.$userUrl),
                                    //'allowedFileTypes' => array('application/pdf','image/jpeg', 'image/png', 'image/gif'),
                                    //'allowedFileExtensions' => array('.pdf','.jpg', '.jpeg', '.png', '.gif'),
                                    'allowedFileTypes' => array('image/jpeg', 'image/png', 'image/gif'),
                                    'allowedFileExtensions' => array('.jpg', '.jpeg', '.png', '.gif'),
                                    'queueFiles' => 5,
                                    'maxFiles' => 5,
                                    'maxFileSize' => 20,
                                ),
                                'uploadedFiles' => $images,
                                'defaultTitle' => 'مدرک حقوقی',
                                'delete' => array(
                                    'url' => Yii::app()->createAbsoluteUrl('/users/account/deleteOther'.$userUrl),
                                ),
                                'changeTitle' => array(
                                    'url' => Yii::app()->createAbsoluteUrl('/users/account/changeTitleOther'.$userUrl),
                                ),
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
