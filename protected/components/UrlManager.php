<?
class UrlManager extends CUrlManager{
    public function createUrl($route, $params=array(), $ampersand='&')
    {
        //if (empty($params['language'])&& Yii::app()->language !== 'ru') {

    	//if ((empty($params['language'])) && (strripos($route, 'admin/')!==false)) {
        
    	/*if (empty($params['language'])) {
            $params['language'] = Yii::app()->language;
        }*/
        return parent::createUrl($route, $params, $ampersand);
    }
}