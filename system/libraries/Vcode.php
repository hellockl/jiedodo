<?php
class CI_Vcode{
	function getcode($imgW=108,$imgH=32,$imgBGC='255,255,255',$fontSize=22){
		//$Arr=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","L","m","n","p","q","r","s","t","u","v","x","y","z","1","2","3","4","5","6","7","8","9");
		$Arr=array("A","B","D","E","F","G","H","I","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","2","3","4","6","7");
		shuffle($Arr);
		$getcode=$Arr[2].$Arr[5].$Arr[9].$Arr[18];
		$_SESSION["vcode"]=$getcode;
		$font = BASEPATH."fonts/WHITEBLD.TTF";
		
		$this->createImage($imgW,$imgH,$imgBGC,$fontSize,"0,0,0",$font,$getcode);
	}
	
	private function createImage($imgW,$imgH,$imgBGC,$sFontSize,$sFontColor,$sFont,$sText)
	{
		Header("Content-type: image/gif");
		$im = imagecreate($imgW,$imgH);
		$bc=explode(",",$imgBGC);
		$black = ImageColorAllocate($im, $bc[0],$bc[1],$bc[2]);
		$fc=explode(",",$sFontColor);
		$white = ImageColorAllocate($im, $fc[0],$fc[1],$fc[2]);
		//让文字上下居中
		$imgY=$sFontSize+($imgH-$sFontSize)/2;
		ImageTTFText($im,$sFontSize,0,2,$imgY,$white,$sFont,$sText);
		ImageGif($im);
		ImageDestroy($im);
	}
}
?>