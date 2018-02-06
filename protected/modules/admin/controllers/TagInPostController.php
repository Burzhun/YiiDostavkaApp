<?php

class TagInPostController extends Controller
{
    private $_model;

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
        //$model = new TagInPost::model()->findAll(array('condition'=>'pos'));

        $model = new CActiveDataProvider('TagInPost', array(
            'pagination' => array(
                'pageSize' => 20,
            ),
            'criteria' => array(
                'order' => 'pos',
            ),
        ));

        $this->render('index', array(
            'model' => $model,
        ));
    }


    public function actionUpdate($id)
    {
        $model = TagInPost::model()->findByPk($id);

        $breadcrumbs = array('Посты' => array('/admin/post'), 'Теги' => array('/admin/tags'));

        $h1 = $model->name ? $model->name : "Редактирование тега";

        if (isset($_POST['TagInPost'])) {
            $model->attributes = $_POST['TagInPost'];

            if ($model->save()) {
                $this->redirect(array('/admin/tagInPost/'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionDelete($id)
    {
        $model = TagInPost::model()->findByPk($id);
        PostTag::model()->deleteAll(array('condition' => 'tag_id=' . $id));
        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(array('/admin/tagInPost/'));
    }

    public function actionSort($id)
    {
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            $i = 1;
            foreach ($_POST['items'] as $item) {
                $menu = TagInPost::model()->findByPk($item);
                $menu->pos = $i;
                $menu->save();
                $i++;
            }
        }
    }


    public function loadModel($id)
    {
        $model = TagInPost::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}