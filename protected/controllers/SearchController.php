<?php

class SearchController extends Controller
{

    public function actionIndex($query = "")
    {
        $direction = 1; // @TODO не используется, можно удалить?

        $search = isset($_GET['query']) ? $_GET['query'] : '';

        $specs = Specialization::model()->findAll(array('condition' => "direction_id='1' and domain_id=".$this->domain->id, 'order' => 'pos')); // @TODO не используется, можно удалить?

        //Запрос где находим все товары с запросом
        $sql = "SELECT *
				FROM tbl_partners
				WHERE tbl_partners.id
				IN (
				SELECT partner_id
				FROM tbl_goods
				WHERE name LIKE '%" . $search . "%'
				)
				OR name LIKE '%" . $search . "%'";

        $model = Partner::model()->findAllBySql($sql);

        $h1 = "Поиск";

        $this->render('index', array(
            'search' => $search,
            'model' => $model,
            'h1' => $h1,
        ));
    }

    public function actionComplete()
    {
        //@TODO Переменная $search не объявлена
        $sql = "SELECT *
				FROM tbl_partners
				WHERE tbl_partners.id
				IN (
				SELECT partner_id
				FROM tbl_goods
				WHERE name LIKE '%" . $search . "%'
				)
				OR name LIKE '%" . $search . "%' ORDER BY name DESC";

        $sql2 = "SELECT name
				FROM tbl_menu
				WHERE name LIKE '%" . $search . "%' GROUP BY name ORDER BY name DESC";

        $sql3 = "SELECT name
				FROM tbl_goods
				WHERE name LIKE '%" . $search . "%' GROUP BY name ORDER BY name DESC";

        $model = Partner::model()->findAllBySql($sql);
        $model2 = Menu::model()->findAllBySql($sql2);
        $model3 = Goods::model()->findAllBySql($sql3);

        $return = array();
        foreach ($model as $item) {
            $return[] = $item->name;
        }

        foreach ($model2 as $item) {
            $return[] = $item->name;
        }

        foreach ($model3 as $item) {
            $return[] = $item->name;
        }

        // $return_n = array();
        // for($i=0;$i<10;$i++) {
        //	$return_n[$i] = $return[$i];
        // }


        echo json_encode($return);
    }

}