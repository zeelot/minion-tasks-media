<?php defined('SYSPATH') or die('No direct script access.');

class Minion_Task_Assets_Watch extends Minion_Task {

	protected $_last_compiled_time;

	public function execute(array $config)
	{
		$this->compile();

		while (TRUE)
		{
			foreach(Arr::flatten(Kohana::list_files('media')) as $filepath)
			{
				if (filemtime($filepath) > $this->_last_compiled_time)
				{
					$this->compile();
					break;
				}
			}

			usleep(100000);
		}
	}

	public function compile()
	{
		Minion_CLI::write('Changes detected, compiling:');
		// Execute the command to compile (We assume minion is in DOCROOT for now)
		exec('cd '.escapeshellarg(DOCROOT).' && ./minion assets:compile');
		Minion_CLI::write('Done compiling.');
		$this->_last_compiled_time = time();
	}
}