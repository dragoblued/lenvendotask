<?php
	require_once 'Command.php';
	require_once 'CommandController.php';

	$filename = 'app.json';
	$argv;
	$command = new Command();
	$commandFunctions = new CommandController();
	$flag = true;
	if (isset($argv[1])) {
		$command->setName($argv[1]);
		foreach ($argv as $arg) {	
			if ($arg[0] == '{' && $arg[strlen($arg) - 1] == '}') {
				$item = mb_substr($arg, 1, strlen($arg) - 2);
				if ($item == 'help') {
					$commandFunctions->showCommand($filename, $argv[1], false);
					$flag = false;
				}
			}
		}
		if($flag == true) {
			foreach ($argv as $key=>$arg) {
				$commandFunctions->saveArguments($arg, $key, $command);
				$commandFunctions->saveOptions($arg, $key, $command);
			}
			$commandFunctions->writeFile('app.json', $command->getObject());
			$commandFunctions->printAddCommand($command, $argv[1]);
		}
	} else {
		print("All commands\n");
		$commandFunctions->showCommand($filename, '', true);
	}

?>
