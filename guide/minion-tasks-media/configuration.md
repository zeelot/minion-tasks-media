# Configuration

	return array(
		'compilers' => array(
			'js'   => array(
				'pattern'   => 'js',
				'class'     => 'Media_Compiler_JS',
				'options'   => array(
					'concat' => array(
						'main' => array(
							'order' => 1,
							'pattern' => '/^media\/js.*/',
						),
					),
					'save_paths' => array(
						'minified'   => APPPATH.'media/js/compiled/app.min.js',
						'unminified' => APPPATH.'media/js/compiled/app.js',
					),
				),
			),
			'scss'   => array(
				'pattern'   => 'scss',
				'class'     => 'Media_Compiler_SCSS',
				'options'   => array(
					'css_dir'         => 'media/css/compiled',
					'sass_dir'        => 'media/css/scss',
					'output_style'    => ':compressed',
					'images_dir'      => 'media/images/',
					'relative_assets' => TRUE,
					'tmp_dir'         => APPPATH.'cache/compass',
					'save_dir'        => rtrim(APPPATH, '/'),
				),
			),
			'couchapp' => array(
				'pattern' => '/^(media\/couchapp\/).*/',
				'class'     => 'Media_Compiler_CouchApp',
				'options'   => array(
					'tmp_dir'         => APPPATH.'cache/couchapp',
				),
			),
		),
	);

## Compilers

This is simply the list of compilers. The module comes with a JS, SCSS, and CouchApp compiler but allows you to add or replace any other compiler. All compilers have 3 items defined. The `pattern` to match for the compiler, the `class` that handles compiling the files, and custom `options` to pass into that compiler.

You can disable any of the compilers by simply setting the value to `NULL` or `FALSE`.

	return array(
		'compilers' => array(
			'js'   => FALSE,
	...

## JS Options

The Javascript compiler takes a set of `concat` options, and `save_paths`. In the `concat` options, you need to specify the groups to categorize the files into. Each group has an `order` value which determined in which order that group of files gets added to the final file, and a `pattern` value which needs to match the file's path in order to place the file in the group. The pattern is useful for excluding certain files from the compiling. For example, to exclude JQuery or other similar libraries. You do not have to worry about excluding the final compiled files from these groups because that is done automatically. The save paths are just where you would like to save the minified and unminified versions of the content.

## SCSS Options

The SCSS options include five options directly from `compass`. These are `css_dir`, `sass_dir`, `output_style`, `images_dir`, and `relative_assets`. Take a look at the compass documentation to find out what those are if you are confused. These options should be written relatively from your `APPPATH` or module's path. So if you keep your SCSS files in `APPPATH.'media/scss'` and you want the compiled CSS in `media/css/compiled`, you would set `css_dir` to `media/css/compiled` and `sass_dir` to `media/scss`. Do not use absolute paths here.

The `tmp_dir` option is simply where the compiler will create a temporary compass project. It just has to be writable and should be an absolute path. The `cache` directory is a good place for this because it is most likely already writable. These temporary files will be automatically deleted as soon as the compiler is done. The `save_dir` option is where you want the final CSS files to be placed. They will be taken from the compass project and copied over to this directory. The `css_dir` option is appended to this path so if you set `save_dir` to `rtrim(APPPATH, '/')`, the CSS files will be sent to `APPPATH.'media/css/compiled'`.

## CouchApp Options

The CouchApp compiler only needs a `tmp_path` so it can create a couchapp project in that location with all the CouchDB documents from the various Kohana directories.