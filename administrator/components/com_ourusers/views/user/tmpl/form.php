<?php
$id = JRequest::getVar('id');
$model = $this->getModel();
$editor   =& JFactory::getEditor();

jimport('joomla.html.pane');
$tabs = & JPane::getInstance('tabs');

if($id){
	$item      = $model->getItem($id);
	$fio   = $item->fio;
	$phone = $item->phone;
	$login = $item->login;
	$email = $item->email;
	$vip   = $item->vip;
	$public = $item->public;
	$desc = $item->desc;
	$id_place = $item->id_place;
}

$placeList  = OurusersHelper::selectList($model->getPlaceList(), 'id_place', 'Выбирите организатора', $id_place, 'style="width: 265px"');

$vipList    = OurusersHelper::selectYN($vip, 'vip');
$pubList    = OurusersHelper::selectYN($public, 'public');
$deniedList = OurusersHelper::selectYN(0, 'denied');
JHTML::_('behavior.calendar');
?>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
<table class="admintable">
<tr>
	<td class="key"><label for="fio"><?php echo JText::_('ФИО');?></label></td>
	<td><input type="text" value="<?php echo $fio?>" readonly disabled maxlength="255" size="60"  name="fio" id="fio" class="inputbox"> </td>
</tr>

<tr>
	<td class="key"><label for="id_place"><?php echo JText::_('Организатор');?></label></td>
	<td><?php echo $placeList?></td>
</tr>

<tr>
	<td class="key"><label for="phone"><?php echo JText::_('Тел.');?></label></td>
	<td><input type="text" value="<?php echo $phone?>" maxlength="255" size="60"  name="phone" id="phone" class="inputbox"> </td>
</tr>

<tr>
	<td class="key"><label for="email"><?php echo JText::_('Email');?></label></td>
	<td><input type="text" value="<?php echo $email?>" maxlength="255" size="60"  name="email" id="email" class="inputbox"> </td>
</tr>

<tr>
	<td class="key"><label for="public"><?php echo JText::_('Может публиковать');?></label></td>
	<td><?php echo $pubList?></td>
</tr>

<tr>
	<td class="key"><label for="vip"><?php echo JText::_('Без модерации');?></label></td>
	<td><?php echo $vipList?></td>
</tr>

<tr>
	<td class="key"><label for="denied"><?php echo JText::_('Отказать!');?></label></td>
	<td><?php echo $deniedList?></td>
</tr>


<tr>
	<td colspan="2"><textarea style="width: 400px; height: 100px"><?php echo $item->desc?></textarea></td>
</tr>

</table>

<input type="hidden" name="option" value="<?php echo $this->component?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $this->view?>" />

</form>
  <script type="text/javascript">
		window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });
  </script>