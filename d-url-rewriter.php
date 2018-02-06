<?php
// Раздел настройки ЧПУ (Все пути начинаются с ведущего слеша от корневой директории)
  $aURLRewriter = array (
    '/restorany/francuzskaya' => '/restorany/francuzskaya-kuhnya',
  );
//Сквозные редиректы
  $aR301SkipCheck = array (
    '/magaziny/gastronomy'=>'/restorany/rolly',
	'/magaziny/dairy'=>'/restorany/francuzskaya-kuhnya',
  );
//Удаленные страницы
  $a410Response = array (
    '/test3',
    '/tests/',
  );
// Только замена ссылок
  $aURLRewriterOnly = array (
    '/magaziny/gastronomy'=>'/magaziny/gastronomyiya',
	'/magaziny/dairy'=>'/magaziny/molochnye',
  );
  define('DUR_DEBUG', 0);                   //Включение режима отладки (вывод инфо в конце исходного текста на странице)
  define('DUR_PREPEND_APPEND', 0);          //Единая точка входа (.htaccess) Не рекомендуется
  define('DUR_BASE_ROOT', 0);               //Прописать принудительно <base href="http://domain.com/"> Бывает полезно при ссылках вида href="?page=2". При указании строки, пропишет ее
  define('DUR_LINK_PARAM', 0);              //Дописать путь перед ссылками вида href="?page=2"
  define('DUR_ANC_HREF', 0);                //Пофиксить ссылки вида href="#ancor"
  define('DUR_ROOT_HREF', 1);               //Пофиксить ссылки вида href="./"
  define('DUR_REGISTER_GLOBALS', 0);        //Регистрировать глобальные переменные
  define('DUR_SKIP_POST', 1);               //Не выполнять подмену при запросе POST
  define('DUR_CMS_TYPE', 'NONE');           //Включение особенностей для CMS, возможные значения: NONE, NETCAT, JOOMLA, HTML, DRUPAL, WEBASYST, ICMS
  define('DUR_OUTPUT_COMPRESS', 'NONE');    //Сжатие выходного потока, возможные значения: NONE, GZIP, DEFLATE, AUTO, SKIP
  define('DUR_SUBDOMAINS', 0);              //Обрабатывать поддомены, указываем здесь основной домен!
  define('DUR_SKIP_USERAGENT', '#^(|mirror)$#'); //Не выполнять редиректы при указанном HTTP_USER_AGENT (регулярка)
  define('DUR_SKIP_URLS', '#^/_?(admin|manag|bitrix|indy|cms|phpshop)#siU');  //Skip URLS
  define('DUR_FIX_CONTLEN', 1);             //Фиксить Content-Length
  define('DUR_PATHINFO', 0);                //Регистрировать переменные для передачи вида /index.php/uri

























