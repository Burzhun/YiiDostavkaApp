<?php

class mySalat extends Salat
{
  public $lat;
  public $lon;

    public function __construct($lat,$lon)
    {
  $this->setLocation($lon,$lat,0);
    }
  public function today()
  {
$this->setDate(date('j'), date('n'),date('Y'));
return $this->getPrayTime2();
  }
//   public function month($month=0,$year=0)
//   {
// $prays = array();
// if(!$month)
//   $month = date('n');
// if(!$year)
//   $year = date('Y');
// for($i=1;$i<=date(t,mktime(0,0,0,$month,1,$year));$i++)
// {$this->setDate($i, $month,$year);
// $prays[] = $this->getPrayTime2();
// }

// return $prays;
//   }

    public function year($year=0)
  {
$prays = array();
if(!$year)
  $year = date('Y');
for ($i=1; $i <=12 ; $i++) { 
$prays[$i] = $this->month($i,$year);
}
  return $prays;
  }

}
