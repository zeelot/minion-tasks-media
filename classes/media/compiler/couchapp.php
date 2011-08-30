<?php defined('SYSPATH') or die('No direct script access.');

class Media_Compiler_CouchApp extends Media_Compiler implements Media_ICompiler {

	public function compile(array $filepaths, array $options)
	{
		foreach ($filepaths as $relative_path => $absolute_path)
		{
			// Remove `media/couchapp` from the relative path
			$couch_path = substr($relative_path, 15);
			$destination = $options['tmp_dir'].'/'.$couch_path;
			$this->copy_file($absolute_path, $destination);
		}

		// Copy the .couchapprc file manually
		$rc_path = Kohana::find_file('media/couchapp', '', 'couchapprc');
		$this->copy_file($rc_path, $options['tmp_dir'].'/.couchapprc');

		// Push the documents to CouchDB
		$config = Kohana::$config->load('couchdb');
		exec('cd '.escapeshellarg($options['tmp_dir']).' && couchapp push');

		// Remove the tmp directory
		exec('rm -R '.escapeshellarg($options['tmp_dir']));
	}
}