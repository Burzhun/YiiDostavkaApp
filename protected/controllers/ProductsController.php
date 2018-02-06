<?php

class ProductsController extends Controller
{

    public function actions()
    {

    }

    public function actionIndex()
    {

    }

    public function actionFood($id = "")
    {
        $direction = 1;
        $specs = Specialization::model()->cache(300)->findAll(array('condition' => "direction_id='1' and city_id=".City::getUserChooseCity(), 'order' => 'pos'));
        $condition2 = "";
       /* if (false&&$this->domain->id == 1&&isset(Yii::app()->request->cookies['rayon'])) {
            $rayon_id = 0;
            if (isset(Yii::app()->request->cookies['rayon'])) {
                $rayon_id = Yii::app()->request->cookies['rayon']->value;
            }
            //Условие для бакинских ресторанов
            $condition2 = "and exists(select id from tbl_partner_rayon tr1 where t.id=tr1.partner_id and tr1.rayon_id=" . $rayon_id . ")";
        }*/
        if ($id != "") {
            /** @var Specialization $seo_spec */

            $seo_spec = Specialization::model()->cache(600)->find(array('condition' => "tname='" . $id . "' and city_id=".City::getUserChooseCity()));
            if(!$seo_spec){
                $seo_spec=Seo::model()->cache(10000)->find("url='/restorany' and name='text' and  city_id=".City::getUserChooseCity());

            }
            $seo_spec_text=$seo_spec->text ? $seo_spec->text : $seo_spec->description;
            $this->title = $seo_spec->title ? $seo_spec->title : str_replace('{name}', $seo_spec->name, Config::getFoodTitle($this->domain->id));
            $this->keywords = $seo_spec->keywords ? $seo_spec->keywords : str_replace('{name}', $seo_spec->name, Config::getFoodsKeywords($this->domain->id));
            $this->description = $seo_spec->description ? $seo_spec->description : str_replace('{name}', $seo_spec->name, Config::getFoodsDescription($this->domain->id));
            $h1=$seo_spec->h1;
            $model = Partner::model()->cache(300)->with('specialization')->findAll(array(
                "condition" => "status=1 AND self_status=1 " . $condition2 . " AND specialization.tname='" . $id . "'  AND specialization.city_id='" . City::getUserChooseCity() . "'",
                'order' => 't.soon_opening , position asc',
                "together" => true
            ));

        } else {
            $model = Partner::model()->cache(300)->with('specialization')->findAll(array('order' => 't.soon_opening , position asc', 'condition' => "status=1 AND self_status=1 " . " AND direction_id=1 AND specialization.city_id='" . City::getUserChooseCity() . "'"));
            $h1=Seo::model()->cache(10000)->find("url='/restorany' and name='h1' and  city_id=".City::getUserChooseCity())->value;
            $seo_spec_text=Seo::model()->cache(10000)->find("url='/restorany' and name='text' and  city_id=".City::getUserChooseCity())->value;
        }
        //$h1 = "Еда";

        $this->render('catalog', array(
            'direction' => $direction,
            'model' => $model,
            'specs' => $specs,
            'seo_spec_text' => $seo_spec_text,
            'h1' => $h1,
        ));
    }

