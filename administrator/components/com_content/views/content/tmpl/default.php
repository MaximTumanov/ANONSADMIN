<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr>
		<td width="100%" valign="top">
			<div id="cpanel">
			<?php

			$link = 'index.php?option=com_content&view=section';
			$this->_quickiconButton( $link, 'icon-48-section.png', JText::_('Разделы') );

			$link = 'index.php?option=com_content&view=category';
			$this->_quickiconButton( $link, 'icon-48-category.png', JText::_('Категории') );

			$link = 'index.php?option=com_content&view=item&layout=form';
			$this->_quickiconButton( $link, 'icon-48-article-add.png', JText::_('Добавить материал') );

			$link = 'index.php?option=com_content&view=item';
			$this->_quickiconButton( $link, 'icon-48-article.png', JText::_('Все материалы') );

			?>
		</div>
		</td>
      </tr>
   </table>
 </form>