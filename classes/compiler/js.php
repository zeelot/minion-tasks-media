<?php defined('SYSPATH') or die('No direct script access.');

class Compiler_JS extends Media_Compiler{

	public static function compile(array $filepaths, array $options)
	{
		$file_meta = array();

		foreach ($filepaths as $relative_path => $absolute_path)
		{
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

		if (empty($file_meta))
			return TRUE; // Nothing to compile

		// Sort the $file_meta array by order (key) before concatinating
		ksort($file_meta);

		$files = Arr::flatten($file_meta);
		$content = '';

		foreach ($files as $path)
		{
			$content .= file_get_contents($path);
		}

		$unmin_path = $options['save_paths']['unminified'];
		$min_path = $options['save_paths']['minified'];
		// Save the unminified version
		Compiler_JS::put_contents($unmin_path, $content);

		// Not mangling variable names and not removing unused code
		$uglify_cmd = 'uglifyjs '
			.'--no-mangle '
			.'--no-dead-code '
			.'--output '.escapeshellarg($min_path).' '
			.escapeshellarg($unmin_path);

		exec($uglify_cmd);
	}
}