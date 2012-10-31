<?php 
	$db = JFactory::getDBO();
	$db->setQuery("SELECT `id_category`, `title` FROM `#__events_category` WHERE published = '1' ORDER BY title");
	$catList = $db->loadObjectList();
	JHTML::_('behavior.calendar');
?>
<script type="text/javascript">
	var actions = [
		["<?php echo JRoute::_("index.php?option=com_events")?>", <?php echo ITEMID_EVENTS?>],
		["<?php echo JRoute::_("index.php?option=com_places")?>", <?php echo ITEMID_PLACES?>]
	];
</script>
<div id="search">
	<h3 class="title fiolet">Быстрый поиск</h3>
	 	<form action="" method="post" enctype="application/x-www-form-urlencoded">
	  		<input type="text" name="text" id="text" value="Поиск" />
	  		<label for="type">Поиск по:</label>
	       		<select id="type" name="type">
	       			<option value="0">Событиям</option>
	       			<option value="1">Местам</option>
	       		</select>
	   		<label for="category">Выбирите категорию:</label>
	       		<select id="category" name="category">
	      			<option value="0">Нет</option>
	      			<?php foreach ($catList as $cat):?>
	       				<option value="<?php echo $cat->id_category?>"><?php echo $cat->title?></option>
	       			<?php endforeach;?>
	      		</select>
	   		<label for="date">Выбирите дату:</label>
	       		<select id="date" name="date">
					<option value="0">Нет</option>
	       			<option value="1">Сегодня</option>
	      			<option value="2">Завтра</option>
	      			<option value="3">Ближайшие выходные</option>
	       			<option value="4">Следующие выходные</option>
	      			<option value="5">Выбранный период</option>
	      		</select>
	      		<div id="set_dates">
	      			<?php echo JHTML::_('calendar', date('Y-m-d'), 'date_from', 'date_from', '%Y-%m-%d', array('readonly' => 'true', 'class'=>'inputbox', 'size'=>'10', 'maxlength'=>'10')); ?>
	      			<?php echo JHTML::_('calendar', date('Y-m-d'), 'date_to', 'date_to', '%Y-%m-%d', array('readonly' => 'true', 'class'=>'inputbox', 'size'=>'10', 'maxlength'=>'10')); ?>
	      		</div>
	   		<div class="button bold">Найти</div>
	   		<input type="hidden" name="layout" id="layout" value="search">
	   		<input type="hidden" name="Itemid" id="Itemid" value="">
	   	</form>
        <i class="shadow"></i>
</div>