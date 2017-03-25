<?php
  class functionalObject extends stdClass{
      public function __call($closure, $args){
          return call_user_func_array($this -> {$closure} -> bindTo($this),$args);
      }
      public function __toString(){
          return call_user_func($this -> {"__toString"} -> bindTo($this));
      }
      public function setProperty(string $prop_name, $prop){
          $this -> $prop_name = $prop;
      }
  }
  class jString extends functionalObject{
      private $functions = [
        'set(string $str):string' => 'Propertly sets the value of extended string object',
        'split(string $delimiter):array' => 'Splits the string',
        'fromCharCode(int $code):string' => 'Returns the char from code',
        ];
      private $str = '';
      public function __toString(){
          return $this -> str;
      }
      public function __debugInfo() {
        $len = strlen($this -> str);
        $str = $this -> str;
        $func_counter = 0;
        $class = get_class ();
        foreach ($this -> functions as $key => $value){
           $func_counter++;
        }
        echo "string($class):($len)#$func_counter \"$str\"\nfunctions:";
        foreach ($this -> functions as $key => $value){
            echo "\n\t$key\n\t\t$value";
        }
        echo "\nNative type: ";
        return [];
      }
      public function set(string $str){
        $this -> str = $str;
        return $this -> str;
      }
      public function split(string $delimiter){
        return explode($delimiter,$this -> str);
      }
      public static function fromCharCode() {
        return array_reduce(func_get_args(),function($a,$b){$a.=chr($b);return $a;});
      }
  }
  ?>
