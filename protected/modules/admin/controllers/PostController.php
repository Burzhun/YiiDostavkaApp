<?php

class PostController extends Controller
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
        $model = new Post('search');
        $model->unsetAttributes();
        if (isset($_GET['Post']))
            $model->attributes = $_GET['Post'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = isset($this->_model) ? $this->_model : new Post();

        $breadcrumbs = array('Посты' => array('/admin/post'), $model->title ? $model->title : "Создание поста");

        $h1 = $model->title ? $model->title : "Создание поста";

        if (isset($_POST['Post'])) {
            $old_img = $model->img;
            $model->attributes = $_POST['Post'];
            $model->img = $model->img == "" ? $old_img : $model->img;
            //$model->tname = $this->translitIt($model->title);

            if ($model->save()) {
                $model->setTag($_POST['Post']['tags']);
                if (!empty($_FILES['Post']['name']['img'])) {
                    $img_property = CUploadedFile::getInstance($model, 'img');
                    ZHtml::imgSave($model, $img_property, 'post', 570, 570);
                }
                $this->redirect(array('/admin/post/'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionUpdate($id)
    {
        $this->_model = $this->loadModel($id);

        $this->actionCreate();
    }


    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        @unlink(Yii::getPathOfAlias('webroot') . '/upload/post/' . $model->img);

        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(array('/admin/post/'));
    }


    public function actionTags()
    {
        $model = TagInPost::model()->findAll(array('condition' => 'pos'));

        $this->render('tags', array(
            'model' => $model,
        ));
    }


    public function actionUpdateTag($id)
    {
        $this->_model = TagInPost::model()->findByPk($id);

        $breadcrumbs = array('Посты' => array('/admin/post'), 'Теги' => array('/admin/tags'));

        $h1 = $model->title ? $model->title : "Редактирование тега";

        if (isset($_POST['TagInPost'])) {
            $model->attributes = $_POST['TagInPost'];

            if ($model->save()) {
                $this->redirect(array('/admin/tags/'));
            }
        }

        $this->render('updateTag', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionDeleteTag($id)
    {
        $model = TagInPost::model()->findByPk($id);
        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(array('/admin/tags/'));
    }


    public function loadModel($id)
    {
        $model = Post::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}