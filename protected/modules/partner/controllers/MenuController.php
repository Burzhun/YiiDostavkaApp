<?php

class MenuController extends Controller
{

    private $_model;

    public function filters()
    {
        return array(
            'accessControl',
        );
    }


    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array(User::PARTNER),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionUpdateAjax()
    {
        $es = new EditableSaver('Goods');
        $es->update();
    }

    public function actionIndex()
    {
        $model = $this->loadPartner();

        $breadcrumbs = array($model->name => array('/partner/info'), 'Меню');

        $h1 = 'Меню';

        $menu = new Menu('search');
        $menu->unsetAttributes();

        if (isset($_GET['Menu']))
            $menu->attributes = $_GET['Menu'];

        $this->render('index', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'menu' => $menu,
        ));
    }


    public function actionView($id)
    {
        //Модель партнера
        $model = $this->loadPartner();

        //модель меню
        $menu_model = Menu::model()->findByPk($id);

        //Заголовок страницы
        $h1 = $menu_model->name;

        //проверяем, является ли присланный id настоящим, или его вставили
        if ($menu_model->partner_id != $model->id)
            //если меню не принадлежит данному партнеру, то выходим отсюда
            $this->redirect(array('index'));
        //составляем начало хлебных крошек
        $breadcrumbs = array($model->name => array('/partner/info'), 'Меню' => array('/partner/menu/'));
        //добавляем пункты меню к хлебным крошкам
        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($model->id, $menu_model));

        //проверка, имеет ли текущее меню подменю
        if ($menu_model->have_subcatalog == 0) {
            //если мы находимся в товарах
            $list = new Goods('search');
            $list->unsetAttributes();

            if (isset($_GET['Goods']))
                $list->attributes = $_GET['Goods'];

            $this->render('goods', array(
                'model' => $model,
                'breadcrumbs' => $breadcrumbs,
                'h1' => $h1,
                'list' => $list,
            ));
        } else {
            //если мы находимся в подменю
            $menu = new Menu('search');
            $menu->unsetAttributes();

            if (isset($_GET['Menu']))
                $menu->attributes = $_GET['Menu'];

            $this->render('submenu', array(
                'model' => $model,
                'breadcrumbs' => $breadcrumbs,
                'h1' => $h1,
                'menu' => $menu,
            ));
        }
    }


    public function actionAddMenu($id)
    {
        $model = $this->loadPartner();
        if($model->user->id!=Yii::app()->user->id){
            $this->redirect(array('menu/'));
        }

        $breadcrumbs = array($model->name => array('/partner/info'), 'Меню' => '/partner/menu/', 'Добавление меню');

        $h1 = $model->name . ' - Добавление меню';

        $parent_model = "";
        $menu_model = "";

        $menu_model = new Menu();

        if (isset($_POST['Menu'])) {
            $menu_model->attributes = $_POST['Menu'];
            if ($menu_model->save()) {
                $catalog = $id != 0 ? $id : "";
                $this->redirect(array('menu/' . $catalog));
            }
        }

        $this->render('addmenu', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'parent_model' => $parent_model,
            'menu_model' => $menu_model,
        ));
    }


    public function actionUpdateMenu($id)
    {
        //Модель партнера, чьё меню мы сейчас смотрим
        $model = $this->loadPartner();

        //Модель меню, которое мы сейчас изменяем
        $menu_model = Menu::model()->findByPk($id);
        if($menu_model->partner_id!=$model->id){
            $this->redirect(array('menu/'));
        }
        //Составляем хлебные крошки
        $breadcrumbs = array($model->name => array('/partner/info'), 'Меню' => '/partner/menu');
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
                //Action::addNewAction('Меню', 0, $model->id, 'Администратор отредактировал пункт меню - '.$menu_model->name.' ID '.$menu_model->id.', партнера - '.$model->name);
                $this->redirect(array('menu/' . $catalog));
            }
        }

        $this->render('updatemenu', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'menu_model' => $menu_model,
        ));
    }


    public function actionDeleteMenu($id)
    {
        $model = Menu::model()->findByPk($id);

        //проверяем, является ли присланный id настоящим, или его вставили
        if ($model->partner_id != $this->loadPartner()->id)
            //если меню не принадлежит данному партнеру, то выходим отсюда
            $this->redirect(array('index'));

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
        //Action::addNewAction('Меню', 0, $id, 'Администратор удалил пункт меню - '.$model->name.' ID'.$model->id.', партнера - '.$this->loadPartner()->name);
        $menu_id = $model->parent_id != 0 ? $model->parent_id : "";
        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/partner/menu/' . $menu_id));
    }


    public function actionProduct($id)
    {
        $model = $this->loadPartner();
        $product_model = Goods::model()->findByPk($id);
        if($model->id!=$product_model->partner_id){
            $this->redirect(array('menu/'));
        }

        $breadcrumbs = array($model->name => array('/partner/info'), 'Меню' => '/partner/menu');

        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, $product_model));

        $h1 = $model->name . ' - ' . $product_model->name;

        $this->render('product', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'product_model' => $product_model,
        ));
    }


    public function actionAddGoods($id)
    {
        $model = $this->loadPartner();

        $breadcrumbs = array($model->name => array('/partner/info'), 'Меню' => '/partner/menu');

        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, Menu::model()->findByPk($id)));

        $h1 = $model->name . ' - Добавление товара';

        $parent_model = "";

        $goods_model = new Goods();


        if (isset($_POST['Goods'])) {
            //Указываем что в данном каталоге не могут размещаться подкаталоги.
            $menu_model = Menu::model()->findByPk($id);
            $menu_model->have_subcatalog = 0;
            $menu_model->save();

            //передаем все данные безопасным путем в модель
            $goods_model->attributes = $_POST['Goods'];
            $img_property = CUploadedFile::getInstance($goods_model, 'img');

            if ($goods_model->save()) {
                //сохраняем картинку (если она есть)
                if (!empty($_FILES['Goods']['name']['img']))
                    ZHtml::imgSave($goods_model, $img_property, 'goods', 500, 500, 250, 250);
                //перенаправляем на текущий каталог
                $this->redirect(array('/partner/menu/' . $id));
            }
        }

        $this->render('addgoods', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'parent_model' => $parent_model,
            'goods_model' => $goods_model,
        ));
    }


    public function actionUpdateProduct($id)
    {
        $model = $this->loadPartner();
        $goods_model = Goods::model()->findByPk($id);
        if($model->id!=$goods_model->partner_id){
            $this->redirect(array('menu/'));
        }

        $breadcrumbs = array($model->name => array('/partner/info'), 'Меню' => '/partner/menu');

        $breadcrumbs = array_merge($breadcrumbs, $this->getFragmentBreadcrumbs($id, $goods_model));

        $h1 = $model->name . ' - ' . $goods_model->name . ' - Редактирование';

        if (isset($_POST['Goods'])) {
            $temp_post = $_POST['Goods'];
            $old_img = "";
            if ($temp_post['img'] == '') {
                $old_img = $goods_model->img;
            }
            $goods_model->attributes = $temp_post;
            $goods_model->img = $old_img;

            $img_property = CUploadedFile::getInstance($goods_model, 'img');

            if ($goods_model->save()) {
                //сохраняем картинку (если она есть)
                if (!empty($_FILES['Goods']['name']['img']))
                    ZHtml::imgSave($goods_model, $img_property, 'goods', 500, 500, 250, 250);

                //Action::addNewAction('Товар', 0, $model->id, 'Администратор отредактировал товар - '.$goods_model->name.' ID'.$goods_model->id.' партнера - '.$model->name);
                //перенаправляем на текущий каталог
                $this->redirect(array('menu/' . $_POST['oldparent']));
            }
        }

        $this->render('updateproduct', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'goods_model' => $goods_model,
        ));
    }


    public function actionDeleteProduct($id)
    {
        //if(Yii::app()->request->isPostRequest)
        //{
        //модель удаляемого товара
        $model = Goods::model()->findByPk($id);

        if($this->loadPartner()->id!=$model->partner_id){
            exit;
        }
        //удаляем картинки товара
        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . $model->img);
        @unlink(Yii::getPathOfAlias('webroot') . DS . 'upload/goods' . DS . 'small' . $model->img);
        //если родительском каталоге болльше нет товаров

        //загружаем модель родительского каталога
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

        //Action::addNewAction('Товар', 0, $model->id, 'Удалил товар - '.$model->name.' ID'.$model->id.' партнера - '.$this->loadPartner()->name);
        $model->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/partner/menu/' . $menu_model->id));
        //}
        //else
        //throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }


    public function actionAjaxAddMenuCatalog($id = '', $status = '')
    {
        $menu_model = new Menu();
        $model = $this->loadPartner();
        if (isset($_POST['name'])) {
            $menu_model->name = $_POST['name'];
            $menu_model->parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : 0;
            $menu_model->have_subcatalog = $_POST['have_subcatalog'];
            $menu_model->pos = 0;
            $menu_model->partner_id = $model->id;
            $parent_menu=Menu::model()->findByPk($_POST['parent_id']);
            if($parent_menu && $parent_menu->partner_id != $model->id){
                exit;
            }
            if ($menu_model->save()) {
                $return_val = true;
            } else {
                $return_val = false;
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            return $return_val;
        } else {
            $this->redirect('/partner/menu/' . $_POST['parent_id']);
        }
    }


    public function getFragmentBreadcrumbs($p_partner_id, $p_menu_model)
    {
        $ar = array();
        $temp_model = Menu::model()->findByPk($p_menu_model->parent_id);
        $ar["" . $p_menu_model->name] = "";

        if ($temp_model) {
            while ($temp_model) {
                $ar[$temp_model->name] = array('/partner/menu/' . $temp_model->id);
                $temp_model = $temp_model->parent_id ? Menu::model()->findByPk($temp_model->parent_id) : 0;
            }
        }
        return array_reverse($ar);
    }


    public function loadPartner()
    {
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = User::model()->findByPk(Yii::app()->user->id)->partner;//Yii::app()->controller->module->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }

    public function actionSortmenu()
    {
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            $i = 1;
            foreach ($_POST['items'] as $item) {
                $model = Menu::model()->findByPk($item);
                $model->pos = $i;
                $model->save();
                $i++;
            }
        }
    }

    public function actionSortgoods()
    {
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            $i = 1;
            foreach ($_POST['items'] as $item) {
                $model = Goods::model()->findByPk($item);
                $model->pos = $i;
                $model->save();
                $i++;
            }
        }
    }

    public function actionChangeFavorite($id = '', $status = '')
    {
        $goods = Goods::model()->findByPk($id);
        if($this->loadPartner()->id!=$goods->partner_id){
            exit;
        }
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
        $goods = Goods::model()->findByPk($id);
        if($this->loadPartner()->id!=$goods->partner_id){
            exit;
        }
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
        $goods = Menu::model()->findByPk($id);
        if($goods->parent_id){
            $g2=Menu::model()->findByPk($goods->parent_id);
            $p_id=$g2->partner_id;
        }else{
            $p_id=$goods->partner_id;
        }
        if($this->loadPartner()->id!=$p_id){
            exit;
        }
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
    public function actionCheckMenu($id){
        $menu=Menu::model()->findByPk($id);
        echo $menu->have_subcatalog;
        exit;
    }
}