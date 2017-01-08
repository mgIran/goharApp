<?php

class iWebFunctions extends CInputWidget
{
    public $publishedAssetsPath;
	public function init()
	{
        if(Yii::getPathOfAlias('iWebFunctions') === false) Yii::setPathOfAlias('iWebFunctions', realpath(dirname(__FILE__) . '/..'));
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile($this->getAssetsUrl().'/js/iWebFunctions.js');
	}

    public function getAssetsUrl()
    {
        if(!isset($this->publishedAssetsPath))
        {
            $assetsSourcePath = Yii::getPathOfAlias('ext.iWebFunctions.assets');

            $publishedAssetsPath = Yii::app()->assetManager->publish($assetsSourcePath, false, -1);

            return $this->publishedAssetsPath = $publishedAssetsPath;
        }
        else return $this->publishedAssetsPath;
    }

}
?>
