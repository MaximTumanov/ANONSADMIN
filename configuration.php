<?php
class JConfig {
	var $offline = '0';
	var $editor = 'jckeditor';
	var $list_limit = '20';
	var $debug = '0';
	var $in_production = '0';
	var $secret = '6zyNpPYgpiVWoAbq';
	var $gzip = '0';
	var $error_reporting = '-1';
	var $log_path = '/var/www/anons4/anons.dp.ua/logs';
	var $tmp_path = '/var/www/anons4/anons.dp.ua/tmp';
	var $live_site = '';
	var $sef = '0';
	var $sef_rewrite = '0';
	var $sef_suffix = '0';
	var $caching = '0';
	var $cachetime = '150';
	var $cache_handler = 'file';
	var $memcache_settings = array();
	var $dbtype = 'mysql';
	var $host = 'localhost';
	var $user = 'u_producti3';
	var $db = 'production';
	var $dbprefix = 'jos_';
	var $mailer = 'mail';
	var $mailfrom = 'site@anons.dp.ua';
	var $fromname = 'anons.dp.ua';
	var $lifetime = '150';
	var $session_handler = 'none';
	var $register_tmpl = '';
	var $reset_tmpl = '';
	var $password = 's8onj4WA';
	var $sitename = 'Anons.dp.ua';
	var $MetaDesc = '';
	var $MetaKeys = '';
	var $offline_message = 'Сайт сейчас закрыт на техническое обслуживание. Пожалуйста зайдите позже.';
}
?>