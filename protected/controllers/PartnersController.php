<?php

class PartnersController extends Controller
{
    public function actionIndex()
    {
        $partnerreg = new PartnerRegistration();
		//print_r($_POST);
        if (isset($_POST['PartnerRegistration'])) {
            $partnerreg->attributes = $_POST['PartnerRegistration'];
            //$partnerreg->img = CUploadedFile::getInstance($partnerreg, 'img');

            if ($partnerreg->validate()) {

                $partner = new Partner();
                $user = new User();

                $partner->name = $partnerreg->partnername;
                $partner->tname = mb_strtolower($this->translitIt($partnerreg->partnername));
                $partner->city_id = $partnerreg->city_id;
                $partner->address = $partnerreg->address;
                $partner->email_order = $partnerreg->email;
                $partner->phone_sms = $partnerreg->smsphone;
                $partner->min_sum = $partnerreg->min_sum;

                $partner->delivery_cost = $partnerreg->delivery_cost;
                $partner->work_begin_time = $partnerreg->work_begin_time;
                $partner->work_end_time = $partnerreg->work_end_time;
                //var_dump($partnerreg);
                //var_dump($partner);

                $partner->delivery_duration = $partnerreg->delivery_duration;
                $partner->text = $partnerreg->text;
                $partner->img = $partnerreg->img;

                //if($partner->validate()){print_r($partner->errors);}exit();

                $partner->day1 = $partnerreg->day1;
                $partner->day2 = $partnerreg->day2;
                $partner->day3 = $partnerreg->day3;
                $partner->day4 = $partnerreg->day4;
                $partner->day5 = $partnerreg->day5;
                $partner->day6 = $partnerreg->day6;
                $partner->day7 = $partnerreg->day7;
                $partner->status = 1;
                $partner->self_status = 1;

                $user->name = $partnerreg->username;
                $user->email = $partnerreg->email;
                $user->phone = $partnerreg->contactphone;
                $user->role = User::PARTNER;
                $user->status = 1;
                $user->pass = md5($partnerreg->pass);
                $user->reg_date = date('Y-m-d');

                //if($user->validate()){print_r($user->errors);}
                //if($partner->validate()){print_r($partner->errors);}
                if ($user->validate() && $partner->validate()) {
                    $partner->work_begin_time=$_POST['PartnerRegistration']['work_begin_time'];
                    $partner->work_end_time=$_POST['PartnerRegistration']['work_end_time'];
                    $user->save();
                    $partner->save();
                    $img_property = CUploadedFile::getInstance($partnerreg, 'img');
                    if (!empty($_FILES['PartnerRegistration']['name']['img']))
                        ZHtml::imgSave($partner, $img_property, 'partner', 500, 500, 250, 250);
                    if (!empty($_POST['Spec'])) {
                        foreach ($_POST['Spec'] as $key => $s) {
                            if ($s == 1) {
                                $specPartnerModel = new SpecPartner();
                                $specPartnerModel->spec_id = (int)$key;
                                $specPartnerModel->partner_id = (int)$partner->id;
                                $specPartnerModel->save();
                            }
                        }
                    }
                    $partner->user_id = $user->id;
                    $user->partner_id = $partner->id;
                    $partner->save();
                    $user->save();
                    $userLogin = new UserLogin;
                    $userLogin->username = $partnerreg->email;
                    $userLogin->password = $partnerreg->pass;
                    if ($userLogin->validate()) {
                        $this->redirect(array('/partner/info'));
                    } else {
                        $this->redirect(array('/partners/'));
                    }
                } else {
                    //$partner->delete();
                    //$user->delete();
					//print_r($user->getErrors());
					//print_r($partner->getErrors());
                }
            }else{
				//print_r($partnerreg->getErrors());
			}
        }

        $this->render('index', array(
            'partnerreg' => $partnerreg,
        ));
    }

    public function actionThanks(){
        echo '2344234';
    }

    public function actiondeletethispartner($id){
        if(!Yii::app()->user->isGuest&&Yii::app()->user->isAdmin()&&isset($_GET['sure'])){
            $p=Partner::model()->findByPk($id);
            $p->delete();
            echo 'ok';
        }
    }
}