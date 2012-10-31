<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'Mail Settings' ); ?></legend>
	<table class="admintable" cellspacing="1">

		<tbody>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Mailer' ); ?>::<?php echo JText::_( 'TIPMAILER' ); ?>">
						<?php echo JText::_( 'Mailer' ); ?>
					</span>
			</td>
			<td>
				<?php echo $lists['mailer']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Mail From' ); ?>::<?php echo JText::_( 'TIPMAILFROM' ); ?>">
						<?php echo JText::_( 'Mail From' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="mailfrom" size="30" value="<?php echo $row->mailfrom; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'From Name' ); ?>::<?php echo JText::_( 'TIPFROMNAME' ); ?>">
						<?php echo JText::_( 'From Name' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="fromname" size="30" value="<?php echo $row->fromname; ?>" />
			</td>
		</tr>
		</tbody>
	</table>
</fieldset>
