<?php

if ($_REQUEST['do'] == 'install') {

define('DATALIFEENGINE', true);
define('ROOT_DIR', dirname (__FILE__));
define('ENGINE_DIR', ROOT_DIR.'/engine');
define('INC_DIR', ENGINE_DIR.'/inc');

require_once ENGINE_DIR.'/classes/mysql.php';
require_once INC_DIR.'/include/functions.inc.php';
include ENGINE_DIR.'/data/dbconfig.php';
include ENGINE_DIR.'/data/config.php';

$db->query("INSERT INTO " .PREFIX . "_admin_sections (`name`, `title`, `descr`, `icon`, `allow_groups`) VALUES ('mystatus', 'Статус сериалов', 'Выводит и обновляет статусы сериалов', 'mystatus.png', '1,2')");
$db->query("ALTER TABLE `" .PREFIX . "_post` ADD `myshowsid` INT(11) NOT NULL;");

$content = <<<HTML
<html>
<head>
<title>Установка модуля MyStatus 1.0</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<meta name="robots" content="noindex,nofollow" />
<style type="text/css">
body, div, table, td, span, a {margin:0px; padding:0px;}
body, td {font-size:8pt; font-family:Tahoma; color:#000;}
div.border {width:600px !important; border-radius:7px; -moz-border-radius:7px; -webkit-border-radius:7px; border:4px solid rgba(0,0,0,0.5);}
div.header {padding:15px 15px; border-radius:5px 5px 0 0; -moz-border-radius:5px 5px 0 0; -webkit-border-radius:5px 5px 0 0; background:#ededed; border-bottom:1px solid #d5d5d5; color:#454545; font-size:14px;}
div.bottoms {text-align:right; padding:8px 15px; border-radius: 0 0 5px 5px; -moz-border-radius: 0 0 5px 5px; -webkit-border-radius: 0 0 5px 5px; background:#ededed; border-top:1px solid #d5d5d5;}
input.text {border:1px solid #BABABA; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; font-size:11px; padding:5px 5px;}
input.fbutton {border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; background:#C8D3D7; border:none; color:#454545; font-size:11px; font-weight:bold; padding:4px 5px 5px 5px; font-family:Tahoma; cursor:pointer;}
input.fbutton:hover {background:#4292BC; color:#fff;}
</style>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<tbody>
<tr>
<td valign="middle" align="center">
<!------>
<div class="border">
<div class="header" align="left">Установка модуля MyStatus 1.0</div>

<div id="page1">
<div align="left" style="font-size:9pt; padding:20px;">
<b>Установка завершена!</b><br>
Поздравляем! Вы установили модуль MyStatus 1.0
<br /><br /><br /><br /><br />
</div>
<div class="bottoms">
	<input class="fbutton" type="button" value="Выход" onclick="location.href='/'" />
</div>
</div>

</div>
<!------>
</td>
</tr>
</tbody>

</table>
</body>
</html>
HTML;


} else {

$content = <<<HTML
<html>
<head>
<title>Установка модуля MyStatus 1.0</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<meta name="robots" content="noindex,nofollow" />
<style type="text/css">
body, div, table, td, span, a {margin:0px; padding:0px;}
body, td {font-size:8pt; font-family:Tahoma; color:#000;}
div.border {width:600px !important; border-radius:7px; -moz-border-radius:7px; -webkit-border-radius:7px; border:4px solid rgba(0,0,0,0.5);}
div.header {padding:15px 15px; border-radius:5px 5px 0 0; -moz-border-radius:5px 5px 0 0; -webkit-border-radius:5px 5px 0 0; background:#ededed; border-bottom:1px solid #d5d5d5; color:#454545; font-size:14px;}
div.bottoms {text-align:right; padding:8px 15px; border-radius: 0 0 5px 5px; -moz-border-radius: 0 0 5px 5px; -webkit-border-radius: 0 0 5px 5px; background:#ededed; border-top:1px solid #d5d5d5;}
input.text {border:1px solid #BABABA; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; font-size:11px; padding:5px 5px;}
input.fbutton {border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; background:#C8D3D7; border:none; color:#454545; font-size:11px; font-weight:bold; padding:4px 5px 5px 5px; font-family:Tahoma; cursor:pointer;}
input.fbutton:hover {background:#4292BC; color:#fff;}
</style>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<tbody>
<tr>
<td valign="middle" align="center">
<!------>
<div class="border">
<div class="header" align="left">Установка модуля MyStatus 1.0</div>

<div id="page1">
<div align="left" style="font-size:9pt; padding:20px;">
<b>Добро пожаловать!</b><br>Этот мастер поможет вам установить модуль MyStatus 1.0<br><br>
<i>Для установки нажмите кнопку "Установить"</i>
<br /><br /><br /><br /><br />
</div>
<div class="bottoms">
	<form action="" method="post">
		<input type="hidden" name="do" value="install">
		<input class="fbutton" type="submit" value="Установить" />
	</form>
</div>
</div>

</div>
<!------>
</td>
</tr>
</tbody>

</table>
</body>
</html>
HTML;

}



echo $content;


?>