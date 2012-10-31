<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
class ConfigApplicationView
{
	function showConfig( &$row, &$lists )
	{
		global $mainframe;

		// Load tooltips behavior
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.switcher');

		// Load component specific configurations
		$table =& JTable::getInstance('component');
		$table->loadByOption( 'com_users' );
		$userparams = new JParameter( $table->params, JPATH_ADMINISTRATOR.DS.'components'.DS.'com_users'.DS.'config.xml' );
		$table->loadByOption( 'com_media' );
		$mediaparams = new JParameter( $table->params, JPATH_ADMINISTRATOR.DS.'components'.DS.'com_media'.DS.'config.xml' );

		// Build the component's submenu
		$contents = '';
		$tmplpath = dirname(__FILE__).DS.'tmpl';
		ob_start();
		require_once($tmplpath.DS.'navigation.php');
		$contents = ob_get_contents();
		ob_end_clean();

		// Set document data
		$document =& JFactory::getDocument();
		$document->setBuffer($contents, 'modules', 'submenu');

		?>
		<form action="index.php" method="post" name="adminForm" autocomplete="off">
		<div id="config-document">
			<div id="page-site">
				<table class="noshow">
					<tr>
						<td>
							<?php require_once($tmplpath.DS.'config_site.php'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php require_once($tmplpath.DS.'config_seo.php'); ?>
						</td>
					</tr>
				</table>
			</div>
			<div id="page-system">
				<table class="noshow">
					<tr>
						<td width="60%">
							<?php require_once($tmplpath.DS.'config_system.php'); ?>
							<fieldset class="adminform">
								<legend><?php echo JText::_( 'User Settings' ); ?></legend>
								<?php echo $userparams->render('userparams'); ?>
							</fieldset>
							<fieldset class="adminform">
								<legend><?php echo JText::_( 'Media Settings' ); ?>
				<span class="error hasTip" title="<?php echo JText::_( 'Warning' );?>::<?php echo JText::_( 'WARNPATHCHANGES' ); ?>">
					<?php echo ConfigApplicationView::WarningIcon(); ?>
				</span>
								</legend>
								<?php echo $mediaparams->render('mediaparams'); ?>
							</fieldset>
						</td>
						<td width="40%">
							<?php require_once($tmplpath.DS.'config_debug.php'); ?>
							<?php require_once($tmplpath.DS.'config_cache.php'); ?>
							<?php require_once($tmplpath.DS.'config_session.php'); ?>
						</td>
					</tr>
				</table>
			</div>
			<div id="page-server">
				<table class="noshow">
					<tr>
						<td width="100%">
							<?php require_once($tmplpath.DS.'config_server.php'); ?>
 							<?php require_once($tmplpath.DS.'config_database.php'); ?>
							<?php require_once($tmplpath.DS.'config_mail.php'); ?> 
                            <?php require_once($tmplpath.DS.'config_mailtemplates.php'); ?>                           
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="c" value="global" />
		<input type="hidden" name="live_site" value="<?php echo isset($row->live_site) ? $row->live_site : ''; ?>" />
		<input type="hidden" name="option" value="com_config" />
		<input type="hidden" name="secret" value="<?php echo $row->secret; ?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	function WarningIcon()
	{
		global $mainframe;
		$tip = '<img src="'.JURI::root().'includes/js/ThemeOffice/warning.png" border="0"  alt="" />';
		return $tip;
	}
}
