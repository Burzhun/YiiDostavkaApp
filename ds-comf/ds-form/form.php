<?php
header('Content-type: application/json');

if($_POST['formid'] && file_exists('forms/'.$_POST['formid'].'.php')) {
	include 'forms/'.$_POST['formid'].'.php';
	include 'lib/class.form.php';
	include("lib/class.phpmailer.php");
	$cForm = new ClassForm;
	$mail = new PHPMailer;
	function cleanpost($post) {
		$post = strip_tags(htmlspecialchars(trim($post)));
	    return $post;
	}
	array_filter($_POST,'cleanpost');

	foreach ($zForma as $pole) {
		if(isset($pole['attributs']['required'])) {
			if(!$pole['attributs']['pattern']) $pole['attributs']['pattern'] = '';
			$cForm->validate($pole['attributs']['pattern'], $_POST[$pole['attributs']['name']],$pole['attributs']['name']);
		}
	}
	if ($cForm->error) {
		$cForm->error['error'] = 1;
		$cForm->error['formid'] = $_POST['formid'];
		echo(json_encode($cForm->error));
		exit();
	}
	$im = 0;
	$message = array();
	$mesfiles = array();
	foreach ($zForma as $pole) {
		if(isset($pole['formail']) && $pole['formail'] === 1) {

			if($pole['type'] != 'freearea') {

				$pole['attributs']['name'] = preg_replace('|\[[^\]]*\]|siU', '', $pole['attributs']['name']);

				if(isset($pole['name_mail']) && !empty($pole['name_mail'])) {
					$message[$im]['name'] = $pole['name_mail'];

				} elseif(isset($pole['label']) && !empty($pole['label'])) {
					$pole['label'] = str_replace('(*)', '', $pole['label']);
					$message[$im]['name'] = trim($pole['label']);
				}

				if(!is_array($_POST[$pole['attributs']['name']]) && $pole['attributs']['type']!='file') {
					$message[$im]['message'] = $_POST[$pole['attributs']['name']];
				}
				if($pole['attributs']['type'] == 'file') {
					$postfiles = $_FILES[$pole['attributs']['name']];
					if(is_array($postfiles['error'])) {
						foreach ($postfiles['error'] as $eindex => $evalue) {
							if($postfiles['error'][$eindex] === 0) {
								$mail->AddAttachment($postfiles['tmp_name'][$eindex],$postfiles['name'][$eindex]);
							}
						}
					} else {
						if($postfiles['error'] === 0) {
							$mail->AddAttachment($postfiles['tmp_name'],$postfiles['name']);
						}
					}
				}
			} elseif(isset($pole['name_mail']) && !empty($pole['name_mail'])) {
				$message[$im]['name'] = $pole['name_mail'];
				$message[$im]['message'] = $pole['type'];
			}
		}
		$im++;
	}
	$mailtpl = $cForm->mailtpl($message);

	$mail->CharSet = CHARSET;
	$mail->From = FROM_EMAIL;
	$mail->FromName = FROM_NAME;
	foreach ($to_email as $email) {
		$mail->addAddress($email);
	}
	foreach ($cc_email as $email) {
		$mail->addCC($email);
	}
	$mail->isHTML(true);
	$mail->Subject = SUBJECT;
	$mail->Body = $mailtpl;

	if(@$mail->send()){
		echo(json_encode(array('error'=>0, 'formid' => $_POST['formid'], 'error_text'=>$good_mail)));
	} else {
		echo(json_encode(array('error'=>2, 'formid' => $_POST['formid'], 'error_text'=>$bad_mail)));
	}

	if(defined('BACK_MAIL') && BACK_MAIL == 1 && !empty($_POST['email'])) {
		$mail->clearAddresses();
		$mail->Body = $repeat_mail;
		$mail->addAddress($_POST['email']);
		@$mail->send();
	}
}
?>