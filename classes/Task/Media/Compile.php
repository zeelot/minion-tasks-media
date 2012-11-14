<?php defined('SYSPATH') or die('No direct script access.');

class Task_Media_Compile extends Minion_Task {

	protected $_options = array(
		'pattern' => NULL,
	);

	protected function _execute(array $config)
	{
		$media = Arr::flatten(Kohana::list_files('media'));
		$module_config = Kohana::$config->load('minion-media');
		
		usort($module_config->compilers, array($this, 'sort_compilers'));

		foreach ($module_config->compilers as $key => $info)
		{
			if ( ! is_array($info))
				continue; // This compiler group was disabled in the config

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
	
	public function sort_compilers($a, $b)
	{
		$a_order = Arr::get($a, 'order', PHP_INT_MAX);
		$b_order = Arr::get($b, 'order', PHP_INT_MAX);
	
		return ($a_order < $b_order) ? -1 : 1;
	}
}