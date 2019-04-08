<?php
/*
=============================================================================
Serial Status - Статус выхода сериала, сохранения настроек
=============================================================================
Автор хака: Максим Гардер
-----------------------------------------------------
URL: http://maxim-harder.de/
-----------------------------------------------------
email: info@maxim-harder.de
-----------------------------------------------------
skype: maxim_harder
=============================================================================
Файл:  engine/inc/mystatus.php
=============================================================================
*/
if( !defined( 'DATALIFEENGINE' ) ) die( "You are a fucking faggot!" );

# Функции для работы с панелью == START.
function showRow($title = "", $description = "", $field = "") {
	echo "<tr><td class=\"col-xs-10 col-sm-6 col-md-7\"><h6>{$title}</h6><span class=\"note large\">{$description}</span></td><td class=\"col-xs-2 col-md-5 settingstd\">{$field}</td></tr>";
}
function showRow2($title = "", $description = "", $field = "") {
	echo "<tr><td colspan=\"2\" class=\"col-xs-10 col-sm-6 col-md-7\"><h6>{$title}</h6><span class=\"note large\">{$description}</span></td></tr>";
}
function showInput($name, $value) {
	return "<input type=text style=\"width: 400px;text-align: center;\" name=\"save_con[{$name}]\" value=\"{$value}\" size=20>";
}
function makeCheckBox($name, $selected, $flag = true) {
	$selected = $selected ? "checked" : "";
	if($flag)
		echo "<input class=\"iButton-icons-tab\" type=\"checkbox\" name=\"$name\" value=\"1\" {$selected}>";
	else
		return "<input class=\"iButton-icons-tab\" type=\"checkbox\" name=\"$name\" value=\"1\" {$selected}>";
}
function showForm($title = "", $field = "") {
	echo "<div class=\"form-group\"><label class=\"control-label col-xs-2\">{$title}</label><div class=\"col-xs-10\">{$field}</div></div>";
}
function makeDropDown($value, $name, $selected) {
	$output = "<select class=\"uniform\" name=\"save_con[$name]\">\r\n";
	foreach ( $value as $values => $description ) {
		$output .= "<option value=\"{$values}\"";
		if( $selected == $values ) {
			$output .= " selected ";
		}
		$output .= ">$description</option>\n";
	}
	$output .= "</select>";
	return $output;
}
# Функции для работы с панелью == END.
include ENGINE_DIR . "/data/mystatus.php";
switch($action):
	case "config":
		echoheader( "<i class=\"icon-wrench\"></i> Статус релиза", "Настройки модуля" );
echo <<<HTML
		<form action="$PHP_SELF?mod=mystatus&action=save&for=config" method="post">
			<div id="setting" class="box">
				<div class="box-header"><div class="title">Настройки</div></div>
				<div class="box-content">
					<table class="table table-normal">
						<thead>
HTML;
							showRow2("Основные настройки", "Включаем и выключаем модуль");
echo <<<HTML
						</thead>
						<tbody>
HTML;
							showRow("Включить модуль?", "Включаем-выключаем", makeCheckBox( "save_con[onof]", ($mystatus_cfg['onof'] == 1) ? true : false, false ));
							showRow("Доп. поле со статусом", "Укажите поле, где на данный момент выводится статус сериала, к примеру: status (без xfvalue_).", showInput("xfield", $mystatus_cfg['xfield']));
							showRow("Выводить новинки?", "Если включено, то будет показывать значение, что сериал новый. Но новый на MyShows.me", makeCheckBox( "save_con[news]", ($mystatus_cfg['news'] == 1) ? true : false, false ));
							showRow("Выводить значение \"Пилотная серия\"?", "Если включено, то будет указывать на пилотную серию сезона", makeCheckBox( "save_con[pilots]", ($mystatus_cfg['pilots'] == 1) ? true : false, false ));
echo <<<HTML
						</tbody>
					</table>
					<table class="table table-normal">
						<thead>
HTML;
							showRow2("Настройки полей и их значений", "Значения, которые будут выводится в новости");
echo <<<HTML
						</thead>
						<tbody>
