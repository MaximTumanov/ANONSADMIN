<?php
header("Cache-Control: public"); 
	header("Expires: " . date("r", time() + 3600 * 24 * 10));
  if(!$_GET['p']) return;
  $param = base64_decode($_GET['p']);
  echo file_get_contents("http://adminka.anons.dp.ua/images/sunny/{$param}");
?>