<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div class="jlv_box">
	<?php echo JText::_('LIKED') ?>
	<span id='jllikecnt_<?=$id?>' style='font-size:16pt; font-weight:bold'><?=$votecountplus?></span> <br/>
	<div id='voteButtons_<?=$id?>'>
		<?php if ($user->guest && $jlvotes_settings['allow_guest']['value'] == 0) : ?>
			<br />
		<?php else: ?>
			<?php if (isset($jlvotescount['votes'][$id]) && $jlvotes_settings['allow_revote']['value'] == 0) :?>
				<i><span style="color: gray;"><?php echo JText::_('ALREADYVOTE') ?></span></i> <br>
			<?php else: ?>
				<input type='button' value='<?php echo JText::_('ILIKEIT') ?>' <?=$likeBtn?> style='width:120px' onclick='ajxjlVote(<?=$id?>, 0)'><br/>  
				<input type='button' value='<?php echo JText::_('IDONTLIKEIT') ?>' <?=$notlikeBtn?> style='width:120px' onclick='ajxjlVote(<?=$id?>, 1)'><br/>
			
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div id="loadimg_<?=$id?>" style="display: none;"><img src="/components/com_jlvotes/ajax-loader.gif" alt="."><br /></div>
	<?php echo JText::_('DONTLIKED') ?>
	<span id='jlnotlikecnt_<?=$id?>'><?=$votecountminus?></span> <br/>
</div>		
