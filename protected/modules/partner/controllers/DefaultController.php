<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->redirect(array('/partner/info'));
    }
    public function actionUpdateAjax()
    {
        $es = new EditableSaver('Goods');
        $es->update();
    }
}