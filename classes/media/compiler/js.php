<?php defined('SYSPATH') or die('No direct script access.');

class Media_Compiler_JS extends Media_Compiler implements Media_ICompiler {

	public function compile(array $filepaths, array $options)
	{
		// Sort by filename first (things like foo/bar/01.something.js will sort by 01.something.js)
		uasort($filepaths, array($this, 'sort_by_filename'));

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
			$this->put_contents($unmin_path, '');
			if ($min_path !== FALSE)
			{
				$this->put_contents($min_path, '');
			}

			return TRUE;
		}

		// Sort the $file_meta array by order (key) before concatenating
		ksort($file_meta);

		$files = Arr::flatten($file_meta);
		$content = '';

		foreach ($files as $path)
		{
			$content .= file_get_contents($path);
		}

		// Save the unminified version
		$this->put_contents($unmin_path, $content);

		if ($min_path !== FALSE)
		{
			// Not mangling variable names and not removing unused code
			$uglify_cmd = 'uglifyjs '
				.'--no-mangle '
				.'--no-dead-code '
				.'--output '.escapeshellarg($min_path).' '
				.escapeshellarg($unmin_path);

			exec($uglify_cmd);
		}
	}

	public function sort_by_filename($a, $b)
	{
		return (basename($a) < basename($b)) ? -1 : 1;
	}
}