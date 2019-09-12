<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

/* Change it to have a specific encoding ! */
define("AUTARTICAPTCHA_ENTROPY","AutarTICa Captcha");

/* Choose length (max 32) */
define("AUTARTICAPTCHA_LENGTH",2);

$GLOBALS["autarticaptcha_akey"] = md5(uniqid(rand(), true));

/**
 * Helper to generate html form tags
 *
 */
class AutarticaptchaHelper
{
	/**
	 * Generate IMG Tag
	 *
	 * @param string $baseuri : relative or absolute path to folder containing this file on web
	 * @return IMG Tag
	 */
	public static function generateImgTags($baseuri)
	{
			$str	= $baseuri ."captcha.php?pck=". $GLOBALS['autarticaptcha_akey'] ."\"".
					" id=\"autarticaptcha\"";
			return JHTML::_( 'image', $str, "???" );

	}

	/**
	 * Generate hidden tag (must be in a form)
	 *
	 * @return input hidden tag
	 */
	public static function generateHiddenTags()
	{
		return "<input type=\"hidden\" name=\"autarticaptcha_key\" value=\"".$GLOBALS['autarticaptcha_akey']."\"/>";
	}

	/**
	 * Generate input tag (must be in a form)
	 *
	 * @return input tag
	 */
	public static function generateInputTags()
	{
		return "<input type=\"text\" class=\"inputbox required text_area\" id=\"autarticaptcha_entry\" name=\"autarticaptcha_entry\" value=\"\" maxlength=\"2\" size=\"5\"/>";
	}

	/**
	 * Check if user input is correct
	 *
	 * @return boolean (true=correct, false=incorrect)
	 */
	public static function checkCaptcha($test)
	{

		if($test){
			if(	isset($_POST['autarticaptcha_entry']) &&
				$_POST['autarticaptcha_entry'] == autarticaptchaHelper::_getDisplayText($_POST['autarticaptcha_key']))
			{
				return true;
			}
			return false;
		}else
		{return true;}

	}

	/**
	 * Internal function
	 *
	 * @param string $pck
	 * @return string
	 */
	public static function _getDisplayText($pck)	// internal function
	{
		$src=md5(AUTARTICAPTCHA_ENTROPY.$pck);
		$txt="";
		for($i=0;$i<AUTARTICAPTCHA_LENGTH;$i++)
			$txt.=substr($pck,$i*32/AUTARTICAPTCHA_LENGTH,1);
		return $txt;
	}
}


// If script called directly : generate image
if(basename($_SERVER["SCRIPT_NAME"])=="captcha.php" && isset($_GET["pck"]))
{
	$width = AUTARTICAPTCHA_LENGTH*10+10;
	$height = 25;

	$image = imagecreatetruecolor($width, $height);
	$bgCol = imagecolorallocate($image, 204, 204, 204);
	imagefilledrectangle($image,0,0,$width,$height,$bgCol);

	$txt = autarticaptchaHelper::_getDisplayText($_GET["pck"]);

	for($c=0;$c<AUTARTICAPTCHA_LENGTH*2;$c++)
	{
		$bgCol = imagecolorallocate($image, 204, 204, 204);
		$x=rand(0,$width);
		$y=rand(0,$height);
		$w=rand(5,$width/2);
		$h=rand(5,$height/2);
		imagefilledrectangle($image,$x,$y,$x+$w,$y+$h,$bgCol);
		imagecolordeallocate($image,$bgCol);
	}
	for($c=0;$c<AUTARTICAPTCHA_LENGTH;$c++)
	{
		$txtCol = imagecolorallocate($image, 0,0,0);
		imagestring($image,5,5+10*$c,rand(0,10),substr($txt,$c,1),$txtCol);
		imagecolordeallocate($image,$txtCol);
	}

	header("Content-type: image/png");
	imagepng($image);
	imagedestroy($image);
}
?>