    public function actionGoods($id = "")
    {
        $this->title = Config::getGoodTitle($this->domain->id);
        $this->description = Config::getGoodDescription($this->domain->id);

        $direction = 2;

        $specs = Specialization::model()->cache(300)->findAll(array('condition' => "direction_id='2' and city_id=".City::getUserChooseCity(), 'order' => 'pos'));
        $condition2 = "";
        if (false&&$this->domain->id == 1&&isset(Yii::app()->request->cookies['rayon'])) {
            $rayon_id = 0;
            if (isset(Yii::app()->request->cookies['rayon'])) {
                $rayon_id = Yii::app()->request->cookies['rayon']->value;
            }
            //Условие для бакинских ресторанов
            $condition2 = "and exists(select id from tbl_partner_rayon tr1 where t.id=tr1.partner_id and tr1.rayon_id=" . $rayon_id . ")";
        }
        if ($id != "") {
            /** @var Specialization $seo_spec */

            $seo_spec = Specialization::model()->cache(600)->find(array('condition' => "tname='" . $id . "' and city_id=".City::getUserChooseCity()));
            $this->title = $seo_spec->title ? $seo_spec->title : str_replace('{name}', $seo_spec->name, Config::getGoodsTitle($this->domain->id));;
            $this->keywords = $seo_spec->keywords ? $seo_spec->keywords : str_replace('{name}', $seo_spec->name, Config::getGoodsKeywords($this->domain->id));;
            $this->description = $seo_spec->description ? $seo_spec->description : str_replace('{name}', $seo_spec->name, Config::getGoodsDescription($this->domain->id));;
            /* $model = Partner::model()->cache(300)->with('specialization')->findAll(array(
                "condition" => "status=1 AND self_status=1 AND specialization.tname='" . $id . "' " . $condition2,
                'order' => 't.soon_opening , position asc',
                "together" => true
            ));*/
        } else {
            //$model = Partner::model()->cache(300)->with('specialization')->findAll(array('order' => 't.soon_opening , position asc', 'condition' => "status=1 AND self_status=1 " . $condition2 . " AND direction_id=1 AND specialization.city_id='" . City::getUserChooseCity() . "'"));
        }

        $model = Partner::model()->cache(300)->with('specialization')->findAll(array('order' => 't.soon_opening , position asc', 'condition' => "status=1 AND self_status=1 AND direction_id=2 AND specialization.city_id='" . City::getUserChooseCity() . "'"));

        $h1 = "Продукты";

        $this->render('catalog', array(
            'direction' => $direction,
            'model' => $model,
            'specs' => $specs,
            'h1' => $h1,
        ));
    }

    public function actionHoreca($id = "")
    {
        if(!(Yii::app()->user->isAdmin() || Yii::app()->user->isPartner())){
            $this->redirect('/');
        }

        $this->title = Config::getGoodTitle($this->domain->id);
        $this->description = Config::getGoodDescription($this->domain->id);

        $direction = 3;

        $specs = Specialization::model()->cache(300)->findAll(array('condition' => "direction_id='3' and city_id=".City::getUserChooseCity(), 'order' => 'pos'));
        $condition2 = "";
        if (false&&$this->domain->id == 1&&isset(Yii::app()->request->cookies['rayon'])) {
            $rayon_id = 0;
            if (isset(Yii::app()->request->cookies['rayon'])) {
                $rayon_id = Yii::app()->request->cookies['rayon']->value;
            }
            //Условие для бакинских ресторанов
            $condition2 = "and exists(select id from tbl_partner_rayon tr1 where t.id=tr1.partner_id and tr1.rayon_id=" . $rayon_id . ")";
        }
        if ($id != "") {
            /** @var Specialization $seo_spec */

            $seo_spec = Specialization::model()->cache(5000)->find(array('condition' => "tname='" . $id . "' and city_id=".City::getUserChooseCity()));

            $this->title = $seo_spec->title ? $seo_spec->title : str_replace('{name}', $seo_spec->name, Config::getGoodsTitle($this->domain->id));;
            $this->keywords = $seo_spec->keywords ? $seo_spec->keywords : str_replace('{name}', $seo_spec->name, Config::getGoodsKeywords($this->domain->id));;
            $this->description = $seo_spec->description ? $seo_spec->description : str_replace('{name}', $seo_spec->name, Config::getGoodsDescription($this->domain->id));;

            $model = Partner::model()->cache(300)->with('specialization')->findAll(array(
                "condition" => "status=1 AND self_status=1 AND specialization.tname='" . $id . "' " . $condition2,
                'order' => 't.soon_opening , position asc',
                "together" => true
            ));
        } else {
            $model = Partner::model()->cache(300)->with('specialization')->findAll(array('order' => 't.soon_opening , position asc', 'condition' => "status=1 AND self_status=1 " . $condition2 . " AND direction_id=3 AND specialization.city_id='" . City::getUserChooseCity() . "'"));
        }

        $model = Partner::model()
                            ->cache(300)
                            ->with('specialization')
                            ->findAll(array(
                                    'order' => 't.soon_opening , position asc',
                                    'condition' => "status=1 AND self_status=1 AND direction_id=3 AND specialization.city_id='" . City::getUserChooseCity() . "'"));

        $h1 = "Horeca";

        $this->render('catalog', array(
            'direction' => $direction,
            'model' => $model,
            'specs' => $specs,
            'h1' => $h1,
        ));
    }

