<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// no direct access
defined('_JEXEC') or die();

class TableYjContent extends JTable
{
        var $id = null;
        var $title = null;
		var $type ="YjContent";
        
        function __construct(&$db)
        {
                parent::__construct( '#__content', 'id', $db );
        }
}

?>