HTML;
							showRow("Значение, если сериал выходит", "Он так-же может продолжать выходить, или же ушёл на сезонную паузу, к примеру: Снимается", showInput("onair", $mystatus_cfg['onair']));
							showRow("Значение, если сериал новый", "Будет выводить, если добавляется новый сериал в базу MyShows, к примеру: Новинка", showInput("new", $mystatus_cfg['new']));
							showRow("Значение, если серия пилотная", "Значение, если сезону дали шанс и первая серия должны быть показательной. К Примеру: Пилотная серия", showInput("pilot", $mystatus_cfg['pilot']));
							showRow("Значение, если сериал был закрыт или закончился", "Выводит значение, если сериал официально закончился или был закрыт из-за низких рейтингов. К примеру: Закрыт", showInput("closed", $mystatus_cfg['closed']));
							showRow("Значение, если сериал был отправлен на перерыв", "Если сериал отправляют в отпуск на длительный срок - выводится это значение. К примеру: Приостановлен", showInput("pause", $mystatus_cfg['pause']));
							showRow("Значение, если судьба сериала неизвестна", "Если сериал находится под вопросом продолжения - ставится это значение. К примеру: Под вопросом", showInput("tbd", $mystatus_cfg['tbd']));
							showRow("Значение, если статус сериала неизвестен", "Если для сериала в исходнике нет данных, то выводится это поле. К примеру: Не определено", showInput("none", $mystatus_cfg['none']));
echo <<<HTML
						</tbody>
					</table>
					<table class="table table-normal">
						<thead>
HTML;
							showRow2("Настройки уведомлений", "");
echo <<<HTML
						</thead>
						<tbody>
HTML;
							showRow("Отправлять уведомления?", "Если нет, то нижние настройки бесполезны", makeCheckBox( "save_con[post]", ($mystatus_cfg['post'] == 1) ? true : false, false ));
							showRow("Уведомлять автора?", "Если да, то при смене или обновлении статуса сериала скриптом автор новости будет уведомлён", makeCheckBox( "save_con[autor]", ($mystatus_cfg['autor'] == 1) ? true : false, false ));
							showRow("Уведомлять админа?", "Если да, то при смене или обновлении статуса сериала скриптом администратор будет уведомлён", makeCheckBox( "save_con[admin]", ($mystatus_cfg['admin'] == 1) ? true : false, false ));
							showRow("Ник админа", "Укажите ник админа, который будет получать уведомления, однако, если автор и админ одно лицо, то сообщение отправится один раз", showInput("adminid", $mystatus_cfg['adminid']));
echo <<<HTML
						</tbody>
					</table>
					<table class="table table-normal">
						<thead>
HTML;
							showRow2("Другие настройки", "");
echo <<<HTML
						</thead>
						<tbody>
HTML;
							showRow("Поднимать новость?", "Если статус сериала обновится - поднимит новость и она будет в самом начале", makeCheckBox( "save_con[newsup]", ($mystatus_cfg['newsup'] == 1) ? true : false, false ));
							showRow("Укажите тип кеша", "Выберите значение префикса кеша, от этого зависит, когда будет чиститься кеш модуля.<br><b>news, rss, comm</b> - при добавлении новости или комментария.<br><b>news, related, tagscloud, archives, calendar, topnews, rss</b> - при добавлении новости.<br><b>comm</b> - при редактировании комментария.<br><b>news, rss</b> - при редактировании новости, при выcтавлении рейтинга<br><b>news, full, comm, rss</b> - при массовом удалении комментариев<br><b>news, full, comm, tagscloud, archives, calendar, rss</b> - при удалении новости", makeDropDown( array ("archives" => "archives", "news" => "news", "rss" => "rss", "comm" => "comm", "related" => "related", "tagscloud" => "tagscloud", "calendar" => "calendar", "topnews" => "topnews", "full" => "full" ), "cache_prefix", $mystatus_cfg['cache_prefix']));
							showRow("Дата последней серии", "Укажите название доп. поля, куда будет выводится дата. Если поле будет заполнено, то скрипт будет выполнен, если нет - то скрипт пропустит", showInput("ended", $mystatus_cfg['ended']));
							showRow("Как выводить дату?", "", makeDropDown( array ( "1" => "02.10.2016","2" => "02.10.16","3" => "2. октября 2016","4" => "02. октября 2016","5" => "2. октября, 2016","6" => "02. октября, 2016","7" => "Октябрь 2, 2016","8" => "02/10/2016","9" => "02/10/16","10" => "10/02/2016","11" => "10/02/16","12" => "02/Окт/2016","13" => "02/Окт/16","14" => "Окт/02/2016","15" => "Окт/02/16","16" => "2. окт 2016","17" => "02. окт 2016","18" => "2. окт, 2016","19" => "02. окт, 2016","20" => "Окт 2, 2016","21" => "2 октября 2016","22" => "02 октября 2016","23" => "2 октября, 2016","24" => "02 октября, 2016" ), "dateout", $mystatus_cfg['dateout']));
							showRow("ID с Кинопоиска", "Укажите название доп. поля, куда будет выводится ID с Кинопоиска. Если поле будет заполнено, то скрипт будет выполнен, если нет - то скрипт пропустит", showInput("kpid", $mystatus_cfg['kpid']));
							showRow("ID с TVRage", "Укажите название доп. поля, куда будет выводится ID с TVRage. Если поле будет заполнено, то скрипт будет выполнен, если нет - то скрипт пропустит", showInput("tvrage", $mystatus_cfg['tvrage']));
							showRow("ID с IMDB", "Укажите название доп. поля, куда будет выводится ID с IMDB. Если поле будет заполнено, то скрипт будет выполнен, если нет - то скрипт пропустит", showInput("imdb", $mystatus_cfg['imdb']));
