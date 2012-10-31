<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'Debug Settings' ); ?></legend>
	<table class="admintable" cellspacing="1">

		<tbody>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Enable Debugging' ); ?>::<?php echo JText::_('TIPDEBUGGINGINFO'); ?>">
					<?php echo JText::_( 'Debug System' ); ?>
				</span>
			</td>
			<td>
				<?php echo $lists['debug']; ?>
			</td>
		</tr>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip">
					<?php echo JText::_( 'In Production' ); ?>
				</span>
			</td>
			<td>
				<input type="checkbox" name="in_production" id="in_production" value="1" <?php if ($row->in_production) echo 'checked="checked"';?>>
			</td>
		</tr>
	</table>
</fieldset>
