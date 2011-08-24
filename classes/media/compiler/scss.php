<?php defined('SYSPATH') or die('No direct script access.');

class Media_Compiler_SCSS extends Media_Compiler implements Media_ICompiler {

	public function compile(array $filepaths, array $options)
	{
		foreach ($filepaths as $relative_path => $absolute_path)
		{
			$destination = $options['tmp_dir'].'/'.$relative_path;
			$this->copy_file($absolute_path, $destination);
		}

		// Create the compass project
		$config_dir = $options['tmp_dir'].'/config';
		if ( ! is_dir($config_dir))
		{
			mkdir($config_dir, 0777, TRUE);
		}

		$view = View::factory('minion/tasks/media/compass')
			->set('options', $options);
		$this->put_contents($config_dir.'/compass.rb', $view->render());

		// Compile the project! Both for production and development
		exec('cd '.escapeshellarg($options['tmp_dir']).' && compass compile');

		// Copy the compiled files to it's final home :)
		$css_dir = $options['tmp_dir'].'/'.$options['css_dir'];

		$compiled_files = Kohana::list_files($options['css_dir'], array($options['tmp_dir'].'/'));

		foreach ($compiled_files as $relative_path => $absolute_path)
		{
			$destination = $options['save_dir'].'/'.$relative_path;
			$this->copy_file($absolute_path, $destination, FALSE);
		}

		// Remove the tmp directory
		exec('rm -R '.escapeshellarg($options['tmp_dir']));
	}
}