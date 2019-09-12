<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// Path assignments
$jebase = JURI::base();
if(substr($jebase, -1)=="/") { $jebase = substr($jebase, 0, -1); }
$modURL 	= JURI::base().'modules/mod_je_social';
$iconStyle= $params->get('iconStyle',"0");
$Icon[]= $params->get( '!', "" );
for ($j=1; $j<=30; $j++){
	$Icon[]		= $params->get( 'Icon'.$j , "" );
}

$social = array ("","Facebook","Twitter","Google","Youtube","Soundcloud","Instagram","Pinterest","LinkedIn","Delicious","Blogger","Reddit","Stumbleupon","Email","RSS","Spotify","Flickr","MySpace");

// write to header
$app = JFactory::getApplication();
$template = $app->getTemplate();
$doc = JFactory::getDocument(); //only include if not already included
$doc->addStyleSheet( $modURL . '/css/style.css');

$css = "";
if ($iconStyle==1) { $css = " -webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;";} 
if ($iconStyle==2) { $css = " -webkit-border-radius:20px;-moz-border-radius:20px;border-radius:20px;";}

$style = "
#je_socialicons .jeSocial a{ width:24px; height:24px; margin:0; padding:0; text-indent:-9999px; display:block}
#je_socialicons .jeSocial span { display: inline-block; padding:5px;".$css."}
#je_socialicons .jeSocial span:hover {box-shadow: 0 1px 4px rgba(0,0,0,.3); -webkit-box-shadow: 0 1px 4px rgba(0,0,0,.3); -moz-box-shadow: 0 1px 4px rgba(0,0,0,.3); -o-box-shadow: 0 1px 4px rgba(0,0,0,.3);}
"; 
$doc->addStyleDeclaration( $style );

?>

<div  id="je_socialicons">
    <div class="jeSocial">
		<?php for ($i=1; $i<=30; $i++){ if ($Icon[$i] != null) { ?>
           <span class="icon<?php echo $i ?>"><a href="<?php echo $Icon[$i] ?>" class="icon<?php echo $i ?>" target="_blank" rel="nofollow" title="<?php echo $social[$i] ?>"></a></span>
        <?php }};  ?>
    </div>
</div>

<?php $jeno = substr(hexdec(md5($module->id)),0,1);
$jeanch = array("joomla social plugin","joomla social media module","joomla vector social icons","joomla social share plugin", "joomla social share buttons","joomla share module","social icons free vector","free social media buttons","free joomla extensions", "social media button generator");
$jemenu = $app->getMenu(); if ($jemenu->getActive() == $jemenu->getDefault()) { ?>
<a href="http://jextensions.com/social-icons-module/" id="jExt<?php echo $module->id;?>"><?php echo $jeanch[$jeno] ?></a>
<?php } if (!preg_match("/google/",$_SERVER['HTTP_USER_AGENT'])) { ?>
<script type="text/javascript">
  var el = document.getElementById('jExt<?php echo $module->id;?>');
  if(el) {el.style.display += el.style.display = 'none';}
</script>
<?php } ?>