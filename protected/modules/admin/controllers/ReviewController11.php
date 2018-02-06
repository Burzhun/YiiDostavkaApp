<?php

class ReviewController extends Controller
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
        $model = new Review('search');
        $model->unsetAttributes();
        if (isset($_GET['Review']))
            $model->attributes = $_GET['Review'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = Review::model()->findByPk($id);

        $breadcrumbs = array('Отзывы' => array('/admin/review'), 'Редактирование');

        $h1 = 'Отзывы - Редактирование';

        if (isset($_POST['Review'])) {
            $model->attributes = $_POST['Review'];
            if ($model->save()) {
                Action::addNewAction('Отзывы', Yii::app()->user->id, 0, 'Администратор отредактировал отзыв - ID' . $model->id);
                $this->redirect(array('index'));
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
        //Review::model()->findByPk($id)->delete();
        //$this->redirect(array('index'));

        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            Action::addNewAction('Отзывы', Yii::app()->user->id, 0, 'Администратор удалил отзыв - ID' . $model->id);
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id)
    {
        $model = Review::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}