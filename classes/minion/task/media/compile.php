<?php defined('SYSPATH') or die('No direct script access.');

class Minion_Task_Media_Compile extends Minion_Task {

	protected $_config = array(
		'pattern' => NULL,
	);

	public function execute(array $config)
	{
		$media = Arr::flatten(Kohana::list_files('media'));
		$module_config = Kohana::$config->load('minion-media');

		foreach ($module_config->compilers as $key => $info)
		{
			$files = array();

			// If --pattern was specified, only worry about matching compilers
			if ($config['pattern'] !== NULL)
			{
				if ( ! preg_match($info['pattern'], $config['pattern']))
					continue; // Move on to the next compiler
			}

			foreach ($media as $relative => $filepath)
			{
				// Check if the path matches the pattern for the compiler
				if (preg_match($info['pattern'], $relative))
				{
					Minion_CLI::write('('.$key.') Matched '.$relative);
					$files[$relative] = $filepath;
				}
			}

			if ( ! empty($files))
			{
				// Compile these files
				$class_name = $info['class'];
				$compiler = new $class_name;
				$compiler->compile($files, Arr::get($info, 'options', array()));
			}
		}
	}
}