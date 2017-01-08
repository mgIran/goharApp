<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property SearchForm $searchModel
 */
class Controller extends ApiBaseController
{
    /**
     * @var string the default layout for the controller views. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $town = null;
    public $place = null;
    public $description;
    public $keywords;

    public $siteName;
    public $pageTitle;

    public $searchModel;
    public $sideRender = null;
    public $message = null;
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&views=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function init()
    {
        Yii::app()->clientScript->registerScript('js-requirement', '
            var baseUrl = "' . Yii::app()->getBaseUrl(true) . '";
        ', CClientScript::POS_HEAD);

        $this->siteName = Yii::app()->name;

        // set default meta tag values
        parent::init();
    }

    /**
     * @param $model
     * @param bool $html
     * @return string
     */
    public static function implodeErrors($model, $html=false)
    {
        $errors = $html?'':array();
        foreach($model->getErrors() as $key => $err){
            if($html)
                $errors .= implode('<br>' ,$err) . '<br>';
            else
                $errors[$key] = $err[0];
        }
        return $errors;
    }

    public static function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Converts latin numbers to farsi script
     */
    public static function parseNumbers($matches)
    {
        $farsi_array = array('Û°', 'Û±', 'Û²', 'Û³', 'Û´', 'Ûµ', 'Û¶', 'Û·', 'Û¸', 'Û¹');
        $english_array = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        return str_replace($english_array, $farsi_array, $matches);
    }

    public static function fileSize($file)
    {
        $size = filesize($file);
        if ($size < 1024)
            return $size . ' Byte';
        elseif ($size < 1024 * 1024) {
            $size = (float)$size / 1024;
            return number_format($size, 1) . ' KB';
        } elseif ($size < 1024 * 1024 * 1024) {
            $size = (float)$size / (1024 * 1024);
            return number_format($size, 1) . ' MB';
        } else {
            $size = (float)$size / (1024 * 1024 * 1024);
            return number_format($size, 1) . ' GB';
        }
    }

    public static function createAdminMenu()
    {
        if (!Yii::app()->user->isGuest && Yii::app()->user->type != 'user')
            return array(
                array(
                    'label' => 'پیشخوان',
                    'url' => array('/admins/login/dashboard')
                ),
                array(
                    'label' => 'مراسمات <span class="caret"></span>',
                    'url' => '#',
                    'itemOptions' => array('class' => 'dropdown', 'tabindex' => "-1"),
                    'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"),
                    'items' => array(
                        array('label' => 'لیست مراسمات', 'url' => Yii::app()->createUrl('/events/manage/admin')),
                        array('label' => 'ثبت مراسم', 'url' => Yii::app()->createUrl('/events/manage/create')),
                    )
                ),
                array(
                    'label' => 'اطلاعیه <span class="caret"></span>',
                    'url' => '#',
                    'itemOptions' => array('class' => 'dropdown', 'tabindex' => "-1"),
                    'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"),
                    'items' => array(
                        array('label' => 'لیست اطلاعیه ها', 'url' => Yii::app()->createUrl('/notifications/manage/admin')),
                        array('label' => 'ارسال اطلاعیه', 'url' => Yii::app()->createUrl('/notifications/manage/create')),
                    )
                ),
                array(
                    'label' => 'پشتیبانی',
                    'url' => Yii::app()->createUrl('/tickets/manage/admin')
                ),
                array(
                    'label' => 'تنظیمات',
                    'url' => Yii::app()->createUrl('/setting/manage/update')
                ),
                array(
                    'label' => 'خروج',
                    'url' => array('/admins/login/logout'),
                    'visible' => !Yii::app()->user->isGuest
                ),
            );
        else
            return array();
    }
}