<?php

class CropDefaultController extends Controller
{
	public function filters()
	{

		return array(

			'accessControl', // perform access control for CRUD operations
			'ajaxOnly + cropTemp,crop,delete,cancel',

		);
	}

	public function accessRules()
	{
		return array(
            array('allow',  // allow all authenticated users
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                    'users'=>array('*'),
            )
        );

	}

    /*
     * temp crop image
     */
    public function actionCropTemp()
    {
        $error = "";
        $msg = "";
        if(!empty($_FILES['crop_image']['error']))
        {
            switch($_FILES['crop_image']['error'])
            {

                case '1':
                    $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                    break;
                case '2':
                    $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                    break;
                case '3':
                    $error = 'The uploaded file was only partially uploaded';
                    break;
                case '4':
                    $error = 'No file was uploaded.';
                    break;
                case '6':
                    $error = 'Missing a temporary folder';
                    break;
                case '7':
                    $error = 'Failed to write file to disk';
                    break;
                case '8':
                    $error = 'File upload stopped by extension';
                    break;
                case '999':
                default:
                    $error = 'No error code available';
            }
        }elseif(empty($_FILES['crop_image']['tmp_name']) || $_FILES['crop_image']['tmp_name'] == 'none')
        {
            $error = 'No file was uploaded..';
        }else
        {            
            if($_FILES['crop_image']['type']==="image/jpeg" || $_FILES['crop_image']['type']==="image/gif" || $_FILES['crop_image']['type']==="image/png" )
            {
                $newName = preg_replace('/[\s\/%*:|"<>?]+/', '_', $_FILES['crop_image']['name']);
                $uploadRoot = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'upload';
                if(!is_dir($uploadRoot))
                {
                    mkdir($uploadRoot);
                }

                $uploadDirectory = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'tempCrop';
                if(!is_dir($uploadDirectory))
                {
                    mkdir($uploadDirectory);
                }
                $uploadDirectory .= DIRECTORY_SEPARATOR;
                $tempName=$newName;
                $i=1;
                while(file_exists($uploadDirectory.$newName))
                {
                    $newName = $i."_".$tempName;
                    $i++;
                }
                $uploadResult = move_uploaded_file($_FILES['crop_image']['tmp_name'], $uploadDirectory.$newName);
                if($uploadResult)
                    $msg.= Yii::app()->baseUrl.'/upload/tempCrop/'.$newName;
                else
                    $msg.= '1';

                @unlink($_FILES['crop_image']);//for security reason, we force to remove all uploaded file
            }
            else
            {
                @unlink($_FILES['crop_image']);
                $msg .= '1';
            }
        }
        echo json_encode(array("error"=>$error,"msg"=>$msg));
    }

