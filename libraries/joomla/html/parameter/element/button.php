<?php
defined('JPATH_BASE') or die();

class JElementButton extends JElement
{
	var	$_name = 'Button';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$doc = &JFactory::getDocument();
		$doc->addScript(JURI::base().'templates/Anons/js/jquery.js');
		$doc->addScript(JURI::base().'templates/Anons/js/addFile.js');
		$script = 'jQuery.noConflict();';
		$doc->addScriptDeclaration($script);
		
		$button = "<p style='margin-left:20px'><a href='javascript:void(0)' class='dashed' id='addFile'>Добавить файл</a></p>";
				
		$filter = '\.png$|\.gif$|\.jpg$|\.bmp$';
		$node->addAttribute('filter', $filter);
		$node->addAttribute('hide_default', '1');
		
		$parameter =& $this->_parent->loadElement('filelist');
		
		$db = &JFactory::getDBO();
		$q = " SELECT `params` FROM `#__modules` WHERE `id` = '35' ";
		$db->setQuery($q);
		$params = explode("\n", $db->loadResult());
		
		echo "<p style='margin-left:20px'>Файлы расположены <a href='index.php?option=com_media'>в папке banners</a></p>";
		
		$i = 1;
		echo $button;
		foreach ($params as $val):
			if(!$val) continue;
			list($txt, $file) = explode('=', $val);
			if(substr($file, -4, 1) != '.' || $file == -1) continue;
				echo "<table cellspacing='1' width='100%' class='paramlist admintable files'>
						<tr>
							<td width='40%' class='paramlist_key'>Баннер #{$i}</td>
							<td class='paramlist_value'>" . $parameter->fetchElement("banner_{$i}", $file, $node, $control_name) . "</td>
						</tr>
					</table>";
			$i++;
		endforeach;
		echo '<div id="dynamic"></div>';
		
	}
}
