<?php defined('SYSPATH') or die('No direct script access.');

abstract class Media_Compiler {

	public static function copy_file($source, $destination, $symlink = TRUE)
	{
		Media_Compiler::make_missing_directories($destination);

		if ($symlink)
		{
			exec('ln -sf '.escapeshellarg($source).' '.escapeshellarg($destination));
		}
		else
		{
			copy($source, $destination);
		}
	}

	public static function put_contents($filepath, $contents)
	{
		Media_Compiler::make_missing_directories($filepath);
		file_put_contents($filepath, $contents);
	}

	public static function make_missing_directories($filepath)
	{
		$directory = pathinfo($filepath, PATHINFO_DIRNAME);
		if ( ! is_dir($directory))
		{
			// Make any missing directory
			mkdir($directory, 0777, TRUE);
		}
	}
}