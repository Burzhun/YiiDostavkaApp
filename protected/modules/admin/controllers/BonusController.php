<?php

class BonusController extends Controller
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
                'roles' => array(User::ADMIN),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        $model = new Bonus('search');
        $model->unsetAttributes();
        if (isset($_GET['Bonus']))
            $model->attributes = $_GET['Bonus'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = Bonus::model()->findByPk($id);

        $breadcrumbs = array('Бонусы' => array('/admin/bonus'), 'Редактирование - ' . $model->name);
        //Заголовок страницы
        $h1 = $model->name . ' - Редактирование';
        //Если форма заполнена
        if (isset($_POST['Bonus'])) {
            //Записываем все находившиеся в форме данные, в модель
            $old_img = $model->img;
            $model->attributes = $_POST['Bonus'];
            $model->img = $model->img == $old_img || $model->img == '' ? $old_img : $model->img;

            if ($model->save()) {
                if (!empty($_FILES['Bonus']['name']['img'])) {
                    $img_property = CUploadedFile::getInstance($model, 'img');
                    ZHtml::imgSave($model, $img_property, 'bonus', 500, 500, 250, 250);
                }
                //Action::addNewAction(Action::MENU, 0, $model->id, 'Администратор отредактировал пункт меню - '.$model->name.' ID '.$model->id.', партнера - '.$model->name);
                $this->redirect(array('/admin/bonus'));
            }
        }

        $this->render('addbonus', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionCreate($id)
    {
        $model = new Bonus;

        $breadcrumbs = array('Бонусы' => array('/admin/bonus'), 'Добавление бонуса');
        //Заголовок страницы
        $h1 = 'Добавление бонуса';
        //Если форма заполнена
        if (isset($_POST['Bonus'])) {
            //Записываем все находившиеся в форме данные, в модель
            $model->attributes = $_POST['Bonus'];

            if ($model->save()) {
                if (!empty($_FILES['Bonus']['name']['img'])) {
                    $img_property = CUploadedFile::getInstance($model, 'img');
                    ZHtml::imgSave($model, $img_property, 'bonus', 500, 500, 250, 250);
                }
                //Action::addNewAction(Action::MENU, 0, $model->id, 'Администратор отредактировал пункт меню - '.$model->name.' ID '.$model->id.', партнера - '.$model->name);
                $this->redirect(array('/admin/bonus'));
            }
        }

        $this->render('addbonus', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'model' => $model,
        ));
    }

    public function actionDelete($id)
    {
        $model = Bonus::model()->findByPk($id);
        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/bonus' . DS . $model->img);
        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/bonus' . DS . 'small' . $model->img);
        $model->delete();
    }


    public function loadModel($id)
    {
        $model = Bonus::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}