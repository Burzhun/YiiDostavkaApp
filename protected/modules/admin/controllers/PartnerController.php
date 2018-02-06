<?php

class PartnerController extends Controller
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
                'roles' => array(User::ADMIN,User::OPERATOR),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionReview($id)
    {
        /** @var Partner $modelPartner */
        $modelPartner = Partner::model()->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $modelPartner->name => '/admin/partner/id/' . $modelPartner->id . '/info/', 'Отзывы');
        $h1 = $modelPartner->name;
        $model = new Review('partner');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Review']))
            $model->attributes = $_GET['Review'];

        $this->render('review', array(
            'model' => $model,
            'modelPartner' => $modelPartner,
            //'partner'=>$partner,
            'h1' => $h1,
            'breadcrumbs' => $breadcrumbs,
        ));
    }


    public function getPartnerId()
    {
        return false;//$this->_partner->id;
    }

    public function actionTogglePub()
    {
        /*if(!Yii::app()->user->checkAccess('administrator')){
            echo json_encode(array('error'=>true,'msg'=>'Вам запрещено данное действие'));
            return;
        }	*/
        //else{
        if (is_numeric(@$_GET['id'])) {
            /** @var Review $model */
            $model = Review::model()->findbyPk($_GET['id']);
            if ($model) {
                if ($model->visible) {
                    $model->visible = 0;
                } else {
                    $model->visible = 1;
                }

                if ($model->save())
                    echo json_encode(array('error' => false, 'msg' => 'Операция успешно завершена!', 'pub' => $model->visible));
            } else {
                echo json_encode(array('error' => true, 'msg' => 'Не найдена новость')); //не найдена запись в бд
            }

        }


        return;
        //}
    }


    public function actionIndex()
    {
        if (!isset(Yii::app()->request->cookies['viewPartnerList'])) {
            Yii::app()->request->cookies['viewPartnerList'] = new CHttpCookie('viewPartnerList', 'primary');
        } else {
            if (Yii::app()->request->cookies['viewPartnerList']->value == 'success') {
                $_GET['Partner']['status'] = 1;
            }
        }
        //$_GET['Partner_sort'] = 'name';
        $model = new Partner('search');
        $model->unsetAttributes();
        if (isset($_GET['Partner']))
            $model->attributes = $_GET['Partner'];

        $this->render('index', array(//@TODO gdghfdhdf
            'model' => $model,
        ));
    }


    public function actionChangeViewPartner()
    {
        Yii::app()->request->cookies['viewPartnerList'] = Yii::app()->request->cookies['viewPartnerList']->value == 'primary' ? new CHttpCookie('viewPartnerList', 'success') : new CHttpCookie('viewPartnerList', 'primary');
        echo Yii::app()->request->cookies['viewPartnerList']->value;
    }


    public function actionInfo($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->findByPk($id);
        $string = (string) $model->name.'';
        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $string => '/admin/partner/id/' . $model->id . '/info/', 'Информация');

        $h1 = $model->name;

        $specs = array();
        $dirs = array();
        foreach ($model->specialization as $s) {
            $specs[] = $s->id;
            $dirs[$s->direction_id] = $s->direction_id;
        }

        if (isset($_POST['Partner']) || isset($_POST['User'])) {

            $old_img = $model->img;
            $old_logo = $model->logo;
            $model->attributes = $_POST['Partner'];
            $model->img = $model->img != $old_img && $model->img != '' ? $model->img : $old_img;
            $model->logo = $model->logo != $old_logo && $model->logo != '' ? $model->logo : $old_logo;

            //$model->tname = $this->translitIt($model->name);

            if (isset($_POST['User']['status'])) {
                $model->user->status = $_POST['User']['status'];
                $model->user->save();
            }

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
            $logo_property = CUploadedFile::getInstance($model, 'logo');

            if ($model->save()) {
                if (!empty($_FILES['Partner']['name']['img'])) {

                    ZHtml::imgSave($model, $img_property, 'partner', 500, 500, 112, 95);
                }

                if (!empty($_FILES['Partner']['name']['logo'])) {

                    ZHtml::imgSave($model, $logo_property, 'partner', 500, 500, 112, 95, true);
                }
                if($model->position==''){
                    $model->updatePosition();
                }
                //Action::addNewAction(Action::REGISTRATION, 0, $model->id, 'Администратор отредактировал пользователя - '.$model->name.' ID'.$model->id);
                $this->redirect(array('partner/id/' . $id . '/info/'));
            }
        }

        $this->render('info', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'specs' => $specs,
        ));
    }

    public function actionMenu($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Меню');

        $h1 = $model->name;


        $menu = new Menu('search');
        $menu->unsetAttributes();

        if (isset($_GET['Menu']))
            $menu->attributes = $_GET['Menu'];

        $this->render('menu/index', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'menu' => $menu,
        ));
    }

    public function actionMenuView($id, $actionId)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $h1 = $model->name;
        /** @var Menu $menu_model */
        $menu_model = Menu::model()->findByPk($actionId);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => array('/admin/partner/id/' . $model->id . '/info'), 'Меню' => array('/admin/partner/id/' . $model->id . '/menu/'));

        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, $menu_model));

        if ($menu_model->have_subcatalog == 0) {
            $list = new Goods('search');
            $list->unsetAttributes();

            if (isset($_GET['Goods']))
                $list->attributes = $_GET['Goods'];

            $this->render('menu/goods', array(
                'model' => $model,
                'breadcrumbs' => $breadcrumbs,
                'h1' => $h1,
                'list' => $list,
            ));
        } else {
            $menu = new Menu('search');
            $menu->unsetAttributes();

            if (isset($_GET['Menu']))
                $menu->attributes = $_GET['Menu'];

            $this->render('menu/submenu', array(
                'model' => $model,
                'breadcrumbs' => $breadcrumbs,
                'h1' => $h1,
                'menu' => $menu,
            ));
        }
    }

    //Экшн предназначен только для подменю
    public function actionAddMenuView($id, $actionId)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Меню' => '/admin/partner/id/' . $model->id . '/menu/', 'Добавление меню');

        //$breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, $menu_model));

        $h1 = $model->name . ' - Добавление меню';

        $parent_model = "";// = Menu::model()->findByPk($actionId);
        $menu_model = "";

        $menu_model = new Menu();

        if (isset($_POST['Menu'])) {
            $menu_model->attributes = $_POST['Menu'];
            if ($menu_model->save()) {
                $catalog = $actionId != 0 ? $actionId : "";
                //Action::addNewAction(Action::MENU, 0, $model->id, 'Администратор добавил пункт меню - '.$menu_model->name.' ID'.$menu_model->id.', партнера - '.$model->name);
                $this->redirect(array('partner/id/' . $id . '/menu/' . $catalog));
            }
        }

        $this->render('menu/addmenu', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'parent_model' => $parent_model,
            'menu_model' => $menu_model,
        ));
    }

    public function actionMenuUpdate($id, $actionId)
    {
        //Модель партнера, чьё меню мы сейчас смотрим
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);
        //Модель меню, которое мы сейчас изменяем
        /** @var Menu $menu_model */
        $menu_model = Menu::model()->findByPk($actionId);
        //Составляем хлебные крошки
        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Меню' => '/admin/partner/id/' . $model->id . '/menu/');
        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, $menu_model));
        //Заголовок страницы
        $h1 = $model->name . ' - ' . $menu_model->name . ' - Редактирование';
        //Если форма заполнена
        if (isset($_POST['Menu'])) {
            //Записываем все находившиеся в форме данные, в модель
            $menu_model->attributes = $_POST['Menu'];

            if ($menu_model->save()) {
                //перенаправляем на текущий каталог
                $catalog = $_POST['oldparent'] != 0 ? $_POST['oldparent'] : "";
                //Action::addNewAction(Action::MENU, 0, $model->id, 'Администратор отредактировал пункт меню - '.$menu_model->name.' ID '.$menu_model->id.', партнера - '.$model->name);
                $this->redirect(array('partner/id/' . $id . '/menu/' . $catalog));
            }
        }

        $this->render('menu/updatemenu', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'menu_model' => $menu_model,
        ));
    }

    public function actionCheckMenu($id){
        $menu=Menu::model()->findByPk($id);
        echo $menu->have_subcatalog;
        exit;
    }

    public function actionMenuDelete($id, $actionId)
    {
        if (Yii::app()->request->isPostRequest) {
            /** @var Menu $model */
            $model = Menu::model()->findByPk($actionId);
            if ($model === null)
                throw new CHttpException(404, 'The requested page does not exist.');

            //Если существуют подкаталоги то удаляем сначала их, а затем удаляем товары в них
            if ($model->have_subcatalog) {
                //находим все подкаталоги
                $child_catalogs = Menu::model()->findAll(array('condition' => 'parent_id = ' . $model->id));
                foreach ($child_catalogs as $c) {
                    //находим все товары подкаталога
                    $goods = Goods::model()->findAll(array('condition' => 'parent_id = ' . $c->id));
                    foreach ($goods as $g) {
                        //удаляем все фото товара
                        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . $g->img);
                        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . 'small' . $g->img);
                        //адаляем товар
                        $g->delete();
                    }
                    //удаляем подкаталог
                    $c->delete();
                }
            } else//если нет подкаталогов то, удаляем сразу товары(если они там есть)
            {
                //находим все товары каталога
                $goods = Goods::model()->findAll(array('condition' => 'parent_id = ' . $model->id));
                foreach ($goods as $g) {
                    //удаляем все фото товара
                    @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . $g->img);
                    @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . 'small' . $g->img);
                    //удаляем товар
                    $g->delete();
                }
            }
            //удаляем каталог
            //Action::addNewAction(Action::MENU, 0, $id, 'Администратор удалил пункт меню - '.$model->name.' ID'.$model->id.', партнера - '.Partner::model()->findByPk($id)->name);
            $model->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionUpdateAjax()
    {
        $es = new EditableSaver('Goods');
        $es->update();
    }

    public function actionAddGoodsView($id, $actionId)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Меню' => '/admin/partner/id/' . $model->id . '/menu/');

        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, Menu::model()->findByPk($actionId)));

        $h1 = $model->name . ' - Добавление товара';

        $parent_model = "";// = Menu::model()->findByPk($actionId);

        $goods_model = new Goods();


        if (isset($_POST['Goods'])) {
            //print_r($_FILES);
            //exit;
            //Указываем что в данном каталоге не могут размещаться подкаталоги.
            /** @var Menu $menu_model */
            $menu_model = Menu::model()->findByPk($actionId);

            //передаем все данные безопасным путем в модель
            $goods_model->attributes = $_POST['Goods'];
            $img_property = CUploadedFile::getInstance($goods_model, 'img');
            if ($menu_model->have_subcatalog == 1) {
                $menu_model->have_subcatalog = 0;
                if($goods_model->validate()) $menu_model->save();
            }
            //var_dump($_FILES['Goods']);
            //exit;
            if ($goods_model->save()) {
                //сохраняем картинку (если она есть)
               // print_r($_FILES['Goods']['tmp_name']);
                if (!empty($_FILES['Goods']['tmp_name'])&&$_FILES['Goods']['tmp_name']['img']!=''){
                    ZHtml::imgSave($goods_model, $img_property, 'goods', 500, 500, 250, 250);
                }
                //Action::addNewAction(Action::GOODS, 0, $id, 'Администратор добавил товар - '.$goods_model->name.' ID'.$goods_model->id.', партнера - '.$model->name);
                //перенаправляем на текущий каталог
                $this->redirect(array('partner/id/' . $id . '/menu/' . $actionId));
            }
        }

        $this->render('menu/addgoods', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'parent_model' => $parent_model,
            'goods_model' => $goods_model,
        ));
    }

    public function actionOrders($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Заказы');

        $h1 = $model->name;

        /*$order_model = new Order('search');
        $order_model->unsetAttributes();

        if(isset($_GET['Order']))
            $order_model->attributes=$_GET['Order'];*/
        $order_model = new Order('search');
        $order_model->unsetAttributes();
        if (isset($_GET['Order']))
            $order_model->attributes = $_GET['Order'];
        /*if(isset($_GET['Partner']))
            $model->partner->attributes=$_GET['Partner'];
        if(isset($_GET['User']))
            $model->user->attributes=$_GET['User'];*/


        $this->render('orders', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'order_model' => $order_model,
        ));
    }

    public function actionRelation_orders($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Заказы группы');

        $h1 = $model->name;

        /*$order_model = new Order('search');
        $order_model->unsetAttributes();

        if(isset($_GET['Order']))
            $order_model->attributes=$_GET['Order'];*/

        $order_model = new Order('search');
        $order_model->unsetAttributes();
        if (isset($_GET['Order']))
            $order_model->attributes = $_GET['Order'];
        /*if(isset($_GET['Partner']))
            $model->partner->attributes=$_GET['Partner'];
        if(isset($_GET['User']))
            $model->user->attributes=$_GET['User'];*/


        $this->render('relation_orders', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'order_model' => $order_model,
        ));
    }

    public function actionOrdersView($id, $actionId)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Заказы' => '/admin/partner/id/' . $model->id . '/orders/', 'Заказ #' . $actionId);

        $h1 = $model->name . ' - Заказ #' . $actionId;
        /** @var Order $order */
        $order = Order::model()->findByPk($actionId);

        $order_item_model = new OrderItem('search');
        $order_item_model->unsetAttributes();

        if (isset($_POST['Order'])) {
            $order->status = $_POST['Order']['status'];
            $order->save();
        }

        if (isset($_GET['OrderItem']))
            $order_item_model->attributes = $_GET['OrderItem'];

        $this->render('ordersView', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'order' => $order,
            'order_item_model' => $order_item_model,
        ));
    }


    public function actionMessage($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info/', 'Сообщения');

        $h1 = $model->name;

        $message = Message::model()->findAll(array('condition' => 'partner_id=' . $model->id, 'order' => 'date DESC'));

        $this->render('message', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'message' => $message,
        ));
    }


    public function actionProfile($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->findByPk($id);

        if (isset($_POST['User'])) {
            /** @var User $user_model */
            $user_model = User::model()->findByPk($model->user->id);

            $oldPass = $user_model->pass;
            $user_model->attributes = $_POST['User'];
            $user_model->pass = empty($_POST['User']['pass']) ? $oldPass : UserModule::encrypting($_POST['User']['pass']);

            //$user_model->attributes=$_POST['User'];

            if ($user_model->save() && !isset($_POST['Partner']))
                $this->redirect(array('partner/id/' . $id . '/profile/'));
        }

        if (isset($_POST['Partner'])) {
            $model->attributes = $_POST['Partner'];
            $sql="delete from tbl_partners_info where partner_id=".$model->id;
            Yii::app()->db->createCommand($sql)->query();
            if(isset($_POST['additional_info'])){
                foreach($_POST['additional_info'] as $array){
                    $p_i=new PartnerInfo();
                    $p_i->partner_id=$model->id;
                    $p_i->name=$array[0];
                    $p_i->phone=$array[1];
                    $p_i->occupation=$array[2];
                    $p_i->save();
                }
            }
            
            if ($model->save()) {
                //Action::addNewAction(Action::OTHER, 0, $model->id, 'Администратор отредактировал информацию в профиле партнера - '.$model->name.' ID'.$model->id);
                $this->redirect(array('partner/id/' . $id . '/profile/'));
            }
        }

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $id . '/info/', 'Профиль');

        $h1 = $model->name;

        $this->render('profile', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionAddPartner()
    {
        $partnerModel = new Partner();

        $userModel = new User();

        if (!empty($_POST)) {
            if (isset($_POST['User'])) {
                $userModel->attributes = $_POST['User'];
                if ($userModel->save() && !isset($_POST['Partner']))
                    $this->redirect(array('index'));
            }

            if (isset($_POST['Partner'])) {
                $partnerModel->attributes = $_POST['Partner'];
                $partnerModel->tname=mb_strtolower($partnerModel->tname);
                $partnerModel->user_id = $userModel->id;
                $img_property = CUploadedFile::getInstance($partnerModel, 'img');

                if ($partnerModel->save()) {
                    if (!empty($_FILES['Partner']['name']['img']))
                        ZHtml::imgSave($partnerModel, $img_property, 'partner', 500, 500, 250, 250);
                    $userModel->partner_id = $partnerModel->id;
                    $userModel->role = User::PARTNER;
                    $userModel->save();
                    //Action::addNewAction(Action::REGISTRATION, 0, 0, 'Администратор добавил нового партнера - '.$partnerModel->name.' ID'.$partnerModel->id);
                    $this->redirect(array('index'));
                }
            }
        }

        $this->render('addpartner', array(
            'partnerModel' => $partnerModel,
            'userModel' => $userModel,
        ));
    }

    public function actionProductView($id, $actionId)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);
        /** @var Goods $product_model */
        $product_model = Goods::model()->findByPk($actionId);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info', 'Меню' => '/admin/partner/id/' . $model->id . '/menu/');

        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, $product_model));

        $h1 = $model->name . ' - ' . $product_model->name;

        $this->render('product', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'product_model' => $product_model,
        ));
    }

    public function actionProductUpdate($id, $actionId)
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);
        /** @var Goods $goods_model */
        $goods_model = Goods::model()->findByPk($actionId);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/info', 'Меню' => '/admin/partner/id/' . $model->id . '/menu/');

        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, $goods_model));

        $h1 = $model->name . ' - ' . $goods_model->name . ' - Редактирование';

        if (isset($_POST['Goods'])) {
            // сохраняем номер каталога в котором находился товар для дольнейшей проверки.
            //Если больше товаров не будет в каталоге, то нужно будет пометить его соответствующе
            $old_parent = $goods_model->parent_id;
            //так как если мы не выбирем файл при изменении, то нам вернется пустое значение картинки
            $temp_post = $_POST['Goods'];
            //то нам приходится сохранять прежнее значение картинки
            $old_img = "";
            if ($temp_post['img'] == '') {
                $old_img = $goods_model->img;
            }
            //присваивать аттрибуты как есть одним разом
            $goods_model->attributes = $temp_post;
            //и отдельно присваивать картинку
            $goods_model->img = $old_img;

            $img_property = CUploadedFile::getInstance($goods_model, 'img');

            //проверка на вшивость
            /** @var Menu $new_parent */
            $new_parent = Menu::model()->findByPk($goods_model->parent_id);
            //если что-то пошло не так и мы пытаемся добавить товар в каталог в котором уже есть подкаталоги
            if (count(Menu::model()->findAll(array('condition' => 'parent_id=' . $goods_model->parent_id))) != 0) {
                //то убиваем этот процесс нахрен
                Yii::app()->end();
                exit();
            }

            if ($goods_model->save()) {
                //если расположение в каталогах поменялось, то
                if ($old_parent != $goods_model->parent_id) {
                    //проверяем старый каталог на отсутствие товаров в нем
                    if (count(Goods::model()->findAll(array('condition' => 'parent_id=' . $old_parent))) == 0) {
                        //если в подкаталогах есть что-то
                        /** @var Menu $old */
                        $old = Menu::model()->findByPk($old_parent);
                        $old->have_subcatalog = 1;
                        $old->save();
                    }
                    //если до этого в новом каталоге не было товаров то нада отметить что теперь есть
                    if ($new_parent->have_subcatalog == 1) {
                        $new_parent->have_subcatalog = 0;
                        $new_parent->save();
                    }
                }
                //сохраняем картинку (если она есть)
                if (!empty($_FILES['Goods']['name']['img']))
                    ZHtml::imgSave($goods_model, $img_property, 'goods', 500, 500, 250, 250);

                //перенаправляем на текущий каталог
                $this->redirect(array('partner/id/' . $id . '/menu/' . $_POST['oldparent']));
            }
        }

        $this->render('updateproduct', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'goods_model' => $goods_model,
        ));
    }

    public function actionProductDelete($id, $actionId)
    {
        //модель удаляемого товара
        /** @var Goods $model */
        $model = Goods::model()->findByPk($actionId);
        //удаляем картинки товара
        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . $model->img);
        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . 'small' . $model->img);
        //если родительском каталоге болльше нет товаров

        //загружаем модель родительского каталога
        /** @var Menu $menu_model */
        $menu_model = Menu::model()->findByPk($model->parent_id);

        if (Goods::model()->count(array('condition' => 'parent_id=' . $model->parent_id)) == 1) {
            //если этот каталог, каталог первого уровня
            if ($menu_model->parent_id == 0) {
                //задаем что у него может быть подкаталог
                $menu_model->have_subcatalog = 1;
                //сохраняем
                $menu_model->save();
            }
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');

        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('partner/id/' . $id . '/menu/' . $menu_model->id));
    }

    public function getFragmentBreadcrumbs($p_partner_id, $p_menu_model)
    {
        $ar = array();
        /** @var Menu $temp_model */
        $temp_model = Menu::model()->findByPk($p_menu_model->parent_id);
        $ar["" . $p_menu_model->name] = "";

        if ($temp_model) {
            while ($temp_model) {
                $ar[$temp_model->name] = array('/admin/partner/id/' . $p_partner_id . '/menu/' . $temp_model->id);
                $temp_model = $temp_model->parent_id ? Menu::model()->findByPk($temp_model->parent_id) : 0;
            }
        }
        return array_reverse($ar);
    }


    public function actionChangeStatus($id = '', $status = '')
    {
        switch ($status) {
            case 1:
                $status = Order::$APPROVED_SITE;
                break;
            case 2:
                $status = Order::$APPROVED_PARTNER;
                break;
            //case 3: $status = Order::$SENT;break;
            case 4:
                $status = Order::$DELIVERED;
                break;
            case 5:
                $status = Order::$CANCELLED;
                break;
        }

        if (Yii::app()->request->isAjaxRequest && $status != Order::$APPROVED_SITE) {
            /** @var Order $order */
            $order = Order::model()->findByPk($id);
            if ($order->status != Order::$DELIVERED && $order->status != Order::$CANCELLED) {
                $order->status = $status;
                $order->save();
                Yii::app()->end();
            }
        }
        Yii::app()->end();
    }

    public function actionChangeVip($id = '', $status = '')
    {
        /** @var Partner $partner */
        $partner = Partner::model()->findByPk($id);

        if ($partner->vip == 1) {
            $partner->vip = 0;
            $partner->save();
            echo "standart_partner";
        } else {
            $partner->vip = 1;
            $partner->save();
            echo "vip_partner";
        }
        Yii::app()->end();
    }

    public function actionChangePartnerStatus($id = '', $status = '')
    {
        /** @var Partner $partner */
        $partner = Partner::model()->findByPk($id);

        if ($partner->status == 1) {
            $partner->status = 0;
            $partner->save();
            echo "blocked_partner";
        } else {
            $partner->status = 1;
            $partner->save();
            echo "active_partner";
        }
        Yii::app()->end();
    }

    public function actionChangeVipRest($id = '', $status = '')
    {
        /** @var Partner $partner */
        $partner = Partner::model()->findByPk($id);

        if ($partner->vip_rest == 1) {
            $partner->vip_rest = 0;
            $partner->save();
            echo "standart_rest_partner";
        } else {
            $partner->vip_rest = 1;
            $partner->save();
            echo "vip_rest_partner";
        }
        Yii::app()->end();
    }

    public function actionChangeFavorite($id = '', $status = '')
    {
        /** @var Goods $goods */
        $goods = Goods::model()->findByPk($id);

        if ($goods->favorite == 1) {
            $goods->favorite = 0;
            $goods->save();
            echo "standart_goods";
        } else {
            $goods->favorite = 1;
            $goods->save();
            echo "favorite_goods";
        }
        Yii::app()->end();
    }

    public function actionChangePublication($id = '', $status = '')
    {
        /** @var Goods $goods */
        $goods = Goods::model()->findByPk($id);

        if ($goods->publication == 1) {
            $goods->publication = 0;
            $goods->save();
            echo "unpubl_goods";
        } else {
            $goods->publication = 1;
            $goods->save();
            echo "publ_goods";
        }
        Yii::app()->end();
    }


    public function actionChangePublicationMenu($id = '', $status = '')
    {
        /** @var Menu $goods */
        $goods = Menu::model()->findByPk($id);

        if ($goods->publication == 1) {
            $goods->publication = 0;
            $goods->save();
            echo "unpubl_menu";
        } else {
            $goods->publication = 1;
            $goods->save();
            echo "publ_menu";
        }
        Yii::app()->end();
    }


    //Добавление аяксом менюшек и подменюшек
    public function actionAjaxAddMenuCatalog($id = '', $status = '')
    {
        $menu_model = new Menu();

        if (isset($_POST['name'])) {
            $menu_model->name = $_POST['name'];
            $menu_model->parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            $menu_model->have_subcatalog = $_POST['have_subcatalog'];
            $menu_model->pos = 0;
            $menu_model->partner_id = $id;

            if ($menu_model->save()) {
                $return_val = true;
            } else {
                $return_val = false;
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            return $return_val;
        } else {
            $this->redirect('/admin/partner/id/' . $id . '/menu/' . $_POST['parent_id']);
        }
    }


    public function actionGetPartnerRecvisit($id)
    {
        $model = Partner::model()->find($id);
        echo "<div></div>";
    }


    public function actionOrderDebt($id, $remove = '')
    {
        /** @var Partner $model */
        $model = Partner::model()->cache(1000)->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $id . '/info/', 'Задолженность');

        $h1 = $model->name . ' - Задолженность';

        $this->render('orderDebt', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionRemoveOrderDebt($id)
    {
        OrderPartnerDebt::model()->updateAll(array('paid' => 1), 'partner_id=' . $id . ' AND paid=0');

        $this->redirect('/admin/partner/id/' . $id . '/orderDebt');
    }

    public function actionGetOrderDebtList($id)
    {
        $dataProvider = new CActiveDataProvider('OrderPartnerDebt', array(
            'criteria' => array(
                'condition' => 'partner_id=' . $id . ' AND paid=0',
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 500,
            ),
        ));

        if (isset($_GET['Action']))
            $model->attributes = $_GET['Action']; // @TODO переменная не определена

        echo $this->renderPartial('orderDebtList', array("dataProvider" => $dataProvider));
    }

    public function actionSortmenu($id)
    {
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            $i = 1;
            foreach ($_POST['items'] as $item) {
                /** @var Menu $model */
                $model = Menu::model()->findByPk($item);
                $model->pos = $i;
                $model->save();
                $i++;
            }
        }
    }

    public function actionSortgoods($id)
    {
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            $i = 1;
            foreach ($_POST['items'] as $item) {
                /** @var Menu $model */
                $model = Goods::model()->findByPk($item);
                $model->pos = $i;
                $model->save();
                $i++;
            }
        }
    }

    public function actionSwappartner($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $id . '/info/', 'Объединение аккаунтов');

        $h1 = $model->name . ' - Объединение аккаунтов';

        $this->render('swappartner', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionAjaxRelatedParner($id = '', $status = '')
    {
        $relation = new Relationpartner();
        print_r($_POST);
        if (isset($_POST['select_partner_id'])) {
            //проверка есть ли добавляемый id среди владельцев (owner)
            //проверка есть ли добавляемый id среди уже поключенных партнеров (user)
            $user = Relationpartner::model()->exists('user_id=' . $_POST['select_partner_id']);
            $owner = Relationpartner::model()->exists('owner_id=' . $_POST['select_partner_id']);
            if ($user || $owner) {
                return false;
            }

            $relation->owner_id = $id;
            $relation->user_id = $_POST['select_partner_id'];

            if ($relation->save()) {
                $return_val = true;
            } else {
                $return_val = false;
            }
        }
        if (Yii::app()->request->isAjaxRequest) {
            return $return_val;
        } else {
            $this->redirect('/admin/partner/id/' . $id . '/swappartner/');
        }
    }

    public function actionDeleterelparner($id = '', $status = '')
    {
        if (Relationpartner::model()->exists('user_id=' . $status . ' AND owner_id=' . $id)) {
            Relationpartner::model()->find(array('condition' => 'user_id=' . $status . ' AND owner_id=' . $id))->delete();
        }
    }

    // @TODO проверь, нужна ли данная функция?
    public function actionCheckNewOrderForMusik($id = '', $status = '')
    {
        $cities = City::model()->cache(60000)->findAll(array('condition' => "domain_id = '" . $this->domain->id . "'"));
        $city_condition = ' AND (1=2 ';
        foreach ($cities as $city) {
            $city_condition .= " OR city LIKE '%" . $city->name . "%'";
        }
        $city_condition .= ')';

        if ($id) {
            $condition = "date > '" . date("Y-m-d H:i:s", time() - 60) . "' AND partners_id = " . $id . $city_condition;
            $orders = Order::model()->findAll(array('condition' => $condition));
            //$orders = Order::model()->findAll(array('condition'=>"date>'2013-10-03 17:51:00' AND partners_id = ".$id));
        } else {
            $condition = "date > '" . date("Y-m-d H:i:s", time() - 60) . "'" . $city_condition;
            $orders = Order::model()->findAll(array('condition' => $condition));
        }

        if (count($orders)) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    // @TODO проверь, нужна ли данная функция?
    public function actionOverdueOrders($id = '', $status = '') //@TODO нужна ли проверка всех связаных партнеров
    {
        $domain=Yii::app()->session['domain_id'];
        $city_condition = ' and domain_id='.$domain->id;
        if ($id) {
            $condition = "date<'" . date("Y-m-d H:i:s", time() - 180) . "' AND status = '" . Order::$APPROVED_SITE . "' AND partners_id = " . $id . $city_condition;
            $orders = Order::model()->findAll(array('condition' => $condition));
            //$orders = Order::model()->findAll(array('condition'=>"date>'2013-10-03 17:51:00' AND partners_id = ".$id));
        } else {
            $condition = "date<'" . date("Y-m-d H:i:s", time() - 180) . "' AND status = '" . Order::$APPROVED_SITE . "'" . $city_condition;
            $orders = Order::model()->findAll(array('condition' => $condition));
        }
        if (count($orders)) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    // @TODO проверь, нужна ли данная функция?
    public function actionCheckNewRelationOrderForMusik($id = '', $status = '')
    {
        $sql = "SELECT id FROM tbl_partners WHERE (user_id IN (
					SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
						SELECT user_id FROM `tbl_partners` WHERE id = " . $id . "
					)
				) OR user_id IN (
					SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
						SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
							SELECT user_id FROM `tbl_partners` WHERE id = " . $id . "
						)
					)
				)) OR id=" . $id;
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryColumn();

        $query = "";
        foreach ($data as $key => $value) {
            $query .= " OR partners_id=" . $value;
        }

        $orders = Order::model()->findAll(array('condition' => "date>'" . date("Y-m-d H:i:s", time() - 60) . "' AND (1=1" . $query . ")"));

        if (count($orders)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function actionChackOrders()
    {
        $lastActionId = $_POST['lastActionId'];
        $partner_id = $_POST['partner_id'];
        $relation = $_POST['relation'];
        $json = array();
        $domain=Yii::app()->session['domain_id'];
        $domain_condition = ' AND domain_id='.$domain->id;

        if ($partner_id) {
            $partner_query = '';
            if($relation) {
                $sql = "SELECT id FROM tbl_partners WHERE (user_id IN (
                        SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
                            SELECT user_id FROM `tbl_partners` WHERE id = " . $partner_id . "
                        )
                    ) OR user_id IN (
                        SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
                            SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
                                SELECT user_id FROM `tbl_partners` WHERE id = " . $partner_id . "
                            )
                        )
                    )) OR id=" . $partner_id;
                $command = Yii::app()->db->cache(10000)->createCommand($sql);
                $data = $command->queryColumn();

                foreach ($data as $key => $value) {
                    $partner_query .= " OR partners_id=" . $value;
                }
                $partner_query = ' AND (1=2 '. $partner_query .')';
            } else {
                $partner_query = ' AND partners_id = "'.$partner_id.'"';
            }
            $conditionNewOrders = "date > '" . date("Y-m-d H:i:s", time() - 60) . "' " . $partner_query . $domain_condition;
            $ordersNewOrders =Yii::app()->db->createCommand("select count(id) from tbl_orders where ".$conditionNewOrders)->queryScalar();

            $conditionOverdueOrders = "date<'" . date("Y-m-d H:i:s", time() - 180) . "' AND status = '" . Order::$APPROVED_SITE . "' " . $partner_query . $domain_condition;
            $overdueOrders =Yii::app()->db->createCommand("select count(id) from tbl_orders where ".$conditionOverdueOrders)->queryScalar();
        } else {
            $conditionNewOrders = "date > '" . date("Y-m-d H:i:s", time() - 60) . "'" . $domain_condition;
            $ordersNewOrders =Yii::app()->db->createCommand("select count(id) from tbl_orders where ".$conditionNewOrders)->queryScalar();

            $conditionOverdueOrders = "date<'" . date("Y-m-d H:i:s", time() - 180) . "' AND status = '" . Order::$APPROVED_SITE . "'" . $domain_condition;
            $overdueOrders =Yii::app()->db->createCommand("select count(id) from tbl_orders where ".$conditionOverdueOrders)->queryScalar();
        }
        //Проверка наличия новых заказов
        $json['haveNewOrders'] = $ordersNewOrders ? 1 : 0;
        //Проверка просроченных заказов
        $json['haveOverdueOrders'] = $overdueOrders ? 1 : 0;
        //Проверка изменения таблицы заказов
        $action = Action::model()->findAll(array('condition' => 'action = "'. Action::ORDER .'" AND id > '.$lastActionId, 'order' => 'id DESC', 'limit' => '1'));
        $json['newActions'] = count($action);
        //Передаем последнее действие по заказам
        $json['lastActionId'] = count($action) ? $action[0]->id : $lastActionId;

        echo json_encode($json);
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {

            $model = $this->loadModel($id);
            $model->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id)
    {
        $model = Partner::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /****** Statistic ******/

    public function bitweenDate()
    {
        $start_day = "";
        $finish_day = "";

        $lastMonth = (int)date("m") - 1;
        $last3Month = (int)date("m") - 3;
        $last6Month = (int)date("m") - 6;
        $lastYear = (int)date("Y") - 1;

        if ($lastMonth < 1) {
            $lastMonth = strtotime(date("01-" . $lastMonth . "-" . $lastYear));
        } else {
            $lastMonth = strtotime(date("01-" . $lastMonth . "-Y"));
        }

        if ($last3Month < 1) {
            $last3Month = strtotime(date("01-" . (12 - $last3Month) . "-" . $lastYear));
        } else {
            $last3Month = strtotime(date("01-" . $last3Month . "-Y"));
        }

        if ($last6Month < 1) {
            $last6Month = strtotime(date("01-" . (12 - $last6Month) . "-" . $lastYear));
        } else {
            $last6Month = strtotime(date("01-" . $last6Month . "-Y"));
        }

        if (!empty($_GET['period'])) {
            Yii::app()->session['period'] = $_GET['period'];
        }

        if (isset(Yii::app()->session['period'])) {
            $period = Yii::app()->session['period'];
        } else {
            $period = 1;
        }

        switch ($period) {
            case 1:
                $start_day = strtotime(date("01-m-Y"));//за текущий месяц
                $finish_day = time();
                break;
            case 2:
                $start_day = $lastMonth;//за прошлый месяц
                $finish_day = strtotime(date("01-m-Y"));
                break;
            case 3:
                $start_day = $last3Month;//за 3 месяца
                $finish_day = time();
                break;
            case 4:
                $start_day = $last6Month;//за пол года
                $finish_day = time();
                break;
            case 5:
                $start_day = strtotime(date("01-m-" . $lastYear));//за год
                $finish_day = time();
                break;
            case 6:
                $start_day = 0;//за все время
                $finish_day = time();
                break;
        }

        return "(date > '" . date('Y-m-d H:i:s', $start_day) . "' AND date < '" . date('Y-m-d H:i:s', $finish_day) . "')";
    }

    public function actionStatisticOrders($id = '', $status = '')
    {
        if (isset($_GET['map_city'])) {
            Yii::app()->session['map_city'] = $_GET['map_city'];
        } else {
            if (!isset(Yii::app()->session['map_city'])) {
                Yii::app()->session['map_city'] = 1;
            }
        }
        $map_city_id = (int)Yii::app()->session['map_city'];
        /** @var Partner $model */
        $model = $this->loadModel($id);
        $breadcrumbs = array($model->name => array('/admin/partner/statisticOrder'), 'Статистика');
        $h1 = $model->name;

        $between_Date = $this->bitweenDate();
        $connection = Yii::app()->db;

        $sql = "SELECT date, COUNT( * ) AS amount, DATE(date) AS day, order_source
		FROM  `tbl_orders`
		WHERE " . $between_Date . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "'
		GROUP BY day ORDER BY  `day` DESC
		LIMIT 190";

        $result = $connection->cache(40000)->createCommand($sql)->queryAll();

        $sql = "SELECT date, SUM((SELECT SUM(total_price) FROM `tbl_order_items` WHERE order_id = `tbl_orders`.id)) AS amount, DATE(date) AS day
		FROM  `tbl_orders`
		WHERE " . $between_Date . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "'
		GROUP BY day ORDER BY  `date` DESC
		LIMIT 190";
        $resultSum = $connection->cache(40000)->createCommand($sql)->queryAll();
        $cities = City::getCityArray();
        $sql = "SELECT *
		FROM  tbl_orders
		WHERE " . $between_Date . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "' AND city='" . $cities[$map_city_id - 1] . "' LIMIT 180";


        $orders = $connection->createCommand($sql)->queryAll();
        $cities_coord = array();
        $cities_coord[1] = '42.989324,47.505676';
        $cities_coord[2] = '42.891594,47.636692';
        $cities_coord[3] = '40.379256,49.836449';
        $coords = $cities_coord[$map_city_id];

        $this->render('statistic/order', array(
            'result' => $result,
            'resultSum' => $resultSum,
            'orders' => $orders,
            'city_coords' => $coords,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionStatisticGoods($id = '', $status = '')
    {
        /** @var Partner $model */
        $model = $this->loadModel($id);
        $breadcrumbs = array($model->name => array('/admin/partner/statisticGoods'), 'Статистика');
        $h1 = $model->name;

        $bitweenDate = $this->bitweenDate();

        $sql = "SELECT *, SUM(goods_id) AS sum_orders, SUM(`tbl_order_items`.quantity) AS sum_goods
		FROM  `tbl_order_items`
		INNER JOIN tbl_orders ON `tbl_order_items`.order_id = `tbl_orders`.Id
		WHERE " . $bitweenDate . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "'
		GROUP BY goods_id
		ORDER BY `sum_goods` DESC ";

        $count = Yii::app()->db->createCommand($sql)->queryColumn();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => count($count),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));

        $this->render('statistic/goods', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionStatisticUsers($id)
    {
        echo $id;
    }

    public function actionPayment($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['sum']) && $model) {
            $payment = new Payment_history();
            $payment->sum = $_POST['sum'];
            $payment->partner_id = $id;
            $payment->balance_before = $model->balance;
            $payment->balance_after = $model->balance + $payment->sum;
            $payment->author = User::model()->findByPk(Yii::app()->user->id)->email;
            $payment->info = "Пополнение баланса";
            $date = time();
            $payment->date = $date;
            if ($payment->save()) {
                $model->balance += $payment->sum;
                $model->save();
                echo 'Done';
            } else {
                //print_r($payment->getErrors());
            }
            exit;
        }
        $breadcrumbs = array($model->name => array('/admin/partner/payment'), 'История платежей');
        $h1 = $model->name;
        $condition2 = "";
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition2 = " and unix_timestamp('{$_GET['from']}') < date and unix_timestamp('" . $_GET['to'] . "')+86400>date";
        } else {
            if (isset($_GET['from'])) {
                $condition2 = " and unix_timestamp('{$_GET['from']}') < date";
            }
            if (isset($_GET['to'])) {
                $condition2 = " and unix_timestamp('" . $_GET['to'] . "')+86400>date";
            }
        }
        $data = new CActiveDataProvider('Payment_history', array(
            'criteria' => array(
                'condition' => 'partner_id=' . $id . $condition2,
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        $this->render('payment', array(
            'data' => $data,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'type'=>''
        ));

    }

    public function actionPayment2($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['sum']) && $model) {
            $payment = new Payment_history();
            $payment->sum = $_POST['sum'];
            $payment->partner_id = $id;
            $payment->balance_before = $model->balance;
            $payment->balance_after = $model->balance + $payment->sum;
            $payment->author = User::model()->findByPk(Yii::app()->user->id)->email;
            $payment->info = "Пополнение баланса";
            $date = time();
            $payment->date = $date;
            if ($payment->save()) {
                $model->balance += $payment->sum;
                $model->save();
                echo 'Done';
            } else {
                //print_r($payment->getErrors());
            }
            exit;
        }
        $breadcrumbs = array($model->name => array('/admin/partner/payment'), 'История платежей');
        $h1 = $model->name;
        $condition2 = "";
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition2 = " and unix_timestamp('{$_GET['from']}') < date and unix_timestamp('" . $_GET['to'] . "')+86400>date";
        } else {
            if (isset($_GET['from'])) {
                $condition2 = " and unix_timestamp('{$_GET['from']}') < date";
            }
            if (isset($_GET['to'])) {
                $condition2 = " and unix_timestamp('" . $_GET['to'] . "')+86400>date";
            }
        }
        $data = new CActiveDataProvider('Payment_history', array(
            'criteria' => array(
                'condition' => 'balance_before<balance_after and partner_id=' . $id . $condition2,
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        $this->render('payment', array(
            'data' => $data,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'type'=>2
        ));

    }

    public function actionRayon($id)
    {
        $model = Partner::model()->cache(1000)->findByPk($id);
        $breadcrumbs = array($model->name => array('/partner/rayon'), 'Районы доставки');
        $h1 = $model->name;
        $rayons = new CActiveDataProvider('PartnerRayon', array(
            'criteria' => array(
                'condition' => 'partner_id=' . $id,
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        $this->render('rayon/index', array('model' => $model, 'rayon' => $rayons, 'h1' => $h1, 'breadcrumbs' => $breadcrumbs));
    }

    public function actionRayonCreate($id)
    {
        $model = Partner::model()->cache(1000)->findByPk($id);
        $partner_rayon = new PartnerRayon;
        if (isset($_POST['PartnerRayon'])) {
            $partner_rayon->partner_id = $model->id;
            $partner_rayon->attributes = $_POST['PartnerRayon'];

            if ($partner_rayon->save()) {
                $this->redirect(array('partner/id/' . $id . "/rayon/"));
            }
        }

        $this->render('rayon/create', array(
            'model' => $model,
            'partner_rayon' => $partner_rayon,
            /*'breadcrumbs'=>$breadcrumbs,
            'h1'=>$h1,*/
        ));

    }

    public function actionRayonUpdate($id)
    {
        if (isset($_GET['rayon_id'])) {
            $rayon_id = (int)$_GET['rayon_id'];
            $model = PartnerRayon::model()->findByPk($rayon_id);
            if (isset($_POST['PartnerRayon'])) {
                $model->attributes = $_POST['PartnerRayon'];

                if ($model->save()) {
                    $this->redirect(array('partner/id/' . $id . "/rayon/"));
                }
            }
            $this->render('rayon/update', array(
                'model' => $model,
                /*'breadcrumbs'=>$breadcrumbs,
                'h1'=>$h1,*/
            ));
        }

    }

    public function actionRayonDelete($id)
    {
        if (isset($_GET['rayon_id'])) {
            $rayon_id = (int)$_GET['rayon_id'];
            $model = PartnerRayon::model()->findByPk($rayon_id);
            $model->delete();
        }
    }
    /**** End Statistic ****/
}