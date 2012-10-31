<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'Установки почтовых уведомлений' ); ?></legend>
    <table>
    	<tr>
        	<td width="50%" valign="top">
            <table class="admintable" cellspacing="1">
                <tbody>
                <tr>
                    <td width="185" class="key">
                        <span class="editlinktip hasTip" title="Уведомление о регистрации на сайте::Текст сообщения с кодом активации учетной записи">
                                Регистрация на сайте
                            </span>
                    </td>
                    <td valign="middle">
                        <textarea name="register_tmpl" id="register_tmpl" style="width:500px; height:100px"><?php echo $row->register_tmpl?></textarea>
                    </td>
                </tr>
                <tr>
                    <td width="185" class="key">
                        <span class="editlinktip hasTip" title="Восставонление пароля::Текст сообщения с новым паролем">
                                Восставонление пароля
                            </span>
                    </td>
                    <td valign="middle">
                        <textarea name="reset_tmpl" id="reset_tmpl" style="width:500px; height:100px"><?php echo $row->reset_tmpl?></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
            </td>
            <td valign="top" width="50%" style="padding-left:30px">
            	<p>Шаблоны, которые будут заменены информацией программно</p>
            	<p>
                	<span class="editlinktip hasTip" title="[user]::Имя пользователя">[user]</span> 
                	<span class="editlinktip hasTip" title="[site]::Название сайта">[site]</span>
                	<span class="editlinktip hasTip" title="[link]::Ссылка с кодом активации">[link]</span>
                    <span class="editlinktip hasTip" title="[pass]::Новый пароль">[pass]</span>
               </p>            	
            </td>
       </tr>
   </table>
</fieldset>