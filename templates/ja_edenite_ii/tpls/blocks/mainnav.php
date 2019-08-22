<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- MAIN NAVIGATION -->
<nav id="t3-mainnav" class="wrap navbar navbar-default t3-mainnav">
	<div class="container">
		<div class="mainnav-inner clearfix">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header pull-left">
			
				<?php if ($this->getParam('navigation_collapse_enable', 1) && $this->getParam('responsive', 1)) : ?>
					<?php $this->addScript(T3_URL.'/js/nav-collapse.js'); ?>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".t3-navbar-collapse" aria-label="navbar-toggle">
						<span class="fa fa-bars"></span>
					</button>
				<?php endif ?>

				<?php if ($this->getParam('addon_offcanvas_enable')) : ?>
					<?php $this->loadBlock ('off-canvas') ?>
				<?php endif ?>

			</div>

			<?php if ($this->getParam('navigation_collapse_enable')) : ?>
				<div class="t3-navbar-collapse navbar-collapse collapse"></div>
			<?php endif ?>

			<div class="t3-navbar navbar-collapse collapse pull-left">
				<jdoc:include type="<?php echo $this->getParam('navigation_type', 'megamenu') ?>" name="<?php echo $this->getParam('mm_type', 'mainmenu') ?>" />
			</div>

			<?php if ($this->countModules('nav-search')) : ?>
					<!-- NAV SEARCH -->
					<div class="nav-search pull-right">
						<jdoc:include type="modules" name="<?php $this->_p('nav-search') ?>" style="raw" />
					</div>
					<!-- //NAV SEARCH -->
				<?php endif ?>
		</div>
	</div>
</nav>
<!-- //MAIN NAVIGATION -->
