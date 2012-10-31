<?php
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.base.tree');
class JAdminCSSMenu extends JTree
{
	var $_css = null;

	function __construct()
	{
		$this->_root = new JMenuNode('ROOT');
		$this->_current = & $this->_root;
	}

	function addSeparator()
	{
		$this->addChild(new JMenuNode(null, null, 'separator', false));
	}

	function renderMenu($id = 'menu', $class = '')
	{
		global $mainframe;

		$depth = 1;

		if(!empty($id)) {
			$id='id="'.$id.'"';
		}

		if(!empty($class)) {
			$class='class="'.$class.'"';
		}

		/*
		 * Recurse through children if they exist
		 */
		while ($this->_current->hasChildren())
		{
			echo "<ul ".$id." ".$class.">\n";
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->renderLevel($depth++);
			}
			echo "</ul>\n";
		}

		if ($this->_css) {
			// Add style to document head
			$doc = & JFactory::getDocument();
			$doc->addStyleDeclaration($this->_css);
		}
	}

	function renderLevel($depth)
	{
		/*
		 * Build the CSS class suffix
		 */
		$class = '';
		if ($this->_current->hasChildren()) {
			$class = ' class="node"';
		}

		if($this->_current->class == 'separator') {
			$class = ' class="separator"';
		}

		if($this->_current->class == 'disabled') {
			$class = ' class="disabled"';
		}


		/*
		 * Print the item
		 */
		echo "<li".$class.">";

		/*
		 * Print a link if it exists
		 */
		if ($this->_current->link != null) {
			echo "<a class=\"".$this->getIconClass($this->_current->class)."\" href=\"".$this->_current->link."\">".$this->_current->title."</a>";
		} elseif ($this->_current->title != null) {
			echo "<a>".$this->_current->title."</a>\n";
		} else {
			echo "<span></span>";
		}

		/*
		 * Recurse through children if they exist
		 */
		while ($this->_current->hasChildren())
		{
			if ($this->_current->class) {
				echo '<ul id="menu-'.strtolower($this->_current->id).'"'.
					' class="menu-component">'."\n";
			} else {
				echo '<ul>'."\n";
			}
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->renderLevel($depth++);
			}
			echo "</ul>\n";
		}
		echo "</li>\n";
	}

	function getIconClass($identifier)
	{
		global $mainframe;

		static $classes;

		// Initialize the known classes array if it does not exist
		if (!is_array($classes)) {
			$classes = array();
		}

		if (!isset($classes[$identifier])) {
			if (substr($identifier, 0, 6) == 'class:') {
				// We were passed a class name
				$class = substr($identifier, 6);
				$classes[$identifier] = "icon-16-$class";
			} else {
				// We were passed an image path... is it a themeoffice one?
				if (substr($identifier, 0, 15) == 'js/ThemeOffice/') {
					// Strip the filename without extension and use that for the classname
					$class = preg_replace('#\.[^.]*$#', '', basename($identifier));
					$classes[$identifier] = "icon-16-$class";
				} else {
					if ($identifier == null) {
						return null;
					}
					// Build the CSS class for the icon
					$class = preg_replace('#\.[^.]*$#', '', basename($identifier));
					$class = preg_replace( '#\.\.[^A-Za-z0-9\.\_\- ]#', '', $class);

					$this->_css  .= "\n.icon-16-$class {\n" .
							"\tbackground: url($identifier) no-repeat;\n" .
							"}\n";

					$classes[$identifier] = "icon-16-$class";
				}
			}
		}
		return $classes[$identifier];
	}
}

class JMenuNode extends JNode
{
	var $title = null;
	var $id = null;
	var $link = null;
	var $class = null;
	var $active = false;


	function __construct($title, $link = null, $class = null, $active = false)
	{
		$this->title	= $title;
		$this->link		= JFilterOutput::ampReplace($link);
		$this->class	= $class;
		$this->active	= $active;
		$this->id		= str_replace(" ","-", $title);

	}
}