    public function actionAjaxCheckSpecs()
    {
        $criteria = ' 1=1 ';
        $model = '';
        if (isset($_POST['Criteria'])) {
            if (isset($_POST['Criteria']['1'])) {
                $criteria .= " AND delivery_cost='0'";
            }
            if (isset($_POST['Criteria']['2'])) {
                $criteria .= " AND min_sum='0'";
            }
        }
        $condition2 = '';
        if (false&&$this->domain->id == 1&&isset(Yii::app()->request->cookies['rayon'])) {
            $rayon_id = 0;
            if (isset(Yii::app()->request->cookies['rayon'])) {
                $rayon_id = Yii::app()->request->cookies['rayon']->value;
            }
            //Условие для бакинских ресторанов
            $condition2 = 'and exists(select id from tbl_partner_rayon tr1 where t.id=tr1.partner_id and tr1.rayon_id=' . $rayon_id . ')';
        }
        //если выбрана одна и более специализация
        if (isset($_POST['Spec'])) {
            //составляем запрос для получения всех партнеров которые занимаются отмеченными специальностями
            $condition = '1=1 AND (1=0 ';
            foreach ($_POST['Spec'] as $key => $p) {
                $condition .= " OR spec_id='" . $key . "'";
            }
            $condition .= ')';
            //непосредственно сам запрос
            $model = Partner::model()->cache(300)->with('specialization')->findAll(array(
                'condition' => 'status=1 AND self_status=1 ' . $condition2 . ' and ' . $condition . " AND specialization.city_id='" . City::getUserChooseCity() . "' AND " . $criteria,
                'order' => 't.soon_opening ,t.position asc',
                'together' => true
            ));
            //если по запросу ничего не найденно
        } else {
            $model = Partner::model()->cache(300)->with('specialization')->findAll(array(
                'condition' => 'status=1 AND self_status=1 ' . $condition2 . ' AND direction_id=' . $_POST['Direction'] . " AND specialization.city_id='" . City::getUserChooseCity() . "' AND " . $criteria,
                'order' => 't.soon_opening, position asc',
                'together' => true,
            ));
        }
        echo $this->renderPartial('supplierList', array('model' => $model));
        Yii::app()->end();
    }

    public function actionAjaxCheckSpecsMobile()
    {
        $condition2 = '';
        if (false&&$this->domain->id == 1&&isset(Yii::app()->request->cookies['rayon'])) {
            $rayon_id = 0;
            if (isset(Yii::app()->request->cookies['rayon'])) {
                $rayon_id = Yii::app()->request->cookies['rayon']->value;
            }
            //Условие для бакинских ресторанов
            $condition2 = "and exists(select id from tbl_partner_rayon tr1 where t.id=tr1.partner_id and tr1.rayon_id=" . $rayon_id . ")";
        }
        $model = Partner::model()->cache(300)->with('specialization')->findAll(array('order' => 't.soon_opening , position asc', 'condition' => "status=1 AND self_status=1 AND direction_id={$_POST['Direction']} AND specialization.city_id='" . City::getUserChooseCity() . "'"));

        echo $this->renderPartial('supplierList', array('model' => $model));
        Yii::app()->end();
    }

    public function actionError()
    {

    }

    public function actionSetRayon()
    {
        if (isset($_POST['rayon_id'])) {
            $id = (int)$_POST['rayon_id'];
            if($id>0){
                $cookie = new CHttpCookie('rayon', $id);
                $cookie->expire = time() + 24 * 3600 * 30;
                Yii::app()->request->cookies['rayon'] = $cookie;
            }else{
                $cookie = new CHttpCookie('rayon', $id);
                $cookie->expire = time() - 24 * 3600 * 30;
                Yii::app()->request->cookies['rayon'] = $cookie;
            }

            echo 'Ok';
        }
    }
}