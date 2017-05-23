<?php header("Content-Type: text/html; charset=utf-8");
setcookie('cookie_test','1', time()+2678400, '/', false, false);
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if($lang==='ru'){
	include ('index_ru.php');
}else{
	include ('index_en.php');
}
?>
