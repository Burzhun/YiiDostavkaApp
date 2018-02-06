<?php

class BannersController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }


    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('@'),
                'expression' => 'User::hasStatAccess()'
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        $model = new Banner('search');
        $model->unsetAttributes();
        if (isset($_GET['Banner']))
            $model->attributes = $_GET['Banner'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = Banner::model()->findByPk($id);

        $breadcrumbs = array('Баннеры' => array('/admin/banners'), 'Редактирование ');
        //Заголовок страницы
        $h1 = ' Редактирование';
        //Если форма заполнена
        if (isset($_POST['Banner'])) {
            //Записываем все находившиеся в форме данные, в модель
            $old_img = $model->image;
            $model->attributes = $_POST['Banner'];
            $model->image = CUploadedFile::getInstance($model, 'image');
            $newImage = true;
            if (!$model->image) {
                $model->image = $old_img;
                $newImage = false;

            }
            if ($model->save()) {
                if ($model->image && $newImage) {
                    $model->image->saveAs('themes/mobile/img/banners/' . $model->id . '.jpg');
                    $image = Yii::app()->image->load('themes/mobile/img/banners/' . $model->id . '.jpg');
                    $image->resize2();
                    $image->save();

                    $model->image = $model->id . '.jpg';
                    $model->save();
                }

                //Action::addNewAction(Action::MENU, 0, $model->id, 'Администратор отредактировал пункт меню - '.$model->name.' ID '.$model->id.', партнера - '.$model->name);
                $this->redirect(array('/admin/banners'));
            }
        }

        $this->render('addbanner', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionCreate()
    {
        $model = new Banner;

        $breadcrumbs = array('Баннеры' => array('/admin/banners'), 'Добавление баннера');
        //Заголовок страницы
        $h1 = 'Добавление баннера';
        //Если форма заполнена
        if (isset($_POST['Banner'])) {

            //Записываем все находившиеся в форме данные, в модель
            $model->attributes = $_POST['Banner'];
            $model->image = CUploadedFile::getInstance($model, 'image');
            if (!$model->image) {
                $this->redirect(array('/admin/banners/create'));
            }
            if ($model->save()) {
                $model->image->saveAs('themes/mobile/img/banners/' . $model->id . '.jpg');
                $model->image = $model->id . '.jpg';
                $model->save();

                $image = Yii::app()->image->load('themes/mobile/img/banners/' . $model->id . '.jpg');
                $image->resize2();
                $image->save();
                //Action::addNewAction(Action::MENU, 0, $model->id, 'Администратор отредактировал пункт меню - '.$model->name.' ID '.$model->id.', партнера - '.$model->name);
                $this->redirect(array('/admin/banners'));
            }
        }

        $this->render('addbanner', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionDelete($id)
    {
        $model = Banner::model()->findByPk($id);
        @unlink('themes/mobile/img/banners/' . $model->image);
        $model->delete();
        $this->redirect(array('/admin/banners'));
    }


    public function loadModel($id)
    {
        $model = Banner::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}