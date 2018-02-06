<?php

/**
 * Class Controller
 *
 * @property Domain $domain
 */
class Controller extends CController
{
    public $layout = "main";
    public $breadcrumbs;
    public $title;
    public $keywords;
    public $description;

    public $registrationUrl = array("registration");
    public $recoveryUrl = array("recovery/recovery");
    public $loginUrl = array("/user/login");
    public $logoutUrl = array("/user/logout");
    public $profileUrl = array("profile");
    public $returnUrl = array("profile");
    public $returnLogoutUrl = array("/user/login");

    public $orders_today;

    public $isMobile;

    public $domain;
    public $citySite;

    public function init()
    {
        $detect = Yii::app()->mobileDetect;
        if (!empty($_GET['language']))
            Yii::app()->language = $_GET['language'];
        //Yii::app()->cache->flush();
        $city=City::model()->cache(10000)->find("alias='".$_SERVER['HTTP_HOST']."'");
        if(!$city){
            City::setUserChooseCity(1);
            $this->domain=Domain::model()->findByPk(3);
        }else{
            City::setUserChooseCity($city->id);
            $this->domain=$city->domain;
        }        
        Yii::app()->session['domain_id']=$this->domain;
        Yii::app()->setHomeUrl("http://".$this->domain->alias);
        if(!isset(Yii::app()->request->cookies['cookie_user_id'])){
            $cookie=new CHttpCookie("cookie_user_id",md5(session_id().time()));
            $cookie->expire=time()+86400*30;
            Yii::app()->request->cookies['cookie_user_id']=$cookie;
        }
        if(isset($_GET['save_city'])){
            $cookie = new CHttpCookie('city_chosen', 1);
            $cookie->expire = time() + 24 * 3600 * 30;
            Yii::app()->request->cookies['city_chosen'] = $cookie;
            header("Location:/");
        }

        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $redirect=Redirect::model()->cache(10000)->find("old_url='".$actual_link."' or old_url='".$_SERVER['REQUEST_URI']."' or old_url='".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'");
        if($redirect){
            //$url=str_replace("http://", "", $redirect->new_url);
            ob_start();
            $this->redirect($redirect->new_url, TRUE, 301);
            exit;
        }
        if($this->domain->id==4){
            $this->domain=Domain::model()->findByPk(1);
            City::setUserChooseCity(1);
        }

        if(isset($_GET['incoming_call'])){
            Yii::app()->request->cookies['incoming_call'] = new CHttpCookie('incoming_call', $_GET['incoming_call']);
        }

        if($detect->isMobile()||isset($_GET['mobile']))
        {
            Yii::app()->theme = 'mobile';
        }
        if($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || Yii::app()->user->id == 989)
        {
           // Yii::app()->theme = 'mobile';
        }
        $seo=Seo::model()->cache(10000)->find("url=:url and name='title' and  city_id=".City::getUserChooseCity(),array(":url"=>$_SERVER['REQUEST_URI']));
        $title=$seo->value;
        $this->title =$title ? $title : Config::getTitle($this->domain->id);
        $keywords=$seo->value;
        $this->keywords =$keywords ? $keywords : Config::getKeywords($this->domain->id);
        $description=$seo->value;
        $this->description =$description ? $description : Config::getDescription($this->domain->id);
        if(!Yii::app()->user->isGuest)
        {
            if(!Yii::app()->user->status)
            {
                Yii::app()->user->logout();
                $this->redirect(array('/user/login'));
            }
        }
        if(!isset(Yii::app()->request->cookies['choose_city']))
        {
            $cityList = City::getCityList($this->domain->id);
            City::setUserChooseCity($cityList[0]->id);
        }

        if(!isset(Yii::app()->request->cookies['open_rest']))
        {
            Yii::app()->request->cookies['open_rest'] = new CHttpCookie('open_rest', 'opened');
        }


        if(!isset(Yii::app()->request->cookies['open_ad']))
        {
            Yii::app()->request->cookies['open_ad'] = new CHttpCookie('open_ad', '0');
        }

        $this->citySite = City::model()->cache(5000)->findByPk(City::getUserChooseCity());

        $cityName = $this->citySite->name;
        $today = date("Y-m-d 00:00:00");

        $str = Order::model()->count(array('condition'=>"date>='".$today."' AND city='".$cityName."'"))+ date('H')*2;
        $out = '';

        $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
        for ($i=count($chars)-1;$i>=0;$i--)
        {
            $out = "<img src = '/images/".$chars[$i].".png'>".$out;
        }
        $zero="";
        if(strlen($str)==1){
            $zero = "<img src = '/images/0.png'>".
                "<img src = '/images/0.png'>".
                "<img src = '/images/0.png'>";
        }
        if(strlen($str)==2){
            $zero = "<img src = '/images/0.png'>".
                "<img src = '/images/0.png'>";
        }
        if(strlen($str)==3){
            $zero = "<img src = '/images/0.png'>";
        }
        $out = $zero.$out;

        $this->orders_today = $str-date('H')*2 ? $out : "<img src = '/images/0.png'>";

        //MailBackup::run();
    }


    public function translitIt($str)
    {
        return Controller::translit($str);
    }

    public static function translit($str)
    {
        $tr = array(
            "1"=>"1","2"=>"2","3"=>"3","4"=>"4",
            "5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9","0"=>"0",
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ё"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"y","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            " "=> "-", "."=> "", "–"=> "", "/"=> "_", '"'=>"", '’'=>"", '`'=>"",
            ','=>"", '!'=>"", '@'=>"", '#'=>"", '$'=>"", '%'=>"",
            '^'=>"", '&'=>"", '*'=>"", '('=>"", ')'=>"", '№'=>"",
            ':'=>"", '?'=>"", '+'=>"", '['=>"", ']'=>"", '{'=>"",
            '}'=>"", ';'=>"", "~"=>"", "«"=>"", "»"=>"", "“"=>"",
            "”"=>"","…"=>"",
        );
        return preg_replace('/^-|---|-$/', '', strtr($str,$tr));
    }
}