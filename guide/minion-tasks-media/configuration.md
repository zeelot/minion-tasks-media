# Configuration

	return array(
		'compilers' => array(
			'js' => array(
				'order'		=> 10,
				'pattern'   => '/^(media\/js\/).*\.js$/',
				'class'     => 'Media_Compiler_JS',
				'options'   => array(
					'concat' => array(
						'main' => array(
							'order'   => 1,
							'pattern' => '/^media\/js.*/',
						),
					),
					'save_paths' => array(
						'minified'   => APPPATH.'media/js/compiled/app.min.js',
						'unminified' => APPPATH.'media/js/compiled/app.js',
					),
				),
			),
			'scss' => array(
				'order'		=> 1,
				'pattern'   => '/^(media\/css\/scss\/).*\.scss$/',
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
			'coffeescript' => array(
				'order'     => 1,
				// Regular expression the path must match
				'pattern'   => '/^media\/coffee\/.*\.coffee$/',
				'class'     => 'Media_Compiler_Coffee',
				'options'   => array(
					// Coffee project settings (relative from tmp_dir)
					'js_dir'		=> 'media/js',
					'coffee_dir'	=> 'media/coffee',
					// Where we create the temporary coffee project
					'tmp_dir' 		=> APPPATH.'cache/coffee',
					// We append js_dir to this path before saving generated js files
					'save_dir'      => rtrim(APPPATH, '/'),
				),
			),
		
			'couchapp' => array(
				'pattern'   => '/^(media\/couchapp\/).*/',
				'class'     => 'Media_Compiler_CouchApp',
				'options'   => array(
					'tmp_dir' => APPPATH.'cache/couchapp',
				),
			),
		),
	);

## Compilers

This is simply the list of compilers. The module comes with a JS, Coffeescript, SCSS, and CouchApp compiler but allows you to add or replace any other compiler. All compilers have 4 options defined. The `pattern` to match for the compiler, the `class` that handles compiling the files, the custom `options` to pass into that compiler, and an optional item called `order` that allows you to specify the order in which the different compilers are run.

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

## Coffeescript Options

There are four coffee script options. These options are `js_dir`, `coffee_dir`, `tmp_dir`, and `save_dir`. The coffee script compiler works similarly to the SCSS compiler. `js_dir` and `coffee_dir` are relative to your `APPPATH`. So if you keep your coffee files in `APPPATH.'media/coffee'` and you want the generated javascripts placed in `media/js`, you would set `js_dir` to `media/js` and `coffee_dir` to `media/coffee`. You should not use absolute locations for these options.

Again, like the SCSS, the `tmp_dir` option is simply where the compiler will store the coffee files temporarily. It should be a writable folder and needs to be an absolute path. The folder will be deleted upon completion of the compiler. The `save_dir` option is where you want the final JS files to be placed. They will be taken from the temporary coffee project and copied over to this directory. The `js_dir` option is appended to this path so set `save_dir` to `rtrim(APPPATH, '/')`, the js files will be sent to `APPPATH.'media/js'`.


## CouchApp Options

The CouchApp compiler only needs a `tmp_path` so it can create a couchapp project in that location with all the CouchDB documents from the various Kohana directories.