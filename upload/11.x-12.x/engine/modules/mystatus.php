<?php
/*
=============================================================================
Serial Status - Статус выхода сериала, файл обработки
=============================================================================
Автор хака: Максим Гардер
-----------------------------------------------------
URL: http://maxim-harder.de/
-----------------------------------------------------
email: info@maxim-harder.de
-----------------------------------------------------
skype: maxim_harder
=============================================================================
Файл:  engine/modules/mystatus.php
=============================================================================
*/

if( !defined( 'DATALIFEENGINE' )) return;

include ENGINE_DIR . "/data/mystatus.php";

if($mystatus_cfg['onof']){
	
	$news_id = intval($news_id);
	if($news_id<=0) return;

	$mysid = intval($mysid);
	$title = urlencode($title);
	$ttitle = totranslit($title);
	
	$allow_cache = ($config['version_id'] >= '10.2') ? $config['allow_cache'] == '1' : $config['allow_cache'] == "yes";
	$is_change = false;
	if (!$allow_cache)
	{
		if ($config['version_id'] >= '10.2')	$config['allow_cache'] = '1';
		else									$config['allow_cache'] = "yes";
		$is_change = true;
	}
	if($mysid != 0)
		$myStatus = dle_cache( $mystatus_cfg['cache_prefix']."_myserials_" . $news_id . '_' . $mysid, $config['skin'] . $mysid, false);
	else
		$myStatus = dle_cache( $mystatus_cfg['cache_prefix']."_myserials_" . $news_id . '_' . $ttitle, $config['skin'] . $ttitle, false);

	if ( !$myStatus ) {

		if($mysid == 0) $askid = "id='".$news_id."'";
			else $askid = "myshowsid='".$mysid."'";

		$row = $db->super_query("SELECT * FROM ". PREFIX ."_post WHERE {$askid}");
		$xfieldsdata = xfieldsdataload( $row["xfields"] );
		$xfields_n = $xfieldsdata;

		if(!function_exists('stdToArray')) {
			function stdToArray($obj) {
				$rc = (array)$obj;
				foreach($rc as $key => &$field)
					if(is_object($field))$field = stdToArray($field);
				return $rc;
			}
		}
			
		$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

		if($mysid == 0) {
			$url = "https://api.myshows.me/shows/search/?q=".$title;
		} else {
			$url = "https://api.myshows.me/shows/".$mysid;
		}
			
		$curlpost = curl_init( $url );
		curl_setopt($curlpost, CURLOPT_URL, $url);
		curl_setopt($curlpost, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlpost, CURLOPT_HEADER, 0);
		curl_setopt($curlpost, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curlpost, CURLOPT_ENCODING, "");
		curl_setopt($curlpost, CURLOPT_USERAGENT, $uagent);
		curl_setopt($curlpost, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($curlpost, CURLOPT_TIMEOUT, 120);
		curl_setopt($curlpost, CURLOPT_MAXREDIRS, 10);
		$data = curl_exec($curlpost);
		curl_close($curlpost);
			
		$data = json_decode($data);
		$count = stdToArray($data);
		if(!$count) return;
	
		
		if($mysid == 0) {
			foreach($count AS $key => $val){
				$myid = $data->{$key}->id;
				if($row['myshowsid'] == 0 || $row['myshowsid'] == ""){
					$db->query( "UPDATE " . PREFIX . "_post SET myshowsid='{$myid}' WHERE id='{$news_id}'" );
				}
			}
		} elseif ($mysid != 0 || $mysid > 0) {
			$myid = $data->id;
			$mystatus = $data->status; 
			$mystatus = str_replace(array("/", " "), array("", ""), $mystatus);
			$mystatus = mb_strtolower($mystatus);
			$mystatus = trim($mystatus);
			
			if($xfieldsdata[$mystatus_cfg['xfield']] != $mystatus) {
				if($mystatus_cfg['ended'] != "" || !empty($mystatus_cfg['ended']) || $data->ended != "NULL" || $data->ended != "" || !empty($data->ended)) {
					$mydateend = $data->ended;
					$mydate = explode("/", $mydateend);
					$month = $mydate[0];
					$day = $mydate[1];
					$year = $mydate[2];
					$monthzahl = array (
						'Jan'		=>	"01",
						'Feb'		=>	"02",
						'Mar'		=>	"03",
						'Apr'		=>	"04",
						'May'		=>	"05",
						'Jun'		=>	"06",
						'Jul'		=>	"07",
						'Aug'		=>	"08",
						'Sep'		=>	"09",
						'Oct'		=>	"10",
						'Nov'		=>	"11",
						'Dec'		=>	"12",
					);
					$monthdrei = array (
						'Jan'		=>	"января",
						'Feb'		=>	"февраля",
						'Mar'		=>	"марта",
						'Apr'		=>	"апреля",
						'May'		=>	"мая",
						'Jun'		=>	"июня",
						'Jul'		=>	"июля",
						'Aug'		=>	"августа",
						'Sep'		=>	"сентября",
						'Oct'		=>	"октября",
						'Nov'		=>	"ноября",
						'Dec'		=>	"декабря",
					);
					$monthfull = array (
						'Jan'		=>	"Январь",
						'Feb'		=>	"Февраль",
						'Mar'		=>	"Март",
						'Apr'		=>	"Апрель",
						'May'		=>	"Май",
						'Jun'		=>	"Июнь",
						'Jul'		=>	"Июль",
						'Aug'		=>	"Август",
						'Sep'		=>	"Сентябрь",
						'Oct'		=>	"Октябрь",
						'Nov'		=>	"Ноябрь",
						'Dec'		=>	"Декабрь",
					);
					if($day == "01" || $day == "02" || $day == "03" || $day == "04" || $day == "05" || $day == "06" || $day == "07" || $day == "08" || $day == "09") {
						$daye = substr($day , -1);
					} else {
						$daye = $day;
					}
					if($mystatus_cfg['dateout'] == "1") {
						$dateout = $day.".".$monthzahl[$month].".".$year;
					} elseif ($mystatus_cfg['dateout'] == "2") {
						$dateout = $day.".".$monthzahl[$month].".".substr($year, -2);
					} elseif ($mystatus_cfg['dateout'] == "3") {
						$dateout = $daye.". ".$monthdrei[$month]." ".$year;
					} elseif ($mystatus_cfg['dateout'] == "4") {
						$dateout = $day.". ".$monthdrei[$month]." ".$year;
					} elseif ($mystatus_cfg['dateout'] == "5") {
						$dateout = $daye.". ".$monthdrei[$month].", ".$year;
					} elseif ($mystatus_cfg['dateout'] == "6") {
						$dateout = $day.". ".$monthdrei[$month].", ".$year;
					} elseif ($mystatus_cfg['dateout'] == "7") {
						$dateout = $monthfull[$month]." ".$day.", ".$year;
					} elseif ($mystatus_cfg['dateout'] == "8") {
						$dateout = $day."/".$monthzahl[$month]."/".$year;
					} elseif ($mystatus_cfg['dateout'] == "9") {
						$dateout = $day."/".$monthzahl[$month]."/".substr($year, -2);
					} elseif ($mystatus_cfg['dateout'] == "10") {
						$dateout = $monthzahl[$month]."/".$day."/".$year;
					} elseif ($mystatus_cfg['dateout'] == "11") {
						$dateout = $monthzahl[$month]."/".$day."/".substr($year, -2);
					} elseif ($mystatus_cfg['dateout'] == "12") {
						$dateout = $day."/".$langdate[$month]."/".$year;
					} elseif ($mystatus_cfg['dateout'] == "13") {
						$dateout = $day."/".$langdate[$month]."/".substr($year, -2);
					} elseif ($mystatus_cfg['dateout'] == "14") {
						$dateout = $langdate[$month]."/".$day."/".$year;
					} elseif ($mystatus_cfg['dateout'] == "15") {
						$dateout = $langdate[$month]."/".$day."/".substr($year, -2);
					} elseif ($mystatus_cfg['dateout'] == "16") {
						$dateout = $daye." ".$langdate[$month]." ".$year;
					} elseif ($mystatus_cfg['dateout'] == "17") {
						$dateout = $day." ".$langdate[$month]." ".$year;
					} elseif ($mystatus_cfg['dateout'] == "18") {
						$dateout = $daye." ".$langdate[$month].", ".$year;
					} elseif ($mystatus_cfg['dateout'] == "19") {
						$dateout = $day." ".$langdate[$month].", ".$year;
					} elseif ($mystatus_cfg['dateout'] == "20") {
						$dateout = $langdate[$month]." ".$daye.", ".$year;
					} elseif ($mystatus_cfg['dateout'] == "21") {
						$dateout = $daye." ".$monthdrei[$month]." ".$year;
					} elseif ($mystatus_cfg['dateout'] == "22") {
						$dateout = $day." ".$monthdrei[$month]." ".$year;
					} elseif ($mystatus_cfg['dateout'] == "23") {
						$dateout = $daye." ".$monthdrei[$month].", ".$year;
					} elseif ($mystatus_cfg['dateout'] == "24") {
						$dateout = $day." ".$monthdrei[$month].", ".$year;
					}
					$xfields_n[$mystatus_cfg['ended']] = $dateout;
				}
				if($mystatus_cfg['kpid'] != "" || !empty($mystatus_cfg['kpid'])) {
					$xfields_n[$mystatus_cfg['kpid']] = $data->kinopoiskId;
				}
				if($mystatus_cfg['tvrage'] != "" || !empty($mystatus_cfg['tvrage'])) {
					$xfields_n[$mystatus_cfg['tvrage']] = $data->tvrageId;
				}
				if($mystatus_cfg['imdb'] != "" || !empty($mystatus_cfg['imdb'])) {
					$xfields_n[$mystatus_cfg['imdb']] = $data->imdbId;
				}
				$xfields_n[$mystatus_cfg['xfield']] = $mystatus;
				if ( $xfieldsdata[$mystatus_cfg['xfield']] )
					unset( $xfields_n[$mystatus_cfg['xfield']] );
				foreach ( $xfields_n as $key => & $value )
					$arr_field[] = $key . "|" . str_replace('|', '&#124;', $value);
					$xfields_n = implode("||", $arr_field);
					unset( $arr_field );
				$xfields_n = $db->safesql($xfields_n);
				
				if($mystatus_cfg['post']) {
					if($mystatus == "canceledended") {
						$statusname = $mystatus_cfg['closed'];
					} elseif($xmystatus == "returningseries") {
						$statusname = $mystatus_cfg['onair'];
					} elseif($mystatus == "tbdothebubble") {
						$statusname = $mystatus_cfg['tbd'];
					} elseif($mystatus == "onhiatus") {
						$statusname = $mystatus_cfg['pause'];
					} elseif($mystatus_cfg['pilots'] && $mystatus == "pilotordered") {
						$statusname = $mystatus_cfg['pilot'];
					} elseif($mystatus_cfg['news'] && $mystatus == "newseries") {
						$statusname = $mystatus_cfg['new'];
					} else {
						$statusname = $mystatus_cfg['none'];
					}
					if($mystatus_cfg['autor'] && !$mystatus_cfg['admin']) {
						$user = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE name='".$row['autor']."'");
					} elseif ($mystatus_cfg['admin'] && !$mystatus_cfg['autor']) {
						$user = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE name='".$mystatus_cfg['adminid']."'");
					} elseif ($mystatus_cfg['autor'] && $mystatus_cfg['admin'] && $row['autor'] == $mystatus_cfg['adminid'] ) {
						$user = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE name='".$row['autor']."'");
					} elseif ($mystatus_cfg['autor'] && $mystatus_cfg['admin'] && $row['autor'] != $mystatus_cfg['adminid'] ) {
						$user = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE name='".$row['autor']."'");
						$usera = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE name='".$mystatus_cfg['adminid']."'");
						$user_ida = $usera['user_id'];
						$user_ida = ( int ) $user_ida;
					}
					$user_id = $user['user_id'];
					$user_id = ( int ) $user_id;
					$now = time();
					$subject = $row['title'] . ' сменил статус на ' . $statusname;
					$subject = $db->safesql($subject);
					$from = 'MySerials';
					$from = $db->safesql($from);
					$text = '<h3>' . $row['title'] . ' сменил статус на ' . $statusname . '</h3>';
					if($mystatus == "canceledended" && $mystatus_cfg['ended']) $text = '<small>Дата последнего показа: '.$dateout.'</small>';
					$text .= '<p><b>Теперь можно:</b></p>';
					$text .= '<ul><li><a href="' . $config['http_home_url'] . 'index.php?newsid='.$news_id.'" target="_blank">Открыть новость на сайте</a></li>';
					$text .= '<li><a href="https://myshows.me/view/'.$mysid.'/" target="_blank">Открыть новость на MyShows.me для проверки</a></li>';
					$text .= '<li><a href="' . $config['admin_path'] . '?mod=editnews&action=editnews&id='.$news_id.'" target="_blank">Редактировать в админпанели</a></li>';
					$text .= '<li><a href="' . $config['admin_path'] . '?mod=addnews&action=addnews" target="_blank">Добавить новую новость в админпанели</a></li></ul>';
					$text = $db->safesql($text);
					if ($mystatus_cfg['autor'] && $mystatus_cfg['admin'] && $row['autor'] != $mystatus_cfg['adminid'] ) {
						$db->query("INSERT into " . PREFIX . "_pm (subj, text, user, user_from, date, pm_read, folder) VALUES ('$subject', '$text', '$user_ida', '$from', '$now', '0', 'inbox')");
						$db->query("UPDATE " . USERPREFIX . "_users set pm_unread = pm_unread + 1, pm_all = pm_all+1  where user_id = '$user_ida'");
					}
					$db->query("INSERT into " . PREFIX . "_pm (subj, text, user, user_from, date, pm_read, folder) VALUES ('$subject', '$text', '$user_id', '$from', '$now', '0', 'inbox')");
					$db->query("UPDATE " . USERPREFIX . "_users set pm_unread = pm_unread + 1, pm_all = pm_all+1  where user_id = '$user_id'");
				}
				if ( $mystatus_cfg['newsup'] )
					$myNewDate = ( $mystatus_cfg['newsup'] != 0 ) ? ", `date` = '" . date('Y-m-d H:i:s') . "'" : false;
				
				$db->query("UPDATE " . PREFIX . "_post SET `xfields` = '$xfields_n' {$myNewDate} WHERE id = {$news_id}");
			}
		}
		if($mysid != 0) create_cache( $mystatus_cfg['cache_prefix']."_myserials_" . $news_id . '_' . $mysid, $mySerials, $config['skin'] . $mysid, false );
		else create_cache( $mystatus_cfg['cache_prefix']."_myserials_" . $news_id . '_' . $ttitle, $mySerials, $config['skin'] . $ttitle, false );
		if ($is_change) $config['allow_cache'] = false;
	}
	echo $mySerials;
}
?>