    public function actionCrop()
    {
        ini_set('memory_limit',-1);
        if(strlen($_POST['file_name']))
        {
            $imagePath=substr($_POST['file_name'],strlen(Yii::app()->baseUrl)+1);
            // simple image for resize images
            $simpleImage = new SimpleImage();
            $simpleImage->load($imagePath);

            // width and height of image in crop box
            //$width = intval($_POST['width']);
            //$height = intval($_POST['height']);
            $width = $simpleImage->getWidth();
            $height = $simpleImage->getHeight();

            // prefix and postfix in image name
            $prefix=(isset($_POST['prefix'])?$_POST['prefix']:"");
            $postfix=(isset($_POST['postfix'])?$_POST['postfix']:"");

            $cropBoxWidth = intval($_POST['crop_width']);
            $cropBoxHeight = intval($_POST['crop_height']);

            $differenceWidth = $width / $cropBoxWidth;
            $differenceHeight = $height / $cropBoxHeight;

            //resize image to crop size
            $simpleImage->resize($width,$height);

            //get image extension size
            $imageInfo=pathinfo($imagePath);
            $imageExtension=$imageInfo['extension'];
            $imageName=$imageInfo['filename'];


            // new unique name for image
            if(isset($_POST['hash']))
                switch($_POST['hash'])
                {
                    case "uniqueid":
                        $newName = uniqid();
                    break;
                    case "md5":
                        $newName = md5($imageName);
                    break;
                    case "sha1":
                        $newName = sha1($imageName);
                        break;
                    default:
                        $newName = $imageName;
                    break;
                }
            else
                $newName=$imageName;

            $baseName=$newName;
            //for save in database
            $dbImageName=$newName.".".$imageExtension;
            //merge prefix and postfix in imagename and image extension
            $newName=$prefix.$newName.$postfix.".".$imageExtension;

            $folderPath=$_POST['path'];
            //check "/" in start of path
            if(strpos($folderPath,"/")!=0)
                $folderPath='/'.$folderPath;
            //check "/" in end of path
            if(strrpos($folderPath,"/")!=strlen($folderPath)-1)
                $folderPath.='/';



            $src=Yii::getPathOfAlias('webroot').$folderPath;

            $this->makeDirOfPath($src);

            $imageSrc=$src.$newName;

            //copy($imagePath,$imageSrc);
            $simpleImage->save($imageSrc);

            //crop image
            $targetWidth = $_POST['target_width'];
            $targetHeight = $_POST['target_height'];

            $saveWidth = intval($targetWidth * $differenceWidth);
            $saveHeight = intval($targetHeight * $differenceHeight);
            $saveX = intval($_POST['x'] * $differenceWidth);
            $saveY = intval($_POST['y'] * $differenceHeight);
            $saveW = intval($_POST['w'] * $differenceWidth);
            $saveH = intval($_POST['h'] * $differenceHeight);

            $jpgQuality = $_POST['jpg_quality'];

            $imageInfo = getimagesize($imagePath);

            $imageType = $imageInfo[2];
            if(($imageType == IMAGETYPE_GIF) || ($imageType==IMAGETYPE_PNG))
            {
                $thumb = imagecreatetruecolor( $targetWidth,$targetHeight);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);

                $source = imagecreatefrompng($imageSrc);
                imagecopyresampled($thumb,$source,0,0,$saveX,$saveY,
                    $saveWidth,$saveHeight,$saveW,$saveH);

                imagepng($thumb,$imageSrc,9);
            }
            else
            {
                $img_r = imagecreatefromjpeg($imageSrc);
                $dst_r = imagecreatetruecolor($saveWidth,$saveHeight);
                imagecopyresampled($dst_r,$img_r,0,0,$saveX,$saveY,
                    $saveWidth,$saveHeight,$saveW,$saveH);
                imagejpeg($dst_r, $imageSrc, $jpgQuality);
            }
            @unlink($imagePath);
            $simpleImage->load($imageSrc);
            $simpleImage->resize($targetWidth,$targetHeight);
            $simpleImage->save($imageSrc);
            /*
             * make other sizes
             */
            // check if other size exists
            if(isset($_POST['other']))
            {
                $simpleImage->load($imageSrc);
                foreach($_POST['other'] as $newSize)
                {
                    $newSize=explode(",",$newSize);
                    //check if size and path not set continue
                    if(!isset($newSize[0]) || !isset($newSize[1]))
                        continue;
                    $simpleImage->resizeToWidth($newSize[1]);
                    $folderPath=$newSize[0];

                    //check "/" in start of path
                    if(strpos($folderPath,"/")!=0)
                        $folderPath='/'.$folderPath;

                    //check "/" in end of path
                    if(strrpos($folderPath,"/")!=strlen($folderPath)-1)
                        $folderPath.='/';

                    $folderPath=Yii::getPathOfAlias('webroot').$folderPath;

                    $this->makeDirOfPath($folderPath);
                    //check directory is make?

                    //save new size
                    $simpleImage->save($folderPath.(isset($newSize[2])?$newSize[2]:'').$baseName.(isset($newSize[3])?$newSize[3]:'').".".$imageExtension);
                }
            }

            //update table in database
            if(isset($_POST['model_name']) and isset($_POST['model_id']) and isset($_POST['field_name']))
            {
                if(@!class_exists($_POST['model_name']))
                {
                    preg_match_all('/[A-Z][a-z]+/', $_POST['model_name'], $matches);
                    $moduleName = strtolower($matches[0][0]);

                    Yii::import($moduleName.'.models.*');
                }

                if($_POST['model_id'] == 'create')
                    echo json_encode(array("success"=>true,"image"=>$dbImageName));
                elseif($_POST['model_name']::model()->updateByPk($_POST['model_id'],array($_POST['field_name']=>$dbImageName)))
                    echo json_encode(array("success"=>true,"image"=>$dbImageName));
                else
                    echo json_encode(array("error"=>"خطا در حین انجام عملیات"));
                exit;
            }
            else
            {
                echo json_encode(array("success"=>true));
                exit;
            }

        }
        echo json_encode(array("error"=>"خطا در حین انجام عملیات"));
    }

    public function actionDelete()
    {
        if(isset($_POST['path']) and isset($_POST['image_name']))
        {
            // prefix and postfix in image name
            $prefix=(isset($_POST['prefix'])?$_POST['prefix']:"");
            $postfix=(isset($_POST['postfix'])?$_POST['postfix']:"");

            //get image extension size
            $dotPosition=strrpos($_POST['image_name'],".");
            $imageExtension=substr($_POST['image_name'],$dotPosition+1);
            $imageName=substr($_POST['image_name'],0,$dotPosition);

            $newName=$imageName;

            $baseName=$newName;
            //for save in database
            $dbImageName=$newName.".".$imageExtension;
            //merge prefix and postfix in imagename and image extension
            $newName=$prefix.$newName.$postfix.".".$imageExtension;

            $folderPath=$_POST['path'];
            //check "/" in start of path
            if(strpos($folderPath,"/")!=0)
                $folderPath='/'.$folderPath;
            //check "/" in end of path
            if(strrpos($folderPath,"/")!=strlen($folderPath)-1)
                $folderPath.='/';
            $src=Yii::getPathOfAlias('webroot').$folderPath;

            $imageSrc=$src.$newName;

            /*
             * make other sizes
             */
            // check if other size exists
            if(isset($_POST['other']))
            {
                foreach($_POST['other'] as $newSize)
                {
                    $newSize=explode(",",$newSize);
                    //check if size and path not set continue
                    if(!isset($newSize[0]) || !isset($newSize[1]))
                        continue;
                    $folderPath=$newSize[0];
                    //check "/" in start of path
                    if(strpos($folderPath,"/")!=0)
                        $folderPath='/'.$folderPath;

                    //check "/" in end of path
                    if(strrpos($folderPath,"/")!=strlen($folderPath)-1)
                        $folderPath.='/';

                    $folderPath=Yii::getPathOfAlias('webroot').$folderPath;
                    //delete image(images)
                    @unlink($folderPath.(isset($newSize[2])?$newSize[2]:'').$baseName.(isset($newSize[3])?$newSize[3]:'').".".$imageExtension);
                }
            }

            //delete image
            @unlink($imageSrc);

            //update table in database
            if(isset($_POST['model_name']) and isset($_POST['model_id']) and isset($_POST['field_name']))
            {
                if(@!class_exists($_POST['model_name']))
                {
                    preg_match_all('/[A-Z][a-z]+/', $_POST['model_name'], $matches);
                    $moduleName = strtolower($matches[0][0]);

                    Yii::import($moduleName.'.models.*');
                }

                if($_POST['model_id'] == 'create')
                    echo json_encode(array("success"=>true));
                elseif($_POST['model_name']::model()->updateByPk($_POST['model_id'],array($_POST['field_name']=>'')))
                    echo json_encode(array("success"=>true));
                else
                    echo json_encode(array("error"=>"خطا در حین انجام عملیات"));
                exit;
            }
            else
            {
                echo json_encode(array("success"=>true));
                exit;
            }
        }
        echo json_encode(array("error"=>"خطا در حین انجام عملیات"));
    }
    public function actionCancel()
    {
        if(isset($_POST['file_name']))
        {
            $imagePath=substr($_POST['file_name'],strlen(Yii::app()->baseUrl)+1);
            @unlink($imagePath);
            echo json_encode(array("success"=>true));
            exit;
        }
        echo json_encode(array("error"=>"خطا در حین انجام عملیات"));
    }

    private function makeDirOfPath($src){
        $tempSrc = $src;
        $directoriesArray = array();
        while($tempSrc != Yii::getPathOfAlias('webroot'))
        {
            $directoriesArray[] = $tempSrc;
            $tempSrc = dirname($tempSrc);
        }
        $directoriesArray = array_reverse($directoriesArray);
        foreach($directoriesArray as $currentDirectory)
        {

            //check directory is make?
            if(!is_dir($currentDirectory))
            {
                mkdir($currentDirectory);
            }
        }
        return true;
    }
}

