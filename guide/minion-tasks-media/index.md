# Media Tasks Module

This module adds Minion tasks that help work with the [Media](http://github.com/Zeelot/kohana-media) module. With this module, you will be able to develop your modules and applications without having to worry about compiling SCSS and JS files before deployment.

# Usage

There are two tasks in this module. `media:compile` and `media:watch`.

## Compile

The `media:compile` task will search through Kohana's Cascading Filesystem and compile files for you based on which compilers you have configured.

### JS

Javascript files are first concatenated, then saved to a minified, and an unminified file. The unminified version is useful for development environments where you would need to find possible bugs. If you use the minified version in development, all errors will seem to be on line 1. The minified version should be used in production for faster page loads. The paths for the minified and unminified files can be set in the configuration file.

#### Concatenate Order

For javascript, the order in which the files are concatenated is very important. The configuration file allows you to create groups in which the files will be categorized based on a regular expression pattern. These groups have an `order` which you can decide on to control the order of the files getting added. If you have a framework, you would want the files that make up the framework to be added before the files that use the framework are.

Here is an example of the `framework` and `app` groups to make sure `app` JS files are below `framework` files.

	'framework' => array(
		// Files get sorted by this before being merged
		'order' => 1,
		// Files belong to this group if the filepath matches the pattern
		'pattern' => '/^media\/js/framework.*/',
	),
	'app' => array(
		// Files get sorted by this before being merged
		'order' => 10, // Will get added after framework files
		// Files belong to this group if the filepath matches the pattern
		'pattern' => '/^media\/js/app.*/',
	),

### SCSS

The compiling of SCSS files is a little more complex than JS files (in some ways, at least). First, all the SCSS files are found and copied to a temporary `compass` project. The configuration for this project can be specified in the module's config file. Then, the compiler simply runs `compass compile`, takes the generated CSS files, and copies them to the configured `save_dir`. What this task does, basically, is add the `compass compile` functionality to a fake project made up of SCSS files across your entire Kohana application. If a module includes `/media/css/scss/forms.scss`, it gets added to all the other SCSS files for compass to compile.

### Coffeescript

The coffeescript compiler is is similar to the SCSS compiler. All of the coffee files are moved to a temporary directory where they are compiled into javascript files. The newly generated javascript files are then copied to the `save_dir`. If you have coffee files in `media/coffee` you can have the generated javascript files created and moved to `media/js`.


### CouchApp

The CouchApp compiler simply takes a set of CouchDB documents, creates a Couch App, and pushes it to the configured database.

### Custom Compilers

Custom compilers can be written easily by adding any needed options to the config file and creating a class that implements the `Media_ICompiler` interface. Compilers simply provide a `compile()` method that takes the array of files, and the array of options from the config. When `media:compile` is run, the module will look for files matching the pattern you configured for the compiler, and call the compiler's `compile()` method. You take it from there.

## Watch

The `media:watch` task is used to constantly poll your application for media changes and trigger `media:compile` automatically any time something changes. Because this is a long running process, it's useful to run with `miniond` instead of `minion` like this:

	./miniond media:watch

This task might have some performance issues because of the complexity of `media:compile` but I am working on improving this soon. Feedback is welcome :)