echo <<<HTML
						</tbody>
					</table>
				</div>
				<div class="box-footer padded">
					<input type="hidden" name="user_hash" value="{$dle_login_hash}" />
					<input type="submit" class="btn btn-lg btn-green" value="{$lang['user_save']}">
					<a href="$PHP_SELF?mod=mystatus" class="btn btn-lg btn-red" style="color:white">Назад</a>
				</div>
			</div>
			<in
		</form>
		<div class="text-center">Copyright 2016 &copy; <a href="http://maxim-harder.de/" target="_blank"><b>Maxim Harder</b></a>. All rights reserved.</div>
HTML;
		echofooter();
	break;
	case "save":
	
		if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
			die( "Hacking attempt! User not found" );
		}
		
		$_for = $_REQUEST['for'];
		if( $_for == "config" ) {
			$save_con = $_REQUEST['save_con'];
			$handler = fopen(ENGINE_DIR . '/data/mystatus.php', "w");
			
			
			fwrite($handler, "<?PHP\n/*\n=============================================================================\nSerial Status - Статус выхода сериала, файл конфигурации\n=============================================================================\nАвтор хака: Максим Гардер\n-----------------------------------------------------\nURL: http://maxim-harder.de/\n-----------------------------------------------------\nemail: info@maxim-harder.de\n-----------------------------------------------------\nskype: maxim_harder\n=============================================================================\nФайл:  engine/data/mystatus.php\n=============================================================================\n*/\n\n\$mystatus_cfg = array (\n\n'version' => \"1.01\",\n\n");
			foreach ($save_con as $name => $value) {
				fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
			}
			fwrite($handler, ");\n\n?>");
			fclose($handler);
			
			clear_cache();
			msg("info", $lang['opt_sysok'], "<b>{$lang['opt_sysok_1']}</b>", "$PHP_SELF?mod=mystatus");
		}
	break;
	default :
		echoheader( "<i class=\"icon-list-alt\"></i> Статус сериалов v{$mystatus_cfg['version']}", "Панель модуля MyStatus");
		
		if($_REQUEST['start_from']) {
				$start_from = intval( $_REQUEST['start_from'] );
			} else {
				if (!isset($cstart) or ($cstart<1)) {
					$cstart = 1;
					$start_from = 0;
				} else {
					$start_from = ($cstart-1)*$news_per_page;
				}
			}
			if ( intval($_REQUEST['news_per_page']) > 0 ) $news_per_page = intval( $_REQUEST['news_per_page'] ); else $news_per_page = 25;
			
			$news_sort_by = ($config['news_sort']) ? $config['news_sort'] : "date";
			$news_direction_by = ($config['news_msort']) ? $config['news_msort'] : "DESC";

			$i = $start_from;
			$sql = $db->query("SELECT * FROM " . PREFIX . "_post ORDER BY $news_sort_by $news_direction_by LIMIT $start_from,$news_per_page ");
			while( $row = $db->get_row($sql) ) {
				$i++;
				$id = $row['id'];
				
				$title = $row['title'];
				if($row['myshowsid'] == "0") {
					$myid = "<br>ID на MyShows не указан";
				} else {
					$myid = "<br> MyShows ID: <a href=\"https://myshows.me/view/".$row['myshowsid']."/\" target=\"_blank\">".$row['myshowsid']."</a>";
				}
				$xfieldsdata = xfieldsdataload ($row['xfields']);
				
				if($xfieldsdata[$mystatus_cfg['xfield']] == "canceledended") {
					$statusname = $mystatus_cfg['closed'];
				} elseif($xfieldsdata[$mystatus_cfg['xfield']] == "returningseries") {
					$statusname = $mystatus_cfg['onair'];
				} elseif($xfieldsdata[$mystatus_cfg['xfield']] == "tbdothebubble") {
					$statusname = $mystatus_cfg['tbd'];
				} elseif($xfieldsdata[$mystatus_cfg['xfield']] == "onhiatus") {
					$statusname = $mystatus_cfg['pause'];
				} elseif($mystatus_cfg['pilots'] && $xfieldsdata[$mystatus_cfg['xfield']] == "pilotordered") {
					$statusname = $mystatus_cfg['pilot'];
				} elseif($mystatus_cfg['news'] && $xfieldsdata[$mystatus_cfg['xfield']] == "newseries") {
					$statusname = $mystatus_cfg['new'];
				} else {
					$statusname = $mystatus_cfg['none'];
				}
				
				$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
				
				$list .= "<tr>";
				$list .= "<td align=\"center\">{$id}</td>";
				$list .= "<td align=\"center\"><a href=\"{$full_link}\" target=\"_blank\">{$title} ({$statusname})</a><b>{$myid}</b></td>";
				$list .= "<td align=\"center\"><a href=\"$PHP_SELF?mod=editnews&action=editnews&id={$id}\"><i class=\"icon-pencil\"></i> Редактировать</a></div></td>";
				$list .= "</tr>";
				$id = '';
			}
			echo <<< HTML
