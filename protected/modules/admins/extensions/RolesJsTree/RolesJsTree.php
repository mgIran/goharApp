<?php
class RolesJsTree extends CInputWidget
{
    protected $publishedAssetsPath;
    public $classes = NULL;
    public $name = NULL;
    public $currentPermissions = NULL;

	public function init()
	{
        $this->currentPermissions = json_decode($this->currentPermissions);

        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/jsTree.min.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/bootstrap-theme.min.css');
        Yii::app()->getClientScript()->registerCssFile($this->getAssetsUrl().'/css/jsTree.style.css');
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/jsTree.min.js');
        Yii::app()->getClientScript()->registerScriptFile($this->getAssetsUrl().'/js/jsTree.script.js');
		self::renderView();
	}

    public function getAssetsUrl()
    {
        if(!isset($this->publishedAssetsPath))
        {
            $assetsSourcePath = Yii::getPathOfAlias('application.modules.admins.extensions.RolesJsTree.assets');

            $publishedAssetsPath = Yii::app()->assetManager->publish($assetsSourcePath, false, -1);

            return $this->publishedAssetsPath = $publishedAssetsPath;
        }
        else return $this->publishedAssetsPath;
    }

	private function renderView(){
		$this->render('view');
	}

    protected function getListItem($module,$action){
        $temp = "";
        $modulePermissions = array();
        if(isset($this->currentPermissions->$module))
            $modulePermissions = $this->currentPermissions->$module;


        foreach($action as $name=>$permission)
        {

            if(is_array($permission) && $permission['type'] == 'admin')
            {

                $temp .=
                '<li id="'.$module."$$".$name.'" '.(in_array($name,$modulePermissions)?'data-jstree=\'{"selected":true}\'':'').'>
                    <span class="js-tree-title">'.$permission['title'].'</span>
                </li>';
            }
        }
        if($temp != "")
            $temp = '<li id="'.$module.'"><span class="js-tree-title">'.$action['title'].'</span><ul>'.$temp.'</ul></li>';

        return $temp;
    }
}

?>
