<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'watch' => array(),
	'compilers' => array(
		'js'   => array(
			'extension' => 'js',
			'callback'  => 'Compiler_JS::compile',
		),
		'css'   => array(
			'extension' => 'css',
			'callback'  => 'Compiler_CSS::compile',
		),
		'scss'   => array(
			'extension' => 'scss',
			'callback'  => 'Compiler_SCSS::compile',
			'options'   => array(
				// Compass project settings (relative from tmp_dir)
				'css_dir'         => 'media/css/compiled',
				'sass_dir'        => 'media/css/scss',
				'output_style'    => ':compressed',
				'images_dir'      => 'media/images/',
				'relative_assets' => TRUE,
				// Where we create the temporary compass project
				'tmp_dir'         => APPPATH.'cache/compass',
				// We append css_dir to this path before saving compiled css files
				'save_dir'        => rtrim(APPPATH, '/'),
			),
		),
	),
	'cache' => array(
		'generate' => array(),
		'clear'    => array(),
	),
);