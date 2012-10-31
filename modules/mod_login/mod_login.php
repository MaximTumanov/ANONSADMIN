<?php
$db = &JFactory::getDBO();

$action = 'login';
$action_title = 'Войти';
$form_title = 'Авторизация';
$error = false;

if (isset($_POST) && count($_POST)) {
	$data = array();
	if ($_POST['token']) {
		$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		$resp = json_decode($s, true);
			
		$q = "SELECT `id`, `name`, `login` FROM `#__user` WHERE `uid` = '{$resp["uid"]}'  AND `network` = '{$resp["network"]}'";
		$db->setQuery($q); 
		$user = $db->loadObject();

		if (!$user->id) {
			jimport('joomla.user.helper');
	
			$db->setQuery("INSERT INTO `#__user` VALUES('', '{$resp["uid"]}', '{$resp["network"]}', '{$resp["first_name"]} {$resp["last_name"]}', '', '', '')");
			$db->query();
			$lastid = $db->insertid();
		}		

		$data[0] = ($user->id) ? md5(time()."anons.dp.ua") . $user->id : md5(time()."anons.dp.ua") . $lastid;
		$data[1] = ($user->name) ? $user->name : "{$resp["first_name"]} {$resp["last_name"]}";
		
		setcookie('anons_dp_ua', implode('|', $data), time() + (3600*24));
		JController::setRedirect(JFactory::getURI()->_uri);
		JController::redirect();

	} elseif ($_POST['method'] && $_POST['method'] == 'login') {
		$login = JRequest::getVar('login');
		$pass  = md5(JRequest::getVar('pass'));
		
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id`, `name`, `login` FROM `#__user` WHERE `login` = '{$login}'  AND `pass` = '{$pass}'"); 
		$user = $db->loadObject();
		
		if ($user) {
			$data[0] = md5(time()."anons.dp.ua") . $user->id;
			$data[1] = ($user->name) ? $user->name : "{$user->login}";			
		} else {
			$error = 'Пользователь не найден!';
		}		
	} elseif ($_POST['method'] && $_POST['method'] == 'logout') {
		setcookie('anons_dp_ua', '', time() - 3600);
	}
		
	if (!$error && ($user->id || $lastid)) {
		setcookie('anons_dp_ua', implode('|', $data), time() + (3600*24));
	}
	
}


if (isset($_COOKIE["anons_dp_ua"])) {
	if (!isset($_POST['method']) && $_POST['method'] != 'login') {
		$data = explode('|', $_COOKIE["anons_dp_ua"]);
		$action = 'logout';
		$action_title = 'Выйти';
		$form_title = 'Мой кабинет';
		
		$id_user = (int) substr($data[0], 32);
		
		$db->setQuery("SELECT count(*) FROM `#__user_events` WHERE `id_user` = '{$id_user}' AND DATE(date) >= DATE(NOW())");
		$my_events_count = $db->loadResult();
		
		$db->setQuery("SELECT count(*) FROM `#__user_places` WHERE `id_user` = '{$id_user}'");
		$my_places_count = $db->loadResult();		
	}
}

function makeEventsGood($i = 0) {
	$str = $i . " событий";
	if ($i == 1) {
		$str = $i . " событие";
		
	} else if ($i > 1) {
		if ($i < 5) {
			$str = $i . " события";
		}
	}
	
	return $str;
}

function makePlacesGood($i) {
	$str = $i . " любимых мест";
	if ($i == 1) {
		$str = $i . " любимое место";
		
	} else if ($i > 1) {
		if ($i == 2 || $i == 3 || $i == 4) {
			$str = $i . " любимых места";
		} else {
			$str = $i . " любимых мест";
		} 
	}
	
	return $str;
}

?>
<!-- MY -->
<div id="login">
	<h3 class="title fiolet"><?php echo $form_title?></h3>
	 	<form action="<?php echo JFactory::getURI()->_uri;?>" method="post" enctype="application/x-www-form-urlencoded">
	   		<?php if ($action == 'login'):?>
	   		<input type="text" class="auth" name="login" id="log" value="Логин" />
	   		<input type="text" class="auth" name="pass" id="pass" value="Пароль" />
			
	   		<p class="error"><?php echo $error?></p>
	   		<?php else:?>
	   		<div class="user_block">
	   			<p class="hello">Привет, <?php echo $data[1];?></p>
	   			<div class="user_info">
	   				<p>Ты собираешься посетить <a href="<?php echo JRoute::_("index.php?option=com_user&layout=my_events&Itemid=" . MY_ITEMID)?>" title="Посмотреть события" id="my_events"><?php echo makeEventsGood($my_events_count)?></a></p>
	   				<p>У тебя <a href="<?php echo JRoute::_("index.php?option=com_user&layout=my_places&Itemid=" . MY_ITEMID)?>" title="Посмотреть мои любимые места" id="my_places"><?php echo makePlacesGood($my_places_count)?></a></p>
	   			</div>
	   		</div>
	   		<?php endif;?>
	   		<div class="button bold <?php echo $action?>"><?php echo $action_title?></div>
	   		<input type="hidden" name="method" value="<?php echo $action ?>">
	   		
	   		<?php if ($action == 'login'):?>
	   		<div id="uLogin"></div>
	   		<?php endif;?>
	   		
	   	</form>
        <i class="shadow"></i>
</div>
<!-- MY -->