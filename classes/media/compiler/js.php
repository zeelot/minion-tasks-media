<?php defined('SYSPATH') or die('No direct script access.');

class Media_Compiler_JS extends Media_Compiler{

	public static function compile(array $filepaths, array $options)
	{
		$file_meta = array();

		foreach ($filepaths as $relative_path => $absolute_path)
		{
			// Exclude the save paths
			if (in_array($absolute_path, $options['save_paths']))
				continue;

			foreach ($options['concat'] as $group => $properties)
			{
				// If the relative path matches the pattern
				if (preg_match($properties['pattern'], $relative_path))
				{
					// This file belongs to this group
					$file_meta[$properties['order']][$relative_path] = $absolute_path;
					break; // No need to check the other groups
				}
			}
		}

		$unmin_path = $options['save_paths']['unminified'];
		$min_path = $options['save_paths']['minified'];

		if (empty($file_meta))
		{
			// Our files are empty because there is nothing to compile
			Media_Compiler_JS::put_contents($unmin_path, '');
			Media_Compiler_JS::put_contents($min_path, '');

			return TRUE;
		}

		// Sort the $file_meta array by order (key) before concatinating
		ksort($file_meta);

		$files = Arr::flatten($file_meta);
		$content = '';

		foreach ($files as $path)
		{
			$content .= file_get_contents($path);
		}

		// Save the unminified version
		Media_Compiler_JS::put_contents($unmin_path, $content);

		// Not mangling variable names and not removing unused code
		$uglify_cmd = 'uglifyjs '
			.'--no-mangle '
			.'--no-dead-code '
			.'--output '.escapeshellarg($min_path).' '
			.escapeshellarg($unmin_path);

		exec($uglify_cmd);
	}
}