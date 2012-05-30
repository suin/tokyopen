<?php
/**
 * form_type  : textbox, textarea, select, yesno, date, datetime
 * value_type : bool, integer, float, string, text, date, datetime
 */

$configs = array(
	array(
		'name'      => 'thumb_width',
		'title'     => "Thumbnail width",
		'desc'      => "",
		'form_type' => 'textbox',
		'value_type'=> 'int',
		'default'   => 144,
		'options'   => array(),
	),
	array(
		'name'      => 'thumb_height',
		'title'     => "Thumbnail height",
		'desc'      => "",
		'form_type' => 'textbox',
		'value_type'=> 'int',
		'default'   => 144,
		'options'   => array(),
	),
	array(
		'name'      => 'max_width',
		'title'     => "Photo max width",
		'desc'      => "",
		'form_type' => 'textbox',
		'value_type'=> 'int',
		'default'   => 1024,
		'options'   => array(),
	),
	array(
		'name'      => 'max_height',
		'title'     => "Photo max height",
		'desc'      => "",
		'form_type' => 'textbox',
		'value_type'=> 'int',
		'default'   => 1024,
		'options'   => array(),
	),
	array(
		'name'      => 'max_file_size',
		'title'     => "File max size",
		'desc'      => "",
		'form_type' => 'textbox',
		'value_type'=> 'int',
		'default'   => 5242880,
		'options'   => array(),
	),
);
