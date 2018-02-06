<?php

class InfoController extends Controller
{
    private $_model;

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array(User::PARTNER),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        //
        $model = $this->loadPartner();

        $breadcrumbs = array($model->name => array('/partner/info'), 'Информация');

        $h1 = $model->name;

        //
        $specs = array();
        $dirs = array();
        foreach ($model->specialization as $s) {
            $specs[] = $s->id;
            $dirs[$s->direction_id] = $s->direction_id;
        }

        if (isset($_POST['Partner'])) {
            $old_img = $model->img;
            $model->attributes = $_POST['Partner'];

            $model->tname = $this->translitIt($model->name);

            $model->img = $old_img;
            //проверяем наличие выделенных специализаций
            if (!empty($_POST['Spec'])) {
                //если специализации выделены
                $temp_arr = array();
                //сначала создаем списак выбранных специализаций
                foreach ($_POST['Spec'] as $key => $s) {
                    if ($s) $temp_arr[] = $key;
                }
                //затем используя хитровыйбанный бехавиор, сохраняем данные
                $model->specialization = $temp_arr;
                $model->saveWithRelated('specialization');
            }

            $img_property = CUploadedFile::getInstance($model, 'img');

            if ($model->save()) {
                if (!empty($_FILES['Partner']['name']['img']))
                    ZHtml::imgSave($model, $img_property, 'partner', 500, 500, 250, 250);

                //Action::addNewAction('Регистрация', 0, $model->id, 'Администратор отредактировал пользователя - '.$model->name.' ID'.$model->id);
                $this->redirect(array('/partner/info'));
            }
        }

        $this->render('index', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'specs' => $specs,
        ));
    }


    public function actionDeleteImage()
    {
        $model = $this->loadPartner();
        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/partner' . DS . $model->img);
        $model->img = "";
        $model->save();
        $this->redirect(array('/partner/info'));
    }


    public function loadPartner()
    {
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = User::model()->findByPk(Yii::app()->user->id)->partner;//Yii::app()->controller->module->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }
}