<form class="box" action="$PHP_SELF?mod=mystatus">
	<div class="box-header"><div class="title">Список релизов</div></div>
	<div class="box-content">

		<table class="table table-normal table-hover">
			<thead>
				<tr>
					<td>ID</td>
					<td>Название</td>
					<td>Настройки</td>
				</tr>
			</thead>
			<tbody>
				{$list}
			</tbody>
		</table>
   </div>
   
	<div class="box-footer padded">
		<div class="pull-left"><a href="$PHP_SELF?mod=mystatus&amp;action=config" class="btn btn-green">Настройки</a></div>
	</div>
	
</form>

HTML;
$npp_nav = "";


if( $all_count_news > $news_per_page ) {

	if( $start_from > 0 ) {
		$previous = $start_from - $news_per_page;
		$npp_nav .= "<li><a href=\"$PHP_SELF?mod=mystatus&amp;start_from=$previous&amp;news_per_page=$news_per_page\"> &lt;&lt; </a></li>";
	}
	
	$enpages_count = @ceil( $all_count_news / $news_per_page );
	$enpages_start_from = 0;
	$enpages = "";

	if( $enpages_count <= 10 ) {
		for($j = 1; $j <= $enpages_count; $j ++) {
			if( $enpages_start_from != $start_from ) {
				$enpages .= "<li><a href=\"$PHP_SELF?mod=mystatus&amp;start_from=$enpages_start_from&amp;news_per_page=$news_per_page\">$j</a></li>";
			} else {
				$enpages .= "<li class=\"active\"><span>$j</span></li>";
			}
			$enpages_start_from += $news_per_page;
		}
		$npp_nav .= $enpages;

	} else {
		$start = 1;
		$end = 10;
		if( $start_from > 0 ) {
			if( ($start_from / $news_per_page) > 4 ) {
				$start = @ceil( $start_from / $news_per_page ) - 3;
				$end = $start + 9;
				if( $end > $enpages_count ) {
					$start = $enpages_count - 10;
					$end = $enpages_count - 1;
				}
				$enpages_start_from = ($start - 1) * $news_per_page;
			}
		}

		if( $start > 2 ) {
			$enpages .= "<li><a href=\"#\">1</a></li> <li><span>...</span></li>";
		}

		for($j = $start; $j <= $end; $j ++) {
			if( $enpages_start_from != $start_from ) {
				$enpages .= "<li><a href=\"$PHP_SELF?mod=mystatus&amp;start_from=$enpages_start_from&amp;news_per_page=$news_per_page\">$j</a></li>";
			} else {
				$enpages .= "<li class=\"active\"><span>$j</span></li>";
			}
			$enpages_start_from += $news_per_page;
		}
		$enpages_start_from = ($enpages_count - 1) * $news_per_page;
		$enpages .= "<li><span>...</span></li><li><a href=\"$PHP_SELF?mod=mystatus&amp;start_from=$enpages_start_from&amp;news_per_page=$news_per_page\">$enpages_count</a></li>";
		$npp_nav .= $enpages;
	}

	if( $all_count_news > $i ) {
		$how_next = $all_count_news - $i;
		if( $how_next > $news_per_page ) {
			$how_next = $news_per_page;
		}
		$npp_nav .= "<li><a href=\"$PHP_SELF?mod=mystatus&amp;start_from=$i&amp;news_per_page=$news_per_page\"> &gt;&gt; </a></li>";
	}
	$npp_nav = "<ul class=\"pagination pagination-sm\">".$npp_nav."</ul>";
}

// pagination
echo <<< HTML
<div class="box-footer padded">
	<div class="pull-left">{$npp_nav}</div>
</div>
HTML;
echofooter();
	break;
endswitch;
?>