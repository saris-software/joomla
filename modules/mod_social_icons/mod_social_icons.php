<?php
/*------------------------------------------------------------------------
# mod_social_icons - Social Icon Module
# ------------------------------------------------------------------------
# author    Bilal Kabeer Butt
# copyright Copyright (C) 2013 GegaByte.org. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.gegabyte.org
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

$newline = $params->get('newline');
$floating = $params->get('floating');
$ShowDebug = $params->get('showdebug');
$UseTop = $params->get('usecustomtop');
$TopCSS = $params->get('CssTop');

$a = 0;
$Network = array ();

$Network[$a]['Network'] = 'Gmail';
$Network[$a]['Show'] = $params->get('showgmail');
$Network[$a]['ID'] = $params->get('gmail_id');
$Network[$a]['Icon'] = $params->get('showgmailicon');
$Network[$a]['ShowCustom'] = $params->get('usegmailcustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showgmailcustomicon');
$Network[$a]['CustomIcon'] = $params->get('customgmailicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showgmailclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_gmail');
$a++;

$Network[$a]['Network'] = 'GooglePlus';
$Network[$a]['Show'] = $params->get('showgooleplus');
$Network[$a]['ID'] = $params->get('gooleplus_id');
$Network[$a]['Icon'] = $params->get('showgoogleplusicon');
$Network[$a]['ShowCustomIcon'] = $params->get('showgpluscustomicon');
$Network[$a]['ShowCustom'] = $params->get('usegpluscustomsettings');
$Network[$a]['CustomIcon'] = $params->get('customgplusicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showgplusclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_gplus');
$Network[$a]['Registered'] = $params->get('gplusreg');
$a++;

$Network[$a]['Network'] = 'YouTube';
$Network[$a]['Show'] = $params->get('showyoutube');
$Network[$a]['ID'] = $params->get('youtube_id');
$Network[$a]['Icon'] = $params->get('showyoutubeicon');
$Network[$a]['ShowCustom'] = $params->get('useyoutubecustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showyoutubecustomicon');
$Network[$a]['CustomIcon'] = $params->get('customyoutubeicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showyoutubeclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_youtube');
$Network[$a]['YouTubeChannel'] = $params->get('youtubechannel');
$a++;

$Network[$a]['Network'] = 'Outlook';
$Network[$a]['Show'] = $params->get('showoutlook');
$Network[$a]['ID'] = $params->get('outlook_id');
$Network[$a]['Icon'] = $params->get('showoutlookicon');
$Network[$a]['ShowCustom'] = $params->get('useoutlookcustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showoutlookcustomicon');
$Network[$a]['CustomIcon'] = $params->get('customoutlookicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showoutlookclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_outlook');
$a++;

$Network[$a]['Network'] = 'Skype';
$Network[$a]['Show'] = $params->get('showskype');
$Network[$a]['ID'] = $params->get('skype_id');
$Network[$a]['Icon'] = $params->get('showskypeicon');
$Network[$a]['ShowCustom'] = $params->get('useskypecustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showskypecustomicon');
$Network[$a]['CustomIcon'] = $params->get('customskypeicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showskypeclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_skype');
$a++;

$Network[$a]['Network'] = 'Yahoo';
$Network[$a]['Show'] = $params->get('showyahoo');
$Network[$a]['ID'] = $params->get('yahoo_id');
$Network[$a]['Icon'] = $params->get('showyahooicon');
$Network[$a]['ShowCustom'] = $params->get('useyahoocustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showyahoocustomicon');
$Network[$a]['CustomIcon'] = $params->get('customyahooicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showyahooclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_yahoo');
$a++;

$Network[$a]['Network'] = 'Facebook';
$Network[$a]['Show'] = $params->get('showfacebook');
$Network[$a]['ID'] = $params->get('facebook_id');
$Network[$a]['Icon'] = $params->get('showfacebookicon');
$Network[$a]['ShowCustom'] = $params->get('usefacebookcustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showfbcustomicon');
$Network[$a]['CustomIcon'] = $params->get('customfbicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showfacebookclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_facebook');
$a++;

$Network[$a]['Network'] = 'Twitter';
$Network[$a]['Show'] = $params->get('showtwitter');
$Network[$a]['ID'] = $params->get('twitter_id');
$Network[$a]['Icon'] = $params->get('showtwittericon');
$Network[$a]['ShowCustom'] = $params->get('usetwicustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showtwicustomicon');
$Network[$a]['CustomIcon'] = $params->get('customtwiicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showtwitterclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_twitter');
$a++;

$Network[$a]['Network'] = 'Linkedin';
$Network[$a]['Show'] = $params->get('showlinkedin');
$Network[$a]['ID'] = $params->get('linkedin_id');
$Network[$a]['Icon'] = $params->get('showlinkedinicon');
$Network[$a]['ShowCustom'] = $params->get('uselinkcustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showlinkcustomicon');
$Network[$a]['CustomIcon'] = $params->get('customlinkicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showlinkclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_link');
$Network[$a]['LinkType'] = $params->get('linktype');
$a++;

$Network[$a]['Network'] = 'Instagram';
$Network[$a]['Show'] = $params->get('showinstagram');
$Network[$a]['ID'] = $params->get('instagram_id');
$Network[$a]['Icon'] = $params->get('showinstagramicon');
$Network[$a]['ShowCustom'] = $params->get('useinstacustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showinstacustomicon');
$Network[$a]['CustomIcon'] = $params->get('custominstaicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showinstaclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_insta');
$a++;

$Network[$a]['Network'] = 'RSS';
$Network[$a]['Show'] = $params->get('showrss');
$Network[$a]['ID'] = $params->get('rss_url');
$Network[$a]['Icon'] = $params->get('showrssicon');
$Network[$a]['ShowCustom'] = $params->get('usersscustomsettings');
$Network[$a]['ShowCustomIcon'] = $params->get('showrsscustomicon');
$Network[$a]['CustomIcon'] = $params->get('customrssicon');
$Network[$a]['ShowCustomLbl'] = $params->get('showrssclbl');
$Network[$a]['CustomLbl'] = $params->get('clbl_rss');
$a++;

// CUSTOM SOCIAL ICONS
$CustomSocialObj = $params->get('custsoicons');
$CustomSocialArr = json_decode(json_encode( $CustomSocialObj ), true);
//$CustomSocialArr = get_object_vars($CustomSocialObj);
$TotalCustomSocialIcons = count($CustomSocialArr);
//$Network[$a]['Network'] = 'Custom Social Icon';

for ($j=0;$j<=$TotalCustomSocialIcons-1;$j++){
	$Network[$a]['Network'] = 'custsoicons' . $j;
	$Network[$a]['Show'] = $CustomSocialArr['custsoicons' . $j]['showcussoc'];
	$Network[$a]['ID'] = $CustomSocialArr['custsoicons' . $j]['cussoc_url'];
	$Network[$a]['Icon'] = $CustomSocialArr['custsoicons' . $j]['showcussocicon'];
	$Network[$a]['ShowCustom'] = '1';
	$Network[$a]['ShowCustomIcon'] = '1';
	$Network[$a]['CustomIcon'] = $CustomSocialArr['custsoicons' . $j]['cussoc_icon'];
	$Network[$a]['ShowCustomLbl'] = '1';
	$Network[$a]['CustomLbl'] = $CustomSocialArr['custsoicons' . $j]['cussoc_lbl'];
	$Network[$a]['BgColor'] = $CustomSocialArr['custsoicons' . $j]['cussoc_bg'];
	$a++;
}

$document 		= JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_social_icons/tmpl/style.css');
require JModuleHelper::getLayoutPath('mod_social_icons', $params->get('layout', 'default'));