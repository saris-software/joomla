<?php
/**
 * @Copyright
 *
 * @package    Visitorcounter - VCNT for Joomla! 3
 * @author     Viktor Vogel <admin@kubik-rubik.de>
 * @version    3.3.0 - 2018-05-22
 * @link       https://joomla-extensions.kubik-rubik.de/vcnt-visitorcounter
 *
 * @license    GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');
echo '<!-- VCNT - Visitorcounter for Joomla! 3.x - Kubik-Rubik Joomla! Extensions - Viktor Vogel -->';
?>
<div class="visitorcounter <?php echo $moduleclassSfx ?>">
    <?php if (!$horizontal) : ?>
        <?php if ($sToday) : ?>
            <p>
                <?php echo $today; ?>
                <span class="floatright"><?php echo $todayVisitors; ?></span>
            </p>
        <?php endif; ?>
        <?php if ($sYesterday) : ?>
            <p>
                <?php echo $yesterday; ?>
                <span class="floatright"><?php echo $yesterdayVisitors; ?></span>
            </p>
        <?php endif; ?>
        <?php if ($sWeek) : ?>
            <p>
                <?php echo $xWeek; ?>
                <span class="floatright"><?php echo $weekVisitors; ?></span>
            </p>
        <?php endif; ?>
        <?php if ($sMonth) : ?>
            <p>
                <?php echo $xMonth; ?>
                <span class="floatright"><?php echo $monthVisitors; ?></span>
            </p>
        <?php endif; ?>
        <?php if ($sAll) : ?>
            <p>
                <?php echo $all; ?>
                <span class="floatright"><?php echo $allVisitors; ?></span>
            </p>
        <?php endif; ?>
    <?php elseif ($horizontal == 1) : ?>
        <p>
            <?php $numbers = $sToday + $sYesterday + $sWeek + $sMonth + $sAll - 1; ?>
            <?php if ($horText) : ?>
                <strong><?php echo $horText; ?></strong>
            <?php endif; ?>
            <?php if ($sToday) : ?>
                <?php echo $today . ' ' . $todayVisitors; ?>
            <?php endif; ?>
            <?php if ($numbers AND $sToday) : ?>
                <?php echo $separator; ?>
                <?php $numbers-- ?>
            <?php endif; ?>
            <?php if ($sYesterday) : ?>
                <?php echo $yesterday . ' ' . $yesterdayVisitors; ?>
            <?php endif; ?>
            <?php if ($numbers AND $sYesterday) : ?>
                <?php echo $separator; ?>
                <?php $numbers-- ?>
            <?php endif; ?>
            <?php if ($sWeek) : ?>
                <?php echo $xWeek . ' ' . $weekVisitors; ?>
            <?php endif; ?>
            <?php if ($numbers AND $sWeek) : ?>
                <?php echo $separator; ?>
                <?php $numbers-- ?>
            <?php endif; ?>
            <?php if ($sMonth) : ?>
                <?php echo $xMonth . ' ' . $monthVisitors; ?>
            <?php endif; ?>
            <?php if ($numbers AND $sMonth) : ?>
                <?php echo $separator; ?>
                <?php $numbers-- ?>
            <?php endif; ?>
            <?php if ($sAll) : ?>
                <?php echo $all . ' ' . $allVisitors; ?>
            <?php endif; ?>
        </p>
    <?php elseif ($horizontal == 2) : ?>
        <p>
            <?php if ($horText) : ?>
                <strong><?php echo $horText; ?></strong>
            <?php endif; ?>
            <?php echo $allVisitors . ' (' . ($todayVisitors) . ')'; ?>
        </p>
    <?php endif; ?>
    <?php if ($whoisonline == 1 || $whoisonline == 2) : ?>
        <?php $guest = JText::plural('MOD_VISITORCOUNTER_GUESTS', $usersOnline['guest']); ?>
        <?php $member = JText::plural('MOD_VISITORCOUNTER_MEMBERS', $usersOnline['user']); ?>
        <p class="whoisonline">
            <?php echo JText::sprintf('MOD_VISITORCOUNTER_USERONLINE', $guest, $member); ?>
        </p>
        <?php if ($whoisonline == 2 && !empty($usersOnline['usernames'])) : ?>
            <ul class="whoisonline_users">
                <?php foreach ($usersOnline['usernames'] as $userOnline) : ?>
                    <li>
                        <?php if (!empty($whoisonlineLinknames)) : ?>
                            <?php $userLink = 'index.php?option=com_users&view=profile&member_id=' . (int) $userOnline['userid'] . $itemId; ?>
                            <?php if ($whoisonlineLinknames == 2) : ?>
                                <?php $userLink = 'index.php?option=com_comprofiler&task=userprofile&user=' . (int) $userOnline['userid'] . $itemId; ?>
                            <?php endif; ?>
                            <a href="<?php echo $userLink; ?>">
                                <?php echo $userOnline['username']; ?>
                            </a>
                        <?php else : ?>
                            <?php echo $userOnline['username']; ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($linkToProject) : ?>
        <p class="small">
            <a href="https://joomla-extensions.kubik-rubik.de/" target="_blank" title="Kubik-Rubik Joomla! Extensions - Qualitative, free extensions for Joomla!">
                Kubik-Rubik Joomla! Extensions
            </a>
        </p>
    <?php endif; ?>
</div>
