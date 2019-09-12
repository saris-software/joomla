<?php
/*------------------------------------------------------------------------
# mod_social_icons - Simple Joomla Module
# ------------------------------------------------------------------------
# author    Bilal Kabeer Butt
# copyright Copyright (C) 2013 GegaByte.org. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.gegabyte.org
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<style type="text/css">
pre{
	width:300%;
}
</style>
<?php 
if ( $floating == 1 ){?>
	<div class="social_cnt">
<?php } ?>
	<?php 
	$pth = "modules/mod_social_icons/tmpl/images/";
	$Total_Social_Icons = count($Network);
	if ( $floating != 1 ){
		echo '<div class="icon_cnt">';
	}
	$ind = 1;
	$LastIndno = '';
	for ( $a=0;$a<=($Total_Social_Icons-1);$a++ ){
		if ( $Network[$a]['Show'] == 1){
			$LastIndno = $ind;
			$ind++;
		}
	}
	
	$ind = 1;
	for ( $b=0;$b<=($Total_Social_Icons-1);$b++ ){
		$Social_ID = $Network[$b]['ID'];		
		if ( $Social_ID != '' ){
			
			$Social_Network = $Network[$b]['Network'];
			$Img_Name = strtolower( $Social_Network );
			$ShowCustomVals = $Network[$b]['ShowCustom'];
			$ShowCustomIcon = $Network[$b]['ShowCustomIcon'];
			$UseCustomLbl = $Network[$b]['ShowCustomLbl'];
			$CustomLbl = $Network[$b]['CustomLbl'];			
			$Social_Link = '';
			
			// LINK
			$Upper_Social_Network = strtoupper( $Social_Network );		
			if ( $Upper_Social_Network == "GMAIL" || $Upper_Social_Network == "OUTLOOK" ){
				$Social_Link = "mailto:" . $Social_ID ;
			}
			if ( $Upper_Social_Network == "GOOGLEPLUS" ){
				if ( $Network[$b]['Registered'] == 1 ){
					$Social_Link = "https://plus.google.com/+" . $Social_ID;
				}else{
					$Social_Link = "https://plus.google.com/" . $Social_ID;
				}
			}
			if ( $Upper_Social_Network == "YOUTUBE" ){
				if ( $Network[$b]['YouTubeChannel'] == 1 ){
					$Social_Link = "https://www.youtube.com/channel/" . $Social_ID;
				}else{
					$Social_Link = "https://www.youtube.com/user/" . $Social_ID;
				}
			}
			if ( $Upper_Social_Network == "YAHOO"){
				$Social_Link = "mailto:" . $Social_ID ;
			}
			if ( $Upper_Social_Network == "SKYPE" ){
				$Social_Link = "skype:" . $Social_ID . "?call:" ;
			}	
			if ( $Upper_Social_Network == "FACEBOOK"  ){
				$Social_Link = "https://www.facebook.com/". $Social_ID;
			}	
			if ( $Upper_Social_Network == "TWITTER"  ){
				$Social_Link = "https://www.twitter.com/". $Social_ID;
			}
			if ( $Upper_Social_Network == "LINKEDIN" ){
				if ( $Network[$b]['LinkType'] == 0 ){
					$Social_Link = "https://www.linkedin.com/in/" . $Social_ID;
				}else{
					$Social_Link = "https://www.linkedin.com/company/" . $Social_ID;
				}
			}
			if ( $Upper_Social_Network == "INSTAGRAM" ){
				$Social_Link = "https://www.instagram.com/" . $Social_ID;
			}	
			if ( $Upper_Social_Network == "RSS" ){
				$Social_Link = $Social_ID;
			}			
			if ( preg_match('/(custsoicons\d{1,})/i',$Upper_Social_Network) ){
				$Social_Link = $Social_ID ;
			}			
			if ( $Social_Link == '' ){
				$Social_Link = "mailto:" . $Social_ID ;
			}			
			// LINK

			if ( $floating != 1 ){
				
				if ( $Network[$b]['Show'] == 1){
					
					if ( $ShowCustomVals == 1 && $UseCustomLbl == 1 ){
						$Network_title = $CustomLbl;						
					}else{
						if ( $Social_Network == "GooglePlus" ){
							$Network_title = 'Google+';
						}else{
							$Network_title = $Social_Network;
						}
					}
					if ( $newline != 1 ){
						if ( $ind == $LastIndno ){
							if ( $ind % 2 == 0) {
								$LastCss = "";
							}else{
								$LastCss = "last-child";
							}
						}
					}
					if ( $floating == 1 ) {
						$CssClass = "group";
					}elseif ( $newline == 1 ){
						$CssClass = "group2 width100";
					}else{
						$CssClass = "group2";
					}
					
					$toshow = '<div class="' . $CssClass . '">';
					
					if ( $newline == '0' && $Network[$b]['Icon'] == 2 ){
						if ( preg_match('/(custsoicons\d{1,})/',$Social_Network) ){
							$hclass = ' CustomSocialIcons height70 font18 ';
						}else{
							$hclass = ' height70 font18 ';
						}
					}else{
						if ( preg_match('/(custsoicons\d{1,})/',$Social_Network) ){
							$hclass = ' CustomSocialIcons font18 ';	
						}else{
							$hclass = ' font18 ';
						}
					}
					
					$toshow .= '	<div class="' . strtolower( $Social_Network ) . $hclass . '">';
					$toshow .= '		<div class="name">';
					$toshow .= '			<a target="_blank" class="icon_link" href="' . $Social_Link . '">';
										
					if ( $newline != 1 ) {
						$toshow .= '			<div class="img_cnt">';
					}else{
						$toshow .= '			<div class="img_cnt2">';
					}
					
					if ( $Network[$b]['Icon'] == 0 || $Network[$b]['Icon'] == 2){
						if ( $ShowCustomVals == 1 && $ShowCustomIcon == 1  ){
							$toshow .= '			<img src="' . $Network[$b]['CustomIcon'] . '" width="36" height="36" />';
						}else{
							//$toshow .= '			<img class="asss" src="' . $pth . strtolower( $Social_Network ) . '.png" width="36" height="36" />';
							$toshow .= '			<div class="' . strtolower( $Social_Network ) . '_icon img"></div>';
						}
					}
					
					$toshow .= '</div>';					
					
					if ( $newline != 1 ) {
						$toshow .= '<div class="img_cnt">';
					}else{
						$toshow .= '<div class="img_cnt2">';
					}
					if ( $Network[$b]['Icon'] == 1 || $Network[$b]['Icon'] == 2){
						$toshow .= '<span>' . $Network_title . '</span>';
					}
					
					$toshow .= '</div>';					
					$toshow .= '</a>';
					$toshow .= '		</div>';
					$toshow .= '	</div>';
					$toshow .= '</div>';
					echo $toshow;
					$ind++;
				}
				
			}else{
	
				if ( $Network[$b]['Show'] == 1){
					echo '<div class="group">';
					
					if ( preg_match('/(custsoicons\d{1,})/',$Social_Network) ){
						echo '	<div class="CustomSocialIcons ' . strtolower( $Social_Network ) . '">';
					}else{
						echo '	<div class="' . strtolower( $Social_Network ) . '">';
					}
										
					if ( $ShowCustomVals == 1 && $UseCustomLbl == 1 ){
						$NetName = $CustomLbl;
					}else{
						if ( $Social_Network == "GooglePlus" ){
							$NetName = 'Google+';
						}else{
							$NetName = $Social_Network;							
						}
					}
					echo '		<div class="name"><a target="_blank" class="link" href="' . $Social_Link . '"><span>' . $NetName . '</span>';
					//if ( $Network[$b]['Icon'] == 0 || $Network[$b]['Icon'] == 2){
						if ( $ShowCustomVals == 1 && $ShowCustomIcon == 1 ){
							echo '			<img src="' . $Network[$b]['CustomIcon'] . '" width="36" height="36" />';
						}else{
							//echo '			<img src="' . $pth . strtolower( $Social_Network ) . '.png" width="36" height="36" />';
							echo '			<div class="' . strtolower( $Social_Network ) . '_icon">&nbsp;</div>';
						}
					//}
					echo '	</a></div></div>';
					echo '</div>';
				}
			}
		}
	}
	if ( $floating != 1 ){
		echo '</div>';
		echo '<div style="clear:both;"></div>';
	}
	?>
<?php if ( $floating == 1 ){?>	
</div>
<?php }
if ( $ShowDebug == 1 ){
	// Social Icons
	echo "<hr /><pre>";print_r($Network);echo "</pre>";	
	// Custom Social Icons
	echo "Total Custom Social Icons ; $TotalCustomSocialIcons <hr /><pre>" ; print_r ( ($CustomSocialArr) ); echo "</pre><hr />";	
} ?>

<style type="text/css">
	<?php 
	// Add Custom Icons CSS
	for ($j=0;$j<=$TotalCustomSocialIcons-1;$j++){
		echo "\n.custsoicons" . $j . "{\n";
		echo "	background-color: " . $CustomSocialArr['custsoicons' . $j]['cussoc_bg'] . " !important;\n";
		echo "}\n";
	}
	if ( $UseTop ){
		echo ".social_cnt{\n";
		echo "	top: " . $TopCSS . "px; \n";
		echo "}\n\n";
	}
	echo "// Custom Icons CSS"; ?>
</style>