// Раздел обработки
  define('DUR_TIME_START', microtime(true));
  define('DUR_REQUEST_URI', $_SERVER['REQUEST_URI']);
  define('DUR_HTTP_HOST', $_SERVER['HTTP_HOST']);
  define('DUR_FULL_URI', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  define('BX_COMPRESSION_DISABLED', true); //Hack for bitrix
  define('DUR_SKIP_THIS', preg_match(DUR_SKIP_URLS, DUR_REQUEST_URI, $aM));
  define('DUR_SKIP_R301', !isset($_SERVER['HTTP_USER_AGENT']) || preg_match(DUR_SKIP_USERAGENT, $_SERVER['HTTP_USER_AGENT']));
  if (defined('DUR_DEBUG') && DUR_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('error_reportings', E_ALL);
  }
  if (isset($_GET['_openstat'])) {
    unset($_GET['_openstat']);
    unset($_REQUEST['_openstat']);
    unset($HTTP_GET_VARS['_openstat']);
    $_SERVER['REQUEST_URI'] = preg_replace('%[&?]_openstat=[^&]+(&|$)%siU', '', $_SERVER['REQUEST_URI']);
  }
  if (isset($a410Response[$_SERVER['REQUEST_URI']]) && !DUR_SKIP_THIS) {
    header('HTTP/1.0 410 Gone');
    echo '<h1 style="font-size: 18pt;">Ошибка 410</h1><p>Страница удалена</p><p style="text-align: right; margin: 10px;"><a href="/">На главную</a></p>';
    exit;
  }
  if (isset($aR301SkipCheck[$_SERVER['REQUEST_URI']]) && !DUR_SKIP_THIS && !DUR_SKIP_R301) {
    if (!defined('DUR_SKIP_POST') || !DUR_SKIP_POST || (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')) {
      header('Location: ' . $aR301SkipCheck[$_SERVER['REQUEST_URI']], true, 301);
      exit;
    }
  }
  foreach ($aURLRewriter as $sKey => $sVal) {
    $aURLRewriter[$sKey] = str_replace(
      array('р', 'у', 'к', 'е', 'н', 'х', 'в', 'а', 'о', 'ч', 'с', 'м', 'и', 'т', ' '),
      array('p', 'y', 'k', 'e', 'h', 'x', 'b', 'a', 'o', '4', 'c', 'm', 'n', 't', '_'),
      $sVal
    );
    if (!defined('DUR_SEO_REQUEST_URI') && ($sVal == $_SERVER['REQUEST_URI'])) {
      define('DUR_SEO_REQUEST_URI', $sKey);
    }
  }
  $aURFlip = array_flip($aURLRewriter);
  //Многократная вложенность замен (до 10)
  for ($i = 0; $i < 10; $i++) {
    foreach ($aURLRewriter as $sFrom => $sTo) {
      if (isset($aURLRewriter[$sTo])) {
        $aURLRewriter[$sFrom] = $aURLRewriter[$sTo];
        $aURFlip[$aURLRewriter[$sTo]] = $sFrom;
      }
    }
  }
  //Joomla hack! (Против защиты от register globals)
  if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'JOOMLA')) {
    $_SERVER['dur'] = array($aURLRewriter, $aURFlip, $aURLRewriterOnly);
  }
  //Единая точка входа
  if (defined('DUR_PREPEND_APPEND') && DUR_PREPEND_APPEND && !DUR_SKIP_THIS) {
    durRun ();
  }


