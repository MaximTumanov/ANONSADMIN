<?php 
	$Itemid = JRequest::getInt('Itemid', 1);
	$layout = JRequest::getVar('layout');
	
	$hrefs = array(
		'main' => array(
			'href' => JURI::base(),
			'class' => ($Itemid == 1) ? 'a' : ''
		),
		'events' => array(
			'href' => JRoute::_("index.php?option=com_events&layout=all&Itemid=" . ITEMID_EVENTS),
			'class' => ($Itemid == ITEMID_EVENTS || ($Itemid == MY_ITEMID && $layout == 'my_events')) ? 'a' : '' 
		),
		'places' => array(
			'href' => JRoute::_("index.php?option=com_places&layout=all&Itemid=" . ITEMID_PLACES),
			'class' => ($Itemid == ITEMID_PLACES || ($Itemid == MY_ITEMID && $layout == 'my_places')) ? 'a' : ''
		)
	);
?>
				<div class="n-hr c">
				    <div class="n-hr-in">
				        <ul class="n-hr-l">
				            <li class="first <?php echo $hrefs['main']['class']?>">
				                <a href="<?php echo $hrefs['main']['href']?>">Главная</a>
				            </li>
				            <li class="<?php echo $hrefs['events']['class']?>">
				                <a href="<?php echo $hrefs['events']['href']?>">События</a>
				            </li>
				            <li class="<?php echo $hrefs['places']['class']?>">
				                <a href="<?php echo $hrefs['places']['href']?>">Места</a>
				            </li>
				            <li class="">
				                <a href="#">Новости</a>
				            </li>
				            <li class="">
				                <a href="#">Реклама</a>
				            </li>	
				            <li class="">
				                <a href="#">Партнеры</a>
				            </li>		
				            <li class="">
				                <a href="#">Контакты</a>
				            </li>				            
				        </ul>
				    </div>
				</div>