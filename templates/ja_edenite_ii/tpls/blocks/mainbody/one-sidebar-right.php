<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Mainbody 2 columns: content - sidebar
 */
?>
<div id="t3-mainbody" class="container t3-mainbody">
	<div class="row">

		<!-- MAIN CONTENT -->
		<div id="t3-content" class="t3-content col-xs-12 col-sm-8  col-md-9">
			<?php if ($this->countModules('content-mass-top')) : ?>
				<!-- CONTENT MASS TOP -->
				<div class="t3-content-mass-top <?php $this->_c('content-mass-top') ?>">
					<jdoc:include type="modules" name="<?php $this->_p('content-mass-top') ?>" />
				</div>
				<!-- //CONTENT MASS TOP -->
			<?php endif ?>

			<?php if($this->hasMessage()) : ?>
			<jdoc:include type="message" />
			<?php endif ?>
			<jdoc:include type="component" />

			<?php if ($this->countModules('content-mass-bottom')) : ?>
				<!-- CONTENT MASS BOTTOM -->
				<div class="t3-content-mass-bottom <?php $this->_c('content-mass-bottom') ?>">
					<jdoc:include type="modules" name="<?php $this->_p('content-mass-bottom') ?>" />
				</div>
				<!-- //CONTENT MASS BOTTOM -->
			<?php endif ?>
		</div>
		<!-- //MAIN CONTENT -->

		<!-- SIDEBAR RIGHT -->
		<div class="t3-sidebar t3-sidebar-right col-xs-12 col-sm-4  col-md-3 <?php $this->_c($vars['sidebar']) ?>">
			<jdoc:include type="modules" name="<?php $this->_p($vars['sidebar']) ?>" style="T3Xhtml" />
		</div>
		<!-- //SIDEBAR RIGHT -->

	</div>
</div> 