// Функции
  function durRun () {
    if (defined('DUR_RUNNED')) return;
//    if (isset())
    define('DUR_RUNNED', 1);
    durR301();
    ob_start('durLinkChanger');
    durIFRewrite();
  }

  function dur404 () {
    $aPages404 = array('404.php', '404.html', '404.htm', 'index.php', 'index.html', 'index.htm');
    header('HTTP/1.1 404 Not found');
    foreach ($aPages404 as $sPage404) {
      if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $sPage404)) {
        include($_SERVER['DOCUMENT_ROOT'] . '/' . $sPage404);
        exit;
      }
    }
    echo '<h1>Ошибка 404</h1><p>Страница не найдена</p><p style="text-align: right; margin: 10px;"><a href="/">На главную</a></p>';
    exit;
  }

  function durRewrite ($sURL) {
    global $QUERY_STRING, $REQUEST_URI, $REDIRECT_URL, $HTTP_GET_VARS;
    define('DUR_DEBUG_BEFORE', "SERVER:\n" . durDebugVar($_SERVER) . "\n\nGET:\n" . durDebugVar($_GET) . "\n\nREQUEST:\n" . durDebugVar($_REQUEST) . "\n");
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'WEBASYST')) {
      $sURL = '/?__furl_path=' . substr($sURL, 1) . '&frontend=1';
    }
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'ICMS')) {
      $sURL = '/index.php?path=' . substr($sURL, 1, -5) . '&frontend=1';
    }
    $QUERY_STRING = strpos($sURL, '?') ? substr($sURL, strpos($sURL, '?') + 1) : '';
    $REQUEST_URI = $sURL;
    $REDIRECT_URL = $sURL;
    $_SERVER['QUERY_STRING'] = $QUERY_STRING;
    $_SERVER['REDIRECT_URL'] = $sURL;
    $_SERVER['REQUEST_URI'] = $sURL;
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'NETCAT')) {
      putenv('REQUEST_URI=' . $sURL);
    }
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'DRUPAL')) {
      $_GET['q'] = substr($sURL, 1);
      $_REQUEST['q'] = substr($sURL, 1);
    }
    if (preg_match_all('%[\?&]([^\=]+)\=([^&]*)%', $sURL, $aM)) {
      $aParams = array();
      foreach ($aM[1] as $iKey => $sName) {
        $sVal = urldecode($aM[2][$iKey]);
        if (preg_match('#^(.+)\[\]$#siU', $sName, $aMatch)) {
          $aParams[$aMatch[1]][] = $sVal;
        }
        elseif (preg_match('#^(.+)\[([\w-]+)\]$#siU', $sName, $aMatch)) {
          $aParams[$aMatch[1]][$aMatch[2]] = $sVal;
        }
        else {
          $aParams[$sName] = $sVal;
        }
      }
      foreach ($aParams as $sKey => $mVal) {
        $_GET[$sKey] = $mVal;
        $HTTP_GET_VARS[$sKey] = $mVal;
        $_REQUEST[$sKey] = $mVal;
        if (defined('DUR_REGISTER_GLOBALS') && DUR_REGISTER_GLOBALS) {
          global $$sKey;
          $$sKey = $mVal;
        }
      }
    }
    if (defined('DUR_PATHINFO') && DUR_PATHINFO) {
      $_SERVER['PATH_INFO'] = substr($sURL, 1);
      $_SERVER['PHP_SELF'] = $sURL;
    }
    if (DUR_CMS_TYPE == 'HTML') {
      $sFName = $sURL;
      if ($iPos = strpos($sFName, '?')) {
        $sFName = substr($sFName, 0, $iPos);
      }
      if (file_exists($_SERVER['DOCUMENT_ROOT'] . $sFName)) {
        include($_SERVER['DOCUMENT_ROOT'] . $sFName);
        exit;
      }
      else {
        dur404();
      }
    }
  }

  function durIFRewrite () {
    global $aURFlip, $aURLRewriter;
    if (DUR_SKIP_THIS) return;
    $sKey = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (defined('DUR_SUBDOMAINS') && DUR_SUBDOMAINS && isset($aURFlip[$sKey])) {
      if (!defined('DUR_ORIG_RURI')) {
        define('DUR_ORIG_RURI', $aURFlip[$sKey]);
      }
      durRewrite ($aURFlip[$sKey]);
    }
    elseif (isset($aURFlip[$_SERVER['REQUEST_URI']])) {
      if (!defined('DUR_ORIG_RURI')) {
        define('DUR_ORIG_RURI', $aURFlip[$_SERVER['REQUEST_URI']]);
      }
      durRewrite ($aURFlip[$_SERVER['REQUEST_URI']]);
    }
    elseif (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'HTML')) {
      if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
        durRewrite ($_SERVER['REQUEST_URI']);
      }
      else {
        dur404();
      }
    }
  }

  function durR301 () {
    global $aURFlip, $aURLRewriter;
    if (DUR_SKIP_THIS || DUR_SKIP_R301) return;
    if (defined('DUR_SKIP_POST') && DUR_SKIP_POST && (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')) {
      return;
    }
    if (isset($aURLRewriter[$_SERVER['REQUEST_URI']])) {
      if ('http://' . DUR_HTTP_HOST == trim($aURLRewriter[$_SERVER['REQUEST_URI']], '/')) {
        return;
      }
      header('Location: ' . $aURLRewriter[$_SERVER['REQUEST_URI']], true, 301);
      exit;
    }
  }

  function durRExpEscape ($sStr) {
    return str_replace(array('?', '.', '-', ':', '%', '[', ']', '(', ')'), array('\\?', '\\.', '\\-', '\\:', '\\%', '\\[', '\\]', '\\(', '\\)'), $sStr);
  }

  function durReplaceOnceLink ($sLink, $sNewLink, $sContent) {
    $sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*' . durRExpEscape ($sLink) . '([#\'"\s>])%siU', '$1' . $sNewLink . '$2', $sContent);
    $sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*' . durRExpEscape (str_replace('&', '&amp;', $sLink)) . '([#\'"\s>])%siU', '$1' . $sNewLink . '$2', $sContent);
    return $sContent;
  }

  function durReplaceLink ($sHost, $sBase, $sFrom, $sTo, $sContent) {
    $sNewLink = $sTo;
  // Link type: "http://domain/link"
    $sContent = durReplaceOnceLink ('http://' . $sHost . $sFrom, $sNewLink, $sContent);
  // Link type: "https://domain.com/link"
    $sContent = durReplaceOnceLink ('https://' . $sHost . $sFrom, $sNewLink, $sContent);
  // Link type: "//domain.com/link"
    $sContent = durReplaceOnceLink ('//' . $sHost . $sFrom, $sNewLink, $sContent);
  // Link type: "/link"
    $sContent = durReplaceOnceLink ($sFrom, $sNewLink, $sContent);
  // Link type: "./link"
    $sContent = durReplaceOnceLink ('.' . $sFrom, $sNewLink, $sContent);
  // Link type: "link" (Calc fromlink)
    $aLink = explode('/', $sFrom);
    $aBase = empty($sBase) ? array('') : explode('/', str_replace('//', '/', '/' . $sBase));
    $sReplLnk = '';
    for ($i = 0; $i < max(count($aLink), count($aBase)); $i++) {
      if (isset($aBase[$i]) && isset($aLink[$i])) {
        if ($aLink[$i] == $aBase[$i]) {
          continue;
        }
        else {
          for ($j = $i; $j < count($aBase); $j++) {
            $sReplLnk .= '../';
          }
          for ($j = $i; $j < count($aLink); $j++) {
            $sReplLnk .= $aLink[$j] . '/';
          }
          break;
        }
      }
      elseif (isset($aLink[$i])) {
        $sReplLnk .= $aLink[$i] . '/';
      }
      elseif (isset($aBase[$i])) {
        $sReplLnk .= '../';
      }
    }
    $sReplLnk = preg_replace('%/+%', '/', $sReplLnk);
    $sReplLnk2 = trim($sReplLnk, '/');
    $sReplLnk3 = rtrim($sReplLnk2, '.');
    if (strlen($sReplLnk) > 1) {
      $sContent = durReplaceOnceLink ($sReplLnk, $sNewLink, $sContent);
      $sContent = durReplaceOnceLink ('./' . $sReplLnk, $sNewLink, $sContent);
    }
    if (($sReplLnk2 != $sReplLnk) && (strlen($sReplLnk2) > 1)) {
      $sContent = durReplaceOnceLink ($sReplLnk2, $sNewLink, $sContent);
      $sContent = durReplaceOnceLink ('./' . $sReplLnk2, $sNewLink, $sContent);
    }
    if (($sReplLnk3 != $sReplLnk2) && (strlen($sReplLnk3) > 1)) {
      $sContent = durReplaceOnceLink ($sReplLnk3, $sNewLink, $sContent);
      $sContent = durReplaceOnceLink ('./' . $sReplLnk3, $sNewLink, $sContent);
    }
    return $sContent;
  }

  function durGZDecode($sS) {
    $sM = ord(substr($sS,2,1)); $iF = ord(substr($sS,3,1));
    if ($iF & 31 != $iF) return null;
    $iLH = 10; $iLE = 0;
    if ($iF & 4) {
      if ($iL - $iLH - 2 < 8) return false;
      $iLE = unpack('v',substr($sS,8,2));
      $iLE = $iLE[1];
      if ($iL - $iLH - 2 - $iLE < 8) return false;
      $iLH += 2 + $iLE;
    }
    $iFCN = $iFNL = 0;
    if ($iF & 8) {
      if ($iL - $iLH - 1 < 8) return false;
      $iFNL = strpos(substr($sS,8+$iLE),chr(0));
      if ($iFNL === false || $iL - $iLH - $iFNL - 1 < 8) return false;
      $iLH += $iFNL + 1;
    }
    if ($iF & 16) {
      if ($iL - $iLH - 1 < 8) return false;
      $iFCN = strpos(substr($sS,8+$iLE+$iFNL),chr(0));
      if ($iFCN === false || $iL - $iLH - $iFCN - 1 < 8) return false;
      $iLH += $iFCN + 1;
    }
    $sHCRC = '';
    if ($iF & 2) {
      if ($iL - $iLH - 2 < 8) return false;
      $calccrc = crc32(substr($sS,0,$iLH)) & 0xffff;
      $sHCRC = unpack('v', substr($sS,$iLH,2));
      $sHCRC = $sHCRC[1];
      if ($sHCRC != $calccrc) return false;
      $iLH += 2;
    }
    $sScrc = unpack('V',substr($sS,-8,4));
    $sScrc = $sScrc[1];
    $iSZ = unpack('V',substr($sS,-4));
    $iSZ = $iSZ[1];
    $iLBD = $iL-$iLH-8;
    if ($iLBD < 1) return null;
    $sB = substr($sS,$iLH,$iLBD);
    $sS = '';
    if ($iLBD > 0) {
      if ($sM == 8) $sS = gzinflate($sB);
      else return false;
    }
    if ($iSZ != strlen($sS) || crc32($sS) != $sScrc) return false;
    return $sS;
  }

  function durGZDecode2($sS) {
    $iLen = strlen($sS);
    $sDigits = substr($sS, 0, 2);
    $iMethod = ord(substr($sS, 2, 1));
    $iFlags  = ord(substr($sS, 3, 1));
    if ($iFlags & 31 != $iFlags) return false;
    $aMtime = unpack('V', substr($sS, 4, 4));
    $iMtime = $aMtime[1];
    $sXFL   = substr($sS, 8, 1);
    $sOS    = substr($sS, 8, 1);
    $iHeaderLen = 10;
    $iExtraLen  = 0;
    $sExtra     = '';
    if ($iFlags & 4) {
      if ($iLen - $iHeaderLen - 2 < 8) return false;
      $iExtraLen = unpack('v', substr($sS, 8, 2));
      $iExtraLen = $iExtraLen[1];
      if ($iLen - $iHeaderLen - 2 - $iExtraLen < 8) return false;
      $sExtra = substr($sS, 10, $iExtraLen);
      $iHeaderLen += 2 + $iExtraLen;
    }
    $iFilenameLen = 0;
    $sFilename = '';
    if ($iFlags & 8) {
      if ($iLen - $iHeaderLen - 1 < 8) return false;
      $iFilenameLen = strpos(substr($sS, $iHeaderLen), chr(0));
      if ($iFilenameLen === false || $iLen - $iHeaderLen - $iFilenameLen - 1 < 8) return false;
      $sFilename = substr($sS, $iHeaderLen, $iFilenameLen);
      $iHeaderLen += $iFilenameLen + 1;
    }
    $iCommentLen = 0;
    $sComment = '';
    if ($iFlags & 16) {
      if ($iLen - $iHeaderLen - 1 < 8) return false;
      $iCommentLen = strpos(substr($sS, $iHeaderLen), chr(0));
      if ($iCommentLen === false || $iLen - $iHeaderLen - $iCommentLen - 1 < 8) return false;
      $sComment = substr($sS, $iHeaderLen, $iCommentLen);
      $iHeaderLen += $iCommentLen + 1;
    }
    $sCRC = '';
    if ($iFlags & 2) {
      if ($iLen - $iHeaderLen - 2 < 8) return false;
      $sCalcCRC = crc32(substr($sS, 0, $iHeaderLen)) & 0xffff;
      $sCRC = unpack('v', substr($sS, $iHeaderLen, 2));
      $sCRC = $sCRC[1];
      if ($sCRC != $sCalcCRC) return false;
      $iHeaderLen += 2;
    }
    $sDataCRC = unpack('V', substr($sS, -8, 4));
    $sDataCRC = sprintf('%u', $sDataCRC[1] & 0xFFFFFFFF);
    $iSize = unpack('V', substr($sS, -4));
    $iSize = $iSize[1];
    $iBodyLen = $iLen - $iHeaderLen - 8;
    if ($iBodyLen < 1) return false;
    $sBody = substr($sS, $iHeaderLen, $iBodyLen);
    $sS = '';
    if ($iBodyLen > 0) {
      switch ($iMethod) {
        case 8: $sS = gzinflate($sBody); break;
        default: return false;
      }
    }
    $sCRC  = sprintf('%u', crc32($sS));
    $bCRCOK = ($sCRC == $sDataCRC);
    $bLenOK = ($iSize == strlen($sS));
    if (!$bLenOK || !$bCRCOK) return false;
    return $sS;
  }

  function durGZCheck ($sContent) {
    $iLen = strlen($sContent);
    if ($iLen < 18 || strcmp(substr($sContent, 0, 2), "\x1f\x8b")) {
      return $sContent;
    }
    $sData = durGZDecode2($sContent);
    if (!$sData) {
      $sData = durGZDecode($sContent);
    }
    return $sData ? $sData : $sContent;
  }

  function durOutputCompress ($sContent) {
    if (!defined('DUR_OUTPUT_COMPRESS')) {
      define('DUR_OUTPUT_COMPRESS', 'SKIP');
    }
    if (DUR_OUTPUT_COMPRESS == 'SKIP') {
      return $sContent;
    }
    $aAccept = array();
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
      $aAccept = array_map('trim', explode(',', strtolower($_SERVER['HTTP_ACCEPT_ENCODING'])));
    }
    $bGZIP = in_array('gzip', $aAccept) && function_exists('gzencode');
    $bDEFL = in_array('deflate', $aAccept) && function_exists('gzdeflate');
    $sCompress = DUR_OUTPUT_COMPRESS;
    if ((!$bGZIP && !$bDEFL) || (!$bGZIP && ($sCompress == 'GZIP')) || (!$bDEFL && ($sCompress == 'DEFLATE'))) {
      $sCompress = 'NONE';
    }
    if ($sCompress == 'AUTO') {
      $sCompress = $bGZIP ? 'GZIP' : ($bDEFL ? 'DEFLATE' : 'NONE');
    }
    switch ($sCompress) {
      case 'GZIP':
        header('Content-Encoding: gzip');
        $sContent = gzencode($sContent);
        break;
      case 'DEFLATE':
        header('Content-Encoding: deflate');
        $sContent = gzdeflate($sContent, 9);
        break;
      default:
     //   header('Content-Encoding: none');
    }
    return $sContent;
  }

  function durDebugEscape ($sText) {
    return str_replace(array('--', '-->'), array('==', '==}'), $sText);
  }

  function durDebugVar ($mVar, $sPref = '  ') {
    $Ret = '';
    foreach ($mVar as $sKey => $sVal) {
      $Ret .= "{$sPref}{$sKey} => ";
      if (is_array($sVal)) {
        $Ret .= "ARRAY (\n" . durDebugVar($sVal, $sPref.'  ') . "{$sPref})\n";
      }
      else {
        $Ret .= "{$sVal}\n";
      }
    }
    return durDebugEscape($Ret);
  }

  function durLinkChanger ($sContent) {
    global $aURFlip, $aURLRewriter, $aURLRewriterOnly;
    if (DUR_SKIP_THIS) return $sContent;
    $iTimeStart = microtime(true);
    $sContent = durGZCheck($sContent);
    if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'JOOMLA') && isset($_SERVER['dur'])) {
      $aURLRewriter = $_SERVER['dur'][0];
      $aURFlip = $_SERVER['dur'][1];
      $aURLRewriterOnly = $_SERVER['dur'][2];
      unset($_SERVER['dur']);
    }
    $aURLRewriter = array_merge($aURLRewriter, $aURLRewriterOnly);
    //Base path
    if (preg_match('%<[^<>]*base[^<>]*href=[\'"]?([\w_\-\.\:/]+)[\'"\s>][^<>]*>%siU', $sContent, $aM)) {
      $sBase = $aM[1];
      $sBaseHref = $aM[1]; 
    }
    else {
      $sBase = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/'));
      $sBaseHref = ''; 
    }
    $sBase = trim(str_replace(array('http://', 'https://'), '', $sBase), '/');
    $aHosts = array($_SERVER['HTTP_HOST']);
    if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
      $aHosts[] = substr($_SERVER['HTTP_HOST'], 4);
    }
    $sExtHost = str_replace('www.www.', 'www.', 'www.' . DUR_SUBDOMAINS);
    $aHosts[] = $sExtHost;
    $aHosts[] = str_replace('www.', '', $sExtHost);
    $aHosts = array_unique($aHosts);
    $sBase = str_replace($aHosts, '', $sBase);
    //href="?..."
    if (defined('DUR_LINK_PARAM') && defined('DUR_ORIG_RURI') && DUR_LINK_PARAM) {
      $sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*([?#].*[#\'"\s>])%siU', '$1' . DUR_ORIG_RURI . '$2', $sContent);
    }
    //Main cicle
    foreach ($aURLRewriter as $sFrom => $sTo) {
      foreach ($aHosts as $sHost) {
        $sContent = durReplaceLink ($sHost, $sBase, $sFrom, $sTo, $sContent);
      }
    }
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php')) {
      include_once($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php');
    }
    if ((defined('DUR_BASE_ROOT') && DUR_BASE_ROOT) || !empty($sBaseHref)) {
      if (strlen(DUR_BASE_ROOT) > 7) {
        $sBaseHref = DUR_BASE_ROOT;
      }
      else {
        $sBaseHref = (empty($sBaseHref) ? 'http://' . $aHosts[0] : $sBaseHref) . '/';
      }
      $sBaseHref = '<base href="' . $sBaseHref . '">';
      $sBaseHref = trim($sBaseHref, '/') . '/';
      $sContent = preg_replace('%<base[^>]+href[^>]+>%siU', '', $sContent);
      $sContent = preg_replace('%(<head[^>]*>)%siU', "$1" . $sBaseHref, $sContent);
    }
    if (defined('DUR_ANC_HREF') && DUR_ANC_HREF) {
      $sContent = preg_replace('%(href\s*=\s*["\']+)(#\w)%siU', '$1' . DUR_REQUEST_URI . '$2', $sContent);
    }
    if (defined('DUR_ROOT_HREF') && DUR_ROOT_HREF) {
      $sContent = preg_replace('%(href\s*=\s*["\']*)\./(\w)%siU', '$1http://' . $_SERVER['HTTP_HOST'] . $sBase . '/$2', $sContent);
    }
    if (function_exists('durOtherReplacer')) {
      $sContent = durOtherReplacer ($sContent);
    }
    if (defined('DUR_DEBUG') && DUR_DEBUG) {
      $sContent .= "\n<!--\n";
      if (defined('DUR_DEBUG_BEFORE') && DUR_DEBUG_BEFORE) {
        $sContent .= " ===== VARS BEFORE REWRITE =====\n\n" . DUR_DEBUG_BEFORE;
      }
      $sContent .= "===== VARS AFTER REWRITE =====\n\nSERVER:\n" . durDebugVar($_SERVER) . "\n\nGET:\n" . durDebugVar($_GET) . "\n\nREQUEST:\n" . durDebugVar($_REQUEST) . "\n";
      $sContent .= "\nCONSTANTS:\n" .
                   '  DUR_REQUEST_URI     => ' . durDebugEscape(DUR_REQUEST_URI) . "\n" .
                   '  DUR_HTTP_HOST       => ' . durDebugEscape(DUR_HTTP_HOST) . "\n" .
                   '  DUR_FULL_URI        => ' . durDebugEscape(DUR_FULL_URI) . "\n" .
                   '  DUR_ORIG_RURI       => ' . (defined('DUR_ORIG_RURI') ? durDebugEscape(DUR_ORIG_RURI) : 'NOT-SET') . "\n" .
                   '  DUR_SEO_REQUEST_URI => ' . (defined('DUR_SEO_REQUEST_URI') ? durDebugEscape(DUR_SEO_REQUEST_URI) : 'NOT-SET') . "\n";
      $iTimeNow = microtime(true);
      $iTimeAll = ($iTimeNow - DUR_TIME_START) / 1000;
      $iTimeContent = ($iTimeStart - DUR_TIME_START) / 1000;
      $iTimeLinks = ($iTimeNow - $iTimeStart) / 1000;
      $sContent .= "\nTIME:\n" . 
                   '  ALL: ' . number_format($iTimeAll, 8) . " sec. (100%)\n" .
                   '  CMS: ' . number_format($iTimeContent, 8) . ' sec. (' . number_format($iTimeContent / $iTimeAll * 100, 2)  . "%)\n" . 
                   '  DUR: ' . number_format($iTimeLinks, 8) . ' sec. (' . number_format($iTimeLinks / $iTimeAll * 100, 2)  . "%)\n";
      $sContent .= '-->';
    }
    $sContent = durOutputCompress($sContent);
    if (defined('DUR_FIX_CONTLEN') && DUR_FIX_CONTLEN) {
      header('Content-Length: ' . strlen($sContent));
    }
    return $sContent;
  }

  function durOtherReplacer ($sContent) {


/*
if($_SERVER['REQUEST_URI'] == '/restorany'){
	$sContent = str_replace('Заказ и доставка еды по Махачкале','Рестораны Махачкалы',$sContent);
	$sContent = str_replace('<p>Доставка еды на дом или доставка еды в офис — это прекрасный способ быстро, вкусно и полезно пообедать на работе с коллегами или поужинать дома после тяжелого дня. Заказав еду на дом, можно также организовать настоящий романтический вечер с любимым человеком, не тратя время на приготовление ужина. А еда в офис поможет скрасить трудности рабочего дня за вкусным обедом в компании коллег. Сайт Доставка05 помогает оперативно выполнить каждый поступивший заказ на доставку великолепной еды на дом или в офис в Махачкале.</p>','<p>Сервис Доставка05 объединяет различные службы доставки продуктов питания. Блюда различных кухонь мира будут оперативно доставлены на дом или в офис. Стоимость заказа зависит от выбранного кафе или ресторана.</p>
<h2>Как воспользоваться ресурсом?</h2>
<p>На странице представлены те <strong>рестораны</strong> Махачкалы, которые участвуют в Единой системе заказов. К каждому прилагается краткая карточка, где указана стоимость и время доставки. Подробные сведения, полное меню, форма заказа представлены на персональной странице. </p>
<p>Поиск соответствующих запросу ресторанов осуществляется при помощи фильтра. В нем можно задать:</p>
<ul>
<li>конкретное блюдо — пиццу, суши, шашлык, бургер и т. д.;</li>
<li>кухню — русскую, европейскую, украинскую, японскую и пр.;</li>
<li>дополнительные критерии — бесплатную доставку, отбор кафе в Махачкале и Каспийске, не устанавливающих минимальную сумму заказа и т. д.</li>
</ul>
<p>Алгоритм поиска учитывает введенные данные и отбирает заведения, наиболее отвечающие требованиям. Информация об акциях и спецпредложениях публикуется на соответствующей странице, доступ к ней можно получить после регистрации. </p>
<p> Доставка05 отвечает за все рестораны в базе. При возникновении несоответствия заявленных данных просьба обращаться к оператору сервиса. Все вопросы будут решены в ближайшее время согласно пожеланиям заказчика. </p>
<p>Перечень кафе и пиццерий постоянно расширяется — мы предлагаем выгодные условия сотрудничества: дополнительную рекламную площадку, собственное представительство в Сети, размещение объявлений в прессе и т. д.</p>',$sContent);
	}
*/


if($_SERVER['REQUEST_URI'] == '/'){
	$sContent = str_replace('<!--text_content-->','sdgdsgsdg',$sContent);
	}


    return $sContent;
  }






/* Подключение в начале файла

// ЧПУ ---
  if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php')) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php');
    durRun ();
  }
// --- ЧПУ

/* Для поддоменов неплохо было прописывать

RewriteCond %{HTTP_HOST} ^www.(.{4,}.nickon.ru)$
RewriteRule ^(.*)$ http://%1/$1 [R=301,L]

RewriteCond %{HTTP_HOST} ^(.{4,}).nickon.ru$
RewriteRule ^robots\.txt$ robots-%1.txt [L]

*/


/* Подключение с единой точкой входа
RemoveHandler .html .htm
AddType application/x-httpd-php .php .htm .html .phtml
php_value auto_prepend_file "/d-url-rewriter.php"
*/

