<?php
defined('_JEXEC') or die('Restricted access');
function modChrome_rounded($module, &$params, &$attribs)
{
	if( $module->content ):
	?>
  <div class="t">
    <div class="t">
      <div class="t"></div>
    </div>
  </div>
  <div class="m">
	<?php echo $module->content;?>
    <div class="clr"></div>
  </div>
  <div class="b">
    <div class="b">
      <div class="b"></div>
    </div>
  </div>

    <?php 
	endif;
}
?>