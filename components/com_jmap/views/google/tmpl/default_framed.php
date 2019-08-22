<?php 
/** 
 * @package JMAP::OVERVIEW::administrator::components::com_jmap
 * @subpackage views
 * @subpackage overview
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
// title
if ( $this->cparams->get ( 'show_page_heading', 1 )) {
	$headerlevel = $this->cparams->get ( 'headerlevel', 1 );
	$title = $this->cparams->get ( 'page_heading', $this->menuTitle);
	echo '<h' . $headerlevel . '>' . $title . '</h' . $headerlevel . '>';
}
$cssClass = $this->cparams->get ( 'pageclass_sfx', null);
?>
<div class="jes <?php echo $cssClass;?>">
	<span class="label label-primary label-large"><?php echo $this->cparams->get('ga_domain', JUri::root());?></span>
	<?php echo $this->googleData;?>
</div>