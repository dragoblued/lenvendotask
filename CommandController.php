<?php
    class CommandController {
        public function showCommand($filename, $command_name, $all_show) {
            $commands = json_decode(file_get_contents($filename));
            foreach ($commands as $key=>$command) {
                if (isset($command)) {
                    foreach ($command as $key=>$items) {
                        if ($all_show) {
                            print("Command: " . $key . "\n");
                            $this->printCommand($command, $key);
                        } else {
                            if ($key == $command_name) {
                                print("Called command: " . $key. "\n");
                                $this->printCommand($command, $key);
                            }
                        }
                    }
                }
            }
        }

        public function printCommand($command, $command_name) {
            print("\n");
            print("     Arguments: \n");
            foreach($command->$command_name as $key=>$items) {
                if ($key == 'arguments') {
                    foreach($items as $name) {
                        print("         - " . $name . "\n");
                    }
                }
            }
            print("     Options: \n");
            foreach($command->$command_name as $key=>$items) {
                if ($key == 'options') {
                    foreach ($items as $key=>$sub_items) {
                        foreach ($sub_items as $key=>$sub_item) {
                            print("        - " . $key . "\n");
                            foreach ($sub_item as $key=>$param) {
                                print("            - " . $param . "\n");
                            }
                        }
                    }
                }
            }
        }

        public function printAddCommand($command) {
            print("Adding command: " . $command->getName() . "\n");
            $arguments = $command->getArguments();
            $options = $command->getOptions();
            print ("    Arguments: \n");
            foreach($arguments as $argument) {
                print("        - " . $argument . "\n");
            }
            print("Options: \n");
            foreach ($options as $key=>$option) {
                print("         - " . $key . "\n");
                foreach ($option as $item) {
                    print("            - " . $item . "\n");
                }
            }

        }

        
        public function writeFile($filename, $data) {
            $handle = @fopen($filename, 'r+');
            if ($handle === null) {
                $handle = fopen($filename, 'w+');
            }
            
            if ($handle) {
                fseek($handle, 0, SEEK_END);
                if (ftell($handle) > 0) {
                    fseek($handle, -1, SEEK_END);
                    fwrite($handle, ',', 1);
                    fwrite($handle, json_encode($data) . ']');
                } else {
                    fwrite($handle, json_encode($data));
                }
                fclose($handle);
            }
        }

        public function saveArguments($argv, $key, $command) {
            $arguments = [];
            if ($argv[0] == '{' && $argv[strlen($argv) - 1] == '}') {
                $argv = mb_substr($argv, 1, strlen($argv) - 2);
                $arguments = explode(',', $argv);
            } else {
                if ($argv[0] != '[' && $key > 1) {
                    $arguments[] = $argv;
                }
            }
            foreach($arguments as $arg) {
                $command->addArguments($arg);
            }
        }

        public function saveOptions($argv, $key, $command) {
            if ($argv[0] == '[' && $argv[strlen($argv) - 1] == ']') {
                $argv = mb_substr($argv,1, strlen($argv) - 2);
                $arguments = explode('=', $argv);
                if(!$command->checkOption($arguments[0])) {
                    $command->addOptions($arguments[0]);
                }
                $command->addOptionValue($arguments[0], $arguments[1]);
            }
        }
    }
?>