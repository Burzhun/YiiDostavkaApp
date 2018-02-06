<?php

class ReviewController extends Controller
{

    public function actions()
    {

    }

    public function actionIndex()
    {
        $model = new Review();

        $reviews = new CActiveDataProvider('Review', array(
            'criteria' => array(
                'order' => 'date DESC',
                'condition' => "status='" . Review::PUBLISHED . "'",
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        if (isset($_POST['Review'])) {
            $model->attributes = $_POST['Review'];
            $model->date = date('Y-m-d');
            $model->user_id = Yii::app()->user->id;
            if ($model->save()) {
                $this->redirect(array('/review'));
            }
        }

        $this->render('index', array(
            'model' => $model,
            'reviews' => $reviews,
        ));
    }


    public function actionError()
    {

    }

}