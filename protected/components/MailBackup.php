<?
Class MailBackup {
	public static function run ()
	{
		$frequency = 60*60*24; //раз в день
		$dir = 'assets';  //папка для временного файла
		$filename = md5("Всем привет :)"); //имя временного файла
		$dump_file = Yii::getPathOfAlias('webroot.assets').'/backup.sql.gz'.md5(mt_rand()); //временный файл бэкапа
		$path = $dir."/".$filename;	//полный путь
		$name = "Yii database backup"; //заголовок письма
		$url = $_SERVER['HTTP_HOST']; //URL сайта (используется в имени отправителя)
		$date = date("d.m.Y H-i-s"); //дата (используется в имени файла)
		$attachname = "backup_$url"."_"."$date.sql.gz"; //шаблон имени файла
		$to = "info@dekartmedia.ru"; //Кому 
		$from = "backup_master@$url"; //От кого
		$subject = "Бэкап $url за $date"; //Тема

		if (file_exists($path))
			$lastbackup = filemtime($path);
		else
		{
			fopen($path,"w"); 
			$lastbackup = filemtime($path);
		}

		if(time()-$lastbackup>$frequency)
		{
			Yii::import('ext.yii-database-dumper.SDatabaseDumper');
			$dumper = new SDatabaseDumper;
			file_put_contents($dump_file, gzencode($dumper->getDump()));
			$filename = $dump_file;

			//Формируем письмо
			$boundary = "---"; 
			$headers = "From: $name <$from>\nReply-To: $from\n";
			$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
			$body = "--$boundary\n";
			$body .= "Content-type: text/html; charset='utf-8'\n";
			$body .= "Content-Transfer-Encoding: quoted-printablenn";
			$body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
			$body .= "--$boundary\n";
			$file = fopen($filename, "r");
			$text = fread($file, filesize($filename));
			fclose($file);
			$body .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($filename)."?=\n"; 
			$body .= "Content-Transfer-Encoding: base64\n";
			$body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($attachname)."?=\n\n";
			$body .= chunk_split(base64_encode($text))."\n";
			$body .= "--".$boundary ."--\n";

			//шлем письмо, удаляем файл
			mail($to, $subject, $body, $headers);
			unlink($filename);

			//перезаписываем файл кеша
			unlink($path);
			fopen($path,"w"); 
		}
	}	
}