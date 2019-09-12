<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- BACK TOP TOP BUTTON -->
<div id="back-to-top" data-spy="affix" data-offset-top="200" class="back-to-top hidden-xs hidden-sm affix-top">
  <button class="btn btn-primary" title="Back to Top"><span class="fa fa-long-arrow-up" aria-hidden="true"></span><span class="element-invisible">empty</span></button>
</div>

<script type="text/javascript">
(function($) {
  // Back to top
  $('#back-to-top').on('click', function(){
    $("html, body").animate({scrollTop: 0}, 500);
    return false;
  });
})(jQuery);
</script>
<!-- BACK TO TOP BUTTON -->

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer">

	<?php if ($this->checkSpotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6')) : ?>
		<!-- FOOT NAVIGATION -->
		<div class="container">
			<div class="footer-items">
			<?php $this->spotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6') ?>
			</div>
		</div>
		<!-- //FOOT NAVIGATION -->
	<?php endif ?>

	<div class="container">
		<section class="t3-copyright text-center">
				<div class="row">
					<div class="col-xs-12 copyright <?php $this->_c('footer') ?>">
						<jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />
					</div>
					<?php if ($this->getParam('t3-rmvlogo', 1)): ?>
						<div class="col-xs-12 text-center text-hide">
							<a class="t3-logo t3-logo-small" href="http://t3-framework.org" title="<?php echo JText::_('T3_POWER_BY_TEXT') ?>"
							   target="_blank" <?php echo method_exists('T3', 'isHome') && T3::isHome() ? '' : 'rel="nofollow"' ?>><?php echo JText::_('T3_POWER_BY_HTML') ?></a>
						</div>
					<?php endif; ?>
				</div>
		</section>
	</div>

</footer>
<!-- //FOOTER -->