<?php 
/**
 * JLVotes
 *
 * @version 1.3
 * @package com_jlvotes
 * @author Anton Voynov (anton@joomline.ru)
 * @copyright (C) 2010 by Anton Voynov(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php?option=com_jlvotes" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'VOTESETTINGS' ); ?></legend>
		<table class="admintable" width="100%">
		<tr>
			<td align="right" class="key" style="width: 200px;">
				<label for="event_name">
					<?php echo JText::_( 'ALLOWGUESTVOTE' ); ?>:
				</label>
			</td>
			<td>
				<input type="checkbox" name="allow_guest" id="allow_guest" <?php echo $this->settings->allow_guest == 1 ? 'checked' : '';?> value="1" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="event_name">
					<?php echo JText::_( 'ADDVOTETOEACH' ); ?>:
				</label>
			</td>
			<td>
				<input type="checkbox" name="add2all" id="add2all" <?php echo $this->settings->add2all == 1 ? 'checked' : '';?> value="1" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="event_name">
					<?php echo JText::_( "ALLOWCHANGEOPINION" ); ?>:
				</label>
			</td>
			<td>
				<input type="checkbox" name="allow_revote" id="allow_revote" <?php echo $this->settings->allow_revote == 1 ? 'checked' : '';?> value="1" />
			</td>
		</tr>
	</table>
	<div style="text-align: center;">
		Developed by a team JoomLine 2010<br />
		Site team <a href=" http://www.joomline.ru">http://www.joomline.ru</a>
	</div>
	<input type="hidden" name="option" value="com_jlvotes" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</fieldset>
</div>
<div class="clr"></div>

</form>