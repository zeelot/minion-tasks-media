<?php defined('SYSPATH') or die('No direct script access.');

class Media_Compiler_RequireJS extends Media_Compiler implements Media_ICompiler {

	public function compile(array $filepaths, array $options)
	{
		foreach ($filepaths as $relative_path => $absolute_path)
		{
			$destination = $options['tmp_dir'].'/'.$relative_path;
			$this->copy_file($absolute_path, $destination);
		}

		if ( ! isset($options['build.js']['include']))
		{
			$options['build.js']['include'] = array();
		}

		$inits = Kohana::list_files($options['init_dir']);

		foreach ($inits as $init)
		{
			$md5 = md5($init); // To make sure we don't replace inits
			$options['build.js']['include'][] = $md5;
			$this->copy_file($init, $options['tmp_dir'].'/'.$options['build.js']['baseUrl'].$md5.'.js');
		}

		// Create the RequireJS project
		$view = View::factory('minion/tasks/media/requirejs')
			->set('options', $options);
		$this->put_contents($options['tmp_dir'].'/build.js', $view->render());

		// Compile the project! Both for production and development
		passthru('cd '.escapeshellarg($options['tmp_dir']).' && node '.escapeshellarg($options['r.js']).' -o build.js');

		foreach ($options['save_paths'] as $tmp => $final)
		{
			if (file_exists($options['tmp_dir'].'/'.$tmp))
			{
				$this->copy_file($options['tmp_dir'].'/'.$tmp, $final, FALSE);
			}
		}

		$this->delete_directory($options['tmp_dir']);
	}
}