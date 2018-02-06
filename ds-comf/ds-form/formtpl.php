<?php
if($_POST['formid'] && file_exists('forms/'.$_POST['formid'].'.php')) {
include 'forms/'.$_POST['formid'].'.php';
header('Content-Type: text/html; charset='.CHARSET);
include 'lib/class.form.php';
$tplForm = new ClassForm;
$outForm = "";
$strForm = "";
if(defined('VALIDATE_HTML5') && VALIDATE_HTML5 != 1) {
	$novalidate = 'novalidate';
} else $novalidate = '';
$outForm = "\n";
$outForm.= '<form id="form-'.$_POST['formid'].'" method="POST" enctype="multipart/form-data" '.$novalidate.'>';
foreach ($zForma as $index => $pole) {
	if(isset($pole['class'])) {
		$poleclass = ' '.$pole['class'];
	} else $poleclass = '';
	if(isset($pole['id'])) {
		$poleid = 'id = "'.$pole['id'].'" ';
	} else $poleid = '';
	$strForm.= "\n".'<div '.$poleid.'class="pole-'.$index.$poleclass.'">'."\n";
	if(!isset($pole['label'])) {
		$pole['label'] = '';
	} else {
		$pole['label'] = str_replace('(*)', '<span class="required">*</span>', $pole['label']);
	}
	if(!isset($pole['attributs'])) $pole['attributs'] = array();
	switch ($pole['type']) {
				case 'input':
					$strForm.= $tplForm -> input($pole['label'],$pole['attributs']);
				break;
				case 'textarea':
					$strForm.= $tplForm -> textarea($pole['label'],$pole['attributs']);
				break;
				case 'select':
					$strForm.= $tplForm -> select($pole['label'],$pole['attributs'],$pole['options']);
					break;
				case 'freearea':
					$strForm.= $pole['value']."\n";
				break;
			}	
	$strForm.= '</div>';
}
$outForm.=$strForm;
$outForm.="\n".'</form>';
echo($outForm);
} else {
	echo "error";
}
?>