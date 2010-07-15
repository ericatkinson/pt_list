<?php if (! defined('EXT')) exit('Invalid file request');


/**
 * P&T List Fieldtype Class for EE1
 *
 * @package   P&T List
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2010 Pixel & Tonic, LLC
 */
class Pt_list extends Fieldframe_Fieldtype {

	var $info = array(
		'name'             => 'P&amp;T List',
		'version'          => '1.0',
		'versions_xml_url' => 'http://pixelandtonic.com/ee/versions.xml'
	);

	// --------------------------------------------------------------------

	/**
	 * Theme URL
	 */
	private function _theme_url()
	{
		if (! isset($this->_theme_url))
		{
			global $PREFS;
			$theme_folder_url = $PREFS->ini('theme_folder_url', 1);
			$this->_theme_url = $theme_folder_url.'third_party/pt_list/';
		}

		return $this->_theme_url;
	}

	/**
	 * Include Theme CSS
	 */
	private function _include_theme_css($file)
	{
		$this->insert('head', '<link rel="stylesheet" type="text/css" href="'.$this->_theme_url().$file.'" />');
	}

	/**
	 * Include Theme JS
	 */
	private function _include_theme_js($file)
	{
		$this->insert('body', '<script type="text/javascript" src="'.$this->_theme_url().$file.'"></script>');
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field
	 */
	function display_field($field_name, $data, $settings, $cell = FALSE)
	{
		$this->_include_theme_css('styles/pt_list.css');
		$this->_include_theme_js('scripts/pt_list.js');

		$field_id = str_replace(array('[', ']'), array('_', ''), $field_name);

		if (! $cell)
		{
			$this->insert_js('new ptList(jQuery("#'.$field_id.'"));');
		}

		$r = '<ul id="'.$field_id.'" class="pt-list ee1">';

		if ($data)
		{
			$list = explode("\n", $data);

			foreach($list as $li)
			{
				$r .= '<li><span>'.$li.'</span>'
				    .   '<input type="hidden" name="'.$field_name.'[]" value="'.str_replace(array('"', '&'), array('&quot;', '&amp;'), $li).'" />'
				    . '</li>';
			}
		}

		$r .=   '<li class="input last"><input type="text" name="'.$field_name.'[]" /></li>'
		    . '</ul>';

		return $r;
	}

	/**
	 * Display Cell
	 */
	function display_cell($cell_name, $data, $settings)
	{
		$this->_include_theme_js('scripts/matrix2.js');

		return $this->display_field($cell_name, $data, $settings, TRUE);
	}
}