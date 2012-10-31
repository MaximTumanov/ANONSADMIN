<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $this->language; ?>" lang="<?php echo  $this->language; ?>" dir="<?php echo  $this->direction; ?>" id="minwidth" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="../templates/system/css/system.css" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />

<?php if(JModuleHelper::isEnabled('menu')) : ?>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/menu.js"></script>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/index.js"></script>
<?php endif; ?>


<?php

$db = &JFactory::getDBO();
$event_today_count_query = "SELECT count(*) FROM `#__events` as `event` 
	JOIN `#__events_dates` as `dates` ON event.id_event = dates.id_event
	WHERE DATE(dates.date) = DATE(NOW())
	AND dates.type = '3'
	AND event.published = '1'";

$db->setQuery($event_today_count_query);
$event_today_count = $db->loadResult();

$event_tomorrow_count_query = "SELECT count(*) FROM `#__events` as `event` 
	JOIN `#__events_dates` as `dates` ON event.id_event = dates.id_event
	WHERE DATE(dates.date) = (CURRENT_DATE + INTERVAL 1 DAY)
	AND dates.type = '3'
	AND event.published = '1'";

$db->setQuery($event_tomorrow_count_query);
$event_tomorrow_count = $db->loadResult();

$event_total_count_query = "SELECT count(*) FROM `#__events` as `event` 
	JOIN `#__events_dates` as `dates` ON event.id_event = dates.id_event
	WHERE DATE(dates.date) >= DATE(NOW())
	AND dates.type = '3'
	AND event.published = '1'";

$db->setQuery($event_total_count_query);
$event_total_count = $db->loadResult();

$event_not_published_count_query = "SELECT count(*) FROM `#__events` as `event` 
	JOIN `#__events_dates` as `dates` ON event.id_event = dates.id_event
	WHERE DATE(dates.date) >= DATE(NOW())
	AND dates.type = '3'
	AND event.published != '1'";

$db->setQuery($event_not_published_count_query);
$event_not_published_count = $db->loadResult();

$db->setQuery("SELECT * FROM `#__places` WHERE published = '1'");
$place_total_count = $db->loadResult();

$db->setQuery("SELECT * FROM `#__cinema` WHERE published = '1'");
$cinema_total_count = $db->loadResult();

?>

<style type="text/css">
.right.fix_padding p{
	padding: 2px 0!important;
	font-size: 12px!important;
}

.right.fix_padding .titles{
	color: #52C9E2;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body id="minwidth-body">
	<div id="border-top">
		<div id="logo_admin">
			<div class="right">
					<p><a href="<?php echo JRoute::_('index.php?option=com_login&task=logout')?>">Выход</a>&nbsp;&nbsp;<a href="../" target="_blank">На сайт &rarr;</a></p>
          <p><a href="http://metrika.yandex.ru/" target="_blank">Яндекс.Метрика &rarr;</a></p>
          <p><a href="https://www.google.com/accounts/ServiceLogin?hl=ru&service=analytics" target="_blank">Google Analytics &rarr;</a></p>
      </div>

      <div class="right fix_padding">
      	<p class="titles">Места:</p>
      	<p> - организаторы: <?php echo $place_total_count?></p>
      	<p> - кинотеатры: <?php echo $cinema_total_count?></p>
      </div>

      <div class="right fix_padding">
      	<p class="titles">События:</p>
      	<p style="color: #E2D152"> - сегодня: <?php echo $event_today_count?></p>
      	<p style="color: #E2D152"> - завтра: <?php echo $event_tomorrow_count?></p>
      	<p> - всего актуальных: <?php echo $event_total_count?></p>
      	<p> - неопубликовано: <?php echo $event_not_published_count?></p>
      </div>
			<span class="title"><?php echo $mainframe->getCfg( 'sitename' ); ?></span>
		</div>
	</div>
	<div id="header-box">
		<div id="module-status">
			<jdoc:include type="modules" name="status"  />
		</div>
		<div id="module-menu">
			<jdoc:include type="modules" name="menu" />
		</div>
		<div class="clr"></div>
	</div>
	<div id="content-box">
		<div class="border">
			<div class="padding">
				<div id="element-box">
					<jdoc:include type="message" />
					<div class="t">
						<div class="t">
							<div class="t"></div>
						</div>
					</div>
					<div >
						<table class="adminform">
						<tr>
							<td valign="top">
								<jdoc:include type="modules" name="icon" />
							</td>
						</tr>
						</table>
						<div class="clr"></div>
					</div>
					<div class="b">
						<div class="b">
							<div class="b"></div>
						</div>
					</div>
				</div>
				<noscript>
					<?php echo  JText::_('WARNJAVASCRIPT') ?>
				</noscript>
				<div class="clr"></div>
				<div id="footer"><?php echo date("Y")?>&nbsp;&copy;&nbsp;<a href="http://www.anons.dp.ua" target="_blank">anons.dp.ua</a></div>
			</div>
		</div>
	</div>
</body>
</html>
