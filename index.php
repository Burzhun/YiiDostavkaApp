<?
//session_start();
//phpinfo();
defined('YII_DEBUG') or define('YII_DEBUG', $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ? true : false);
defined('CHERRY05') or define('CHERRY05', $_SERVER['HTTP_HOST']=='www.cherry05.ru' ? true : false);

defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 10);

define('WORKING_DIRECTORY', getcwd());
register_shutdown_function('customError');
function customError()
{

	chdir(WORKING_DIRECTORY);
	$arrStrErrorInfo = error_get_last();
	if ($arrStrErrorInfo['type'] != 8) {
		ob_start();
		print_r($arrStrErrorInfo);
		$print = ob_get_contents();
		ob_clean();
		file_put_contents('logs.txt', $print);
	}
}

	if($_SERVER['SERVER_NAME'] == 'dostavka.az' || $_SERVER['SERVER_NAME'] == 'www.dostavka.az'){
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter-az.php')) {
			include_once($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter-az.php');
            date_default_timezone_set('Asia/Baku');
			durRun();
			//exit;
		}
	}else{
		if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php')) {
			//exit;
			include_once($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php');
			durRun();
		}
	}
if (!YII_DEBUG) {
	if($_SERVER['SERVER_NAME']=='dostavka.az'){
		Header("Location: http://www.dostavka.az");
		exit;
	}
	if($_SERVER['HTTP_HOST']=='www.derbent.dostavka05.ru'){
		header("Location:http://derbent.dostavka05.ru");
		exit;
	}
	if($_SERVER['HTTP_HOST']=='www.kaspiysk.dostavka05.ru'){
		header("Location:http://kaspiysk.dostavka05.ru");
		exit;
	}
	if($_SERVER['HTTP_HOST']=='www.vladikavkaz.edostav.ru'){
		header("Location:http://vladikavkaz.edostav.ru");
		exit;
	}
	if($_SERVER['HTTP_X_REQUEST_SCHEME'] == "https"){
		exit;
	}
	if($_SERVER['SERVER_NAME']=='dostavka05.ru' && !isset($_SERVER['HTTP_X_HTTPS'])){
		Header("Location: http://www.dostavka05.ru".$_SERVER['REQUEST_URI']);
		exit;
	}
	if($_SERVER['SERVER_NAME']=='edostav.ru' && !isset($_SERVER['HTTP_X_HTTPS'])){
		Header("Location: http://www.edostav.ru".$_SERVER['REQUEST_URI']);
		exit;
	}


	if (substr_count($_SERVER['SERVER_NAME'], '20.gauss.z8.ru') != 0)
		Header("Location: http://www.dostavka05.ru" . $_SERVER['REQUEST_URI']);

	if (substr_count($_SERVER['SERVER_NAME'], 'xn--05-6kcajk8b3a1ak.xn--p1ai') != 0)
		Header("Location: http://www.dostavka05.ru" . $_SERVER['REQUEST_URI']);

	$uri = $_SERVER['REQUEST_URI']; // текущий URL
	if (strstr($uri, "index.php"))
		Header("Location: http://www.dostavka05.ru");


	if ($uri != "/") {
		if (substr($uri, -1) == "/")
			Header("Location: " . substr($uri, 0, -1));
	}
}
putenv('TZ=Europe/Moscow');

define('DS', DIRECTORY_SEPARATOR);

require_once dirname(__FILE__) . '/yii/framework/yiilite.php';
require_once __DIR__ . '/protected/components/WebApplication.php';

/**
 * Class Yii
 * @method static WebApplication app
 */



Yii::createApplication('WebApplication', dirname(__FILE__) . '/protected/config/main.php')->run();