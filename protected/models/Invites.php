<?
class Invites extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{invites}}';
    }

    public function rules(){
        return array(
            array('user_id,email,wasted','required'),
            array('user_id,wasted', 'numerical', 'integerOnly'=>true),
            array('email', 'length', 'max'=>50),
        );
    }

    static function AddInvite($user_id,$email){
        $invite=Invites::model()->find('email=:email and user_id=:user_id',array(':email'=>$email,':user_id'=>$user_id));
        if(!$invite){
            $invite = new Invites();
            $invite->user_id=$user_id;
            $invite->email=$email;
            $invite->wasted=0;
            $invite->save();

            return true;
        }
        return false;
    }

    static function ActivateInvite($email){
        $invite=Invites::model()->find('email=:email and wasted=1',array(':email'=>$email));
        if($invite){
            $invite->wasted=2;
            $invite->save();
            $user_bonus=new UserBonus();
            $user_bonus->user_id=$invite->user_id;
            $user_bonus->date=time();
            $user_bonus->sum_in_start=200;
            $user_bonus->sum=200;
            $user_bonus->info="Получил 200 баллов за заказ приглашенного друга";
            $user_bonus->save();
        }
        else{
            return false;
        }

    }

    static function ActivateInviteRegistration($email,$user_id){
        $invite=Invites::model()->find('email=:email and wasted=0',array(':email'=>$email));
        if($invite){
            $invite->wasted=1;
            $invite->save();
            $user_bonus=new UserBonus();
            $user_bonus->user_id=$user_id;
            $user_bonus->date=time();
            $user_bonus->sum_in_start=50;
            $user_bonus->sum=50;
            $user_bonus->info="Получил 50 баллов за регистрацию";
            $user_bonus->save();
        }
        else{
            return false;
        }

    }
}