<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined( '_JOS_QUICKICON_MODULE' ))
{
	/** ensure that functions are declared only once */
	define( '_JOS_QUICKICON_MODULE', 1 );

	function quickiconButton( $link, $image, $text )
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
		$template	= $mainframe->getTemplate();

		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image.site',  $image, '/templates/'. $template .'/images/header/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
		<?php
	}



	?>
	<div id="cpanel">
		<?php $user = &JFactory::getUser();
		if ( $user->get('gid') > 23 ) {
			$link = 'index.php?option=com_menus';
			quickiconButton( $link, 'icon-48-menumgr.png', JText::_( 'Меню' ) );

			$link = 'index.php?option=com_users';
			quickiconButton( $link, 'icon-48-user.png', JText::_( 'Пользователи' ) );

			$link = 'index.php?option=com_config';
			quickiconButton( $link, 'icon-48-config.png', JText::_( 'Общие настройки' ) );
		} ?>
		<br clear="all" />

		<?php

		$link = 'index.php?option=com_contact';
		quickiconButton( $link, 'icon-48-contacts.png', JText::_( 'Контакты' ) );		
		
		$link = 'index.php?option=com_content';
		quickiconButton( $link, 'icon-48-article.png', JText::_( 'Менеджер материалов' ) );

		$link = 'index.php?option=com_media';
		quickiconButton( $link, 'icon-48-media.png', JText::_( 'Медиаменеджер' ) );

		?>
	
		<br clear="all" />			
		
		<?php 		
	
		$link = 'index.php?option=com_events&view=event';
		quickiconButton( $link, 'icon-48-stats.png', JText::_( 'События' ) );

		$link = 'index.php?option=com_events&view=parser';
		quickiconButton( $link, 'icon-48-spider.png', JText::_( 'Парсер' ) );

		//echo '<br clear="all" />';

		$link = 'index.php?option=com_events&view=places';
		quickiconButton( $link, 'icon-48-user.png', JText::_( 'Организаторы' ) );

		//echo '<br clear="all" />';

		$link = 'index.php?option=com_films&view=cinema';
		quickiconButton( $link, 'icon-48-cinema.png', JText::_( 'Кинотеатры' ) );


		$link = 'index.php?option=com_films&view=films';
		quickiconButton( $link, 'icon-48-video.png', JText::_( 'Фильмы' ) );

		//echo '<br clear="all" />';

		$link = 'index.php?option=com_blog';
		quickiconButton( $link, 'icon-48-component.png', JText::_( 'Блог' ) );

		$link = 'index.php?option=com_ourusers&view=user';
		quickiconButton( $link, 'icon-48-component.png', JText::_( 'Юзеры' ) );
		
		?>
		
	</div>
		<div style="clear: both"></div>
		<!--table>
			<tr>
				<td colspan="4"><b>События</b></td>
			</tr>
			<tr>
				<td>Сегодня: <b><?php echo $event_today_count?></b></td>
				<td>Завтра: <b><?php echo $event_tomorrow_count?></b></td>
				<td>Всего актуальных: <b><?php echo $event_total_count?></b></td>
				<td>Неопубликовано: <b><?php echo $event_not_published_count?></b></td>
			</tr>
			<tr>
				<td colspan="4">Места: <b><?php echo $place_total_count?></b></td>
			</tr>			
		</table-->

	<?php
}