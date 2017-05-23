<?php 
$width = 183;
$height = 60; 
$font_size = 8;
$font = 'fonts/rock.ttf';

$symbols = array('a','b','c','d','e','f','g','h','j','k','m','n','p','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9');      

$src = imagecreatetruecolor($width,$height);//создаем изображение               
$background = imagecolorallocate($src,255,255,255);//создаем фон
imagefill($src,0,0,$background);//заливаем изображение фоном
$captcha=array();
 
for($i=0;$i < 7;$i++){
   $color = imagecolorallocatealpha(
   $src,
   rand(0,255),
   rand(0,200),
   rand(0,210),rand(10,25));
   $symbol = $symbols[rand(0,sizeof($symbols)-1)];
   $size = rand($font_size*2-5,$font_size*2-1);
   $x =($i===0)?2:round($i*3.4*$font_size)+rand(-1*round($font_size),-1);
   $y = (($height*2)/3) + rand(-9,8);                            
   $captcha[] = $symbol;                        //запоминаем код
   imagettftext($src,$size,rand(-30,15),$x,$y,$color,$font,$symbol);
}
 
$captcha= implode("",$captcha);                    //переводим код в строку
 session_start();
 $_SESSION['captcha']=md5($captcha);
header ("Content-type: image/gif");         //выводим готовую картинку
imagegif($src); 
?>