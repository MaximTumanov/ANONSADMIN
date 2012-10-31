<?php 
$error = $this->error->code;
if ( $error == '404' || $error == '500') {
        header("HTTP/1.0 404 Not Found");
} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<title>404 Нет такой страницы</title>
<?php
defined('JPATH_BASE') or die();
echo file_get_contents(JURI::root().'/index.php?option=com_content&view=404');