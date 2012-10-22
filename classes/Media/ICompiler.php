<?php defined('SYSPATH') or die('No direct script access.');

interface Media_ICompiler {

	public function compile(array $files, array $options);

}