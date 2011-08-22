<?php defined('SYSPATH') or die('No direct script access.');

class Minion_Task_Assets_Watch extends Minion_Task {

	protected $_config = array(
		'lifetime' => '600', // Run for 10 minutes by default
	);

	protected $_last_compiled_time;

	public function execute(array $config)
	{
		$start_time = time();
		$end_time = $start_time + (int) $config['lifetime'];
		$this->compile();

		while (TRUE)
		{
			foreach(Arr::flatten(Kohana::list_files('media')) as $filepath)
			{
				if (filemtime($filepath) > $this->_last_compiled_time)
				{
					Minion_CLI::write('Changes detected: '.$filepath);
					$this->compile();
					break;
				}
			}

			if (time() > $end_time)
			{
				// We exceeded the lifetime for this process
				return;
			}

			usleep(100000);
		}
	}

	public function compile()
	{
		// Execute the command to compile (We assume minion is in DOCROOT for now)
		exec('cd '.escapeshellarg(DOCROOT).' && ./minion assets:compile');
		$this->_last_compiled_time = time();
	}

	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('lifetime', 'not_empty')
			->rule('lifetime', 'digit');
	}
}