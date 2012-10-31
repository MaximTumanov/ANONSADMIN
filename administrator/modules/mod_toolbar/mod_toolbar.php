<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.html.toolbar');
$bar = & JToolBar::getInstance('toolbar');
echo $bar->render('toolbar');