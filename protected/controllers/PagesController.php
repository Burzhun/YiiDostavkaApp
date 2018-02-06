<?php

class PagesController extends Controller
{

    public function actionView($alias)
    {
        // $model = Pages::model()->findByPk($id);
        $model = $this->loadModel($alias, $this->domain->id);
        /*$this->title = $model['name']." - ".$this->title;*/

        /*$this->title = $model->name." / Доставка 05";
        $this->description = $model->shorttext.". Доставка 05";*/

        $this->title = str_replace('{name}', $model->name, Config::getStaticTitle($this->domain->id));
        $this->description = str_replace('{shorttext}', $model->shorttext, Config::getStaticDescription($this->domain->id));

        $this->render('view', array(
            'model' => $model,
        ));
    }

    public function loadModel($pageUri, $domain)
    {
        $model = Pages::model()->find('uri=:uri AND domain=:domain', array(':uri' => $pageUri, ':domain' => $domain));
        if ($model === null)

            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
