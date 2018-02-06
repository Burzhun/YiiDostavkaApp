<?php

class MessageController extends Controller
{

    public function actionAjaxCreate()
    {
        if (Yii::app()->request->isAjaxRequest) {
            if (!empty($_POST['text']) && !empty($_POST['partner'])) {
                $model = new Message();
                $model->text = $_POST['text'];
                $model->partner_id = $_POST['partner'];
                $model->date = date('Y-m-d H:i:s');
                if ($model->save()) {
                    echo "Сообщение отправленно!";
                }
            } else {
                echo "Заполните сообщение";
            }
        }
    }


    public function actionAjaxDelete()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $message = Message::model()->findByPk($_POST['message_id']);
            if ($message->partner->id == $_POST['partner_id']) {
                $message->read = 1;
                $message->save();

                $count = Message::model()->count(array('condition' => 'partner_id=' . $message->partner->id . ' AND `read`=1'));
                if ($count > 3) {
                    Message::model()->deleteAll(array('condition' => 'partner_id=' . $message->partner->id . ' AND `read`=1', 'limit' => $count - 3, 'order' => 'date'));
                }
            }
        }
    }

    public function actionDelete()
    {
        $message = Message::model()->findByPk($_GET['message_id']);
        if ($message->partner->id == $_GET['partner_id']) {
            $message->read = 1;
            $message->save();
        }
    }

}