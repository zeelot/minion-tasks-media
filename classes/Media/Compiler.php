<?php defined('SYSPATH') or die('No direct script access.');

abstract class Media_Compiler {

	public function copy_file($source, $destination, $symlink = TRUE)
	{
		$this->make_missing_directories($destination);

		if ($symlink AND Kohana::$config->load('minion-media')->symlinks)
		{
			if (Kohana::$is_windows)
			{
				exec('mklink '.escapeshellarg($destination).' '.escapeshellarg($source));
			}
			else
			{
				exec('ln -sf '.escapeshellarg($source).' '.escapeshellarg($destination));
			}
		}
		else
		{
			copy($source, $destination);
		}
	}

	public function delete_directory($directory)
	{
		if (Kohana::$is_windows)
		{
			exec('rmdir /S /Q '.escapeshellarg($directory));
		}
		else
		{
			exec('rm -R '.escapeshellarg($directory));
		}
	}
	
	public function put_contents($filepath, $contents)
	{
		$this->make_missing_directories($filepath);
		file_put_contents($filepath, $contents);
	}

	public function make_missing_directories($filepath)
	{
		$directory = pathinfo($filepath, PATHINFO_DIRNAME);
		if ( ! is_dir($directory))
		{
			// Make any missing directory
			mkdir($directory, 0777, TRUE);
		}
	}
}