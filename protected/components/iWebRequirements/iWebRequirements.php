<?php

class iWebRequirements extends CWidget
{
    private $publishedAssetsPath;

    public function __construct()
    {
        $this->init();
    }
	public function init()
	{
        if(strpos($_SERVER['SERVER_PROTOCOL'], 'HTTPS'))
            $protocol = 'https';
        else
            $protocol = 'http';
        $url = dirname((($_SERVER['REQUEST_URI'])));
        if($url === '/')
            $url = '';
        $js = "var scenario = null;function getBaseUrl(){return baseUrl;}function createAbsoluteUrl(path){return '".
            Yii::app()->getBaseUrl(true).'/'.
            "'+path;}function setScenario(scenarioName){scenario = scenarioName;}function getScenario(){return scenario;}";

        $js .= "function getControllerID(){return '".$this->controller->id."';}";

        Yii::app()->getClientScript()->registerScript('iWebRequirements',$js,CClientScript::POS_BEGIN);
	}
}

?>
