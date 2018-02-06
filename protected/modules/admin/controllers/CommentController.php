<?php

class CommentController extends Controller
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
        $model = new CommentInPost('search');
        $model->unsetAttributes();
        if (isset($_GET['CommentInPost']))
            $model->attributes = $_GET['CommentInPost'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        $model = isset($this->_model) ? $this->_model : new CommentInPost();

        $breadcrumbs = array('Посты' => array('/admin/comment'), $model->title ? $model->title : "Создание поста");

        $h1 = $model->title ? $model->title : "Создание поста";

        if (isset($_POST['CommentInPost'])) {
            $old_img = $model->img;
            $model->attributes = $_POST['CommentInPost'];
            $model->img = $model->img == "" ? $old_img : $model->img;
            $model->tname = $this->translitIt($model->name);

            if ($model->save()) {
                $model->setTag($_POST['CommentInPost']['tags']);
                if (!empty($_FILES['CommentInPost']['name']['img'])) {
                    $img_property = CUploadedFile::getInstance($model, 'img');
                    ZHtml::imgSave($model, $img_property, 'comment', 600, 600, 250, 250);
                }
                $this->redirect(array('/admin/comment/'));
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

        @unlink(Yii::getPathOfAlias('webroot') . '/upload/comment/' . $model->img);

        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(array('/admin/comment/'));
    }

    public function actionChangeStatus($id = '', $status = '')
    {
        $partner = CommentInPost::model()->findByPk($id);

        if ($partner->publ == 1) {
            $partner->publ = 0;
            $partner->save();
            echo "not_publ_comment";
        } else {
            $partner->publ = 1;
            $partner->save();
            echo "publ_comment";
        }
        Yii::app()->end();
    }


    public function loadModel($id)
    {
        $model = CommentInPost::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}