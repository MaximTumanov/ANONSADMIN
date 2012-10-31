<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />

<link href="templates/<?php echo $this->template ?>/css/login.css" rel="stylesheet" type="text/css" />

</head>

<body>
	<div id="header" style="width:100%; margin-bottom:20px; background:url(templates/Anons/images/logo-admin.jpg) no-repeat scroll 0 0 #42434A">
    	<p style="color:#FFF; padding-left:150px; font-size:22px; line-height:110px"> [ вход в админпанель ]</p>
    </div>
    
    <div style="margin:0 auto; width:300px; padding:50px; margin-top:100px; border:1px solid #CCC">
		<jdoc:include type="component" />
        <div class="clr"></div>   
    </div>
    
</body>
</html>
