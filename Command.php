<?php
    class Command {
        private $name;
        private $arguments = [];
        private $options = [];

        public function setName($name) {
            $this->name = $name;
        }
        
        public function getName() {
            return $this->name;
        }

        public function addArguments($val) {
            $this->arguments[] = $val;
        }

        public function getArguments() {
            return $this->arguments;
        }

        public function addOptions($name) {
            $this->options[$name] = [];
        }

        public function checkOption($name) {
            return isset($this->options[$name]);
        }

        public function addOptionValue($option, $value) {
            $this->options[$option][] = $value;
        }

        public function getOptions() {
            return $this->options;
        }

        public function getObject() {
            $options = [];
            foreach($this->options as $key=>$option) {
                $items = [];
                foreach($option as $item) {
                    print_r($item);
                    $items[] = $item;
                }
                $options[] = [
                    $key => $items
                ];
            }
            return [
                $this->name => [
                    'arguments' => $this->arguments,
                    'options' => $options
                ]
            ];
        }
    }
?>