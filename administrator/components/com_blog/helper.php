<?php

class BlogHelper
{
	/**
	* выводит список select
	* @param array $array массив 'значение' => текст
	* @param string $id атрибут ID
	* @param string $text текст нулевого индекса
	* @param int $active ID активного 
	*/		
	function selectList($array, $id, $text, $active = NULL, $style = 'style="width:200px"')
	{
		$list[] = JHTML::_('select.option', '0', '- '.JText::_($text).' -');

		if( is_array($array) ):
			$list   = array_merge($list, $array);
		endif;
		$category = JHTML::_('select.genericlist',  $list, $id, 'class="inputbox" size="1" ' . $style, 'value', 'text', $active);
		return $category;
	}
	
	function selectLists($array, $id, $text, $active = NULL) {
		$input = array();
		if (is_array($array)) {
			$input[] = "<select name='{$id}' id='{$id}'>";
			foreach ($array as $key => $val) {
				$input[] = ($key == $active) ? "<option selected value='{$key}'>{$val}</option>" : "<option value='{$key}'>{$val}</option>";
			}
			$input[] = "</select>";
		}
		return implode('', $input);
	}
	
	function selectYN($active, $id) {
		$list = array();
		$list[] = JHTML::_('select.option', '0', 'Нет');
		$list[] = JHTML::_('select.option', '1', 'Да');
		return JHTML::_('select.genericlist',  $list, $id, 'class="inputbox" size="1" style="width:50px" "', 'value', 'text', $active);
	}
	
	function selectListMulti($array, $id, $active = array()) {
		$sel = array();
		$sel[] = "<select name='{$id}[]' id='{$id}' class='inputbox' multiple='true' size='1' style='width: 200px; height: 200px'>";
			foreach ($array as $opt):
				$sel[] = (@in_array($opt->value, $active)) 
							? "<option selected='selected' value='{$opt->value}'>{$opt->text}</option>" 
							: "<option value='{$opt->value}'>{$opt->text}</option>";
			endforeach;
		$sel[] = "</select>";
		return implode('', $sel);
	}
	
	function getMenuName($view){
		$menu = array();
		$menu['blog'] = JText::_('список блогов');
		$string = JString::strtolower($menu[$view]);
		return $string;
	}

	function getPlaceTitle($view) {
		$id = JRequest::getInt('id');
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `title` FROM `#__blog` WHERE `id_blog` = '{$id}'");
		return JString::cropstr(JString::strtolower($db->loadResult()), 5);
	}

}

?>