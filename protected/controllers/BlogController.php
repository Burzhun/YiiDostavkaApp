<?php

class BlogController extends Controller
{

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Post', array(
            'criteria' => array(
                'order' => 'date DESC',
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'pagination' => array(
                'pageSize' => 1,
            ),
        ));
    }

    public function actionCategory($tname)
    {
        $tag = TagInPost::model()->find(array('condition' => "tname='" . $tname . "'")); // @TODO не используется, можно удалить?

        $dataProvider = new CActiveDataProvider('Post', array(
            'criteria' => array(
                'together' => true,
                'with' => array('tags' => array('alias' => 'tag')),
                'condition' => "tname='" . $tname . "'",
                'order' => 'date DESC',
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'pagination' => array(
                'pageSize' => 1,
            ),
        ));
    }


    public function actionView($id)
    {
        $model = Post::model()->findByPk($id);
        $model->view++;
        $model->save();

        $this->render('view', array('model' => $model));
    }
}