<?php
  class getObjectPublicVars extends stdClass{
    public function get($obj){
        return get_object_vars($obj);
    }
  }
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
  class jString extends stdClass{
      private $functions = [
        'set(string $str):string'           => 'Propertly sets the value of extended string object',
        'split(string $delimiter):array'    => 'Splits the string',
        'reverse(void):string'              => 'Returns reversed string'
        ];
      private $static_methods = [
        'fromCharCode(int $code1, ...):string' => 'Returns the char(-s) from code(-s)',
      ];
      private function public_vars(){
        $tmpobj = new getObjectPublicVars();
        return $tmpobj -> get($this);
      }
      private $str = '';
      public function __toString(){
          return $this -> str;
      }
      public function __debugInfo() {
        foreach($GLOBALS as $var_name => $var_value) {
            if ($var_value === $this) {
                $name = '$' . $var_name;
            }
        }
        $len = strlen($this -> str);
        $str = $this -> str;
        $func_counter = 0;
        $class = get_class ();
        foreach ($this -> functions as $key => $value){
           $func_counter++;
        }
        echo "$name: string($class):($len)#$func_counter \"$str\"\nFunctions:";
        foreach ($this -> functions as $key => $value){
            echo "\n\t$name -> $key\n\t\t$value";
        }
        echo "\n\nStatic methods:";
        foreach ($this -> static_methods as $key => $value){
            echo "\n\t$class::$key\n\t\t$value";
        }
        echo "\n\nPublic vars:\n";
        foreach ($this -> public_vars() as $key => $value){
            ob_start();
            var_dump($value);
            $value = ob_get_clean();
            $value = str_replace("\n  ", "\n\t\t", $value);
            $value = str_replace("\n}", "\n\t}", $value);
            $value = preg_replace("[\=]{1}[>]{1}[\r]?[\n]{1}[\s]*", " => ", $value);
            echo "\t$name -> $key = $value";
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
      public static function fromCharCode(){
        return array_reduce(func_get_args(),function($a,$b){$a.=chr($b);return $a;});
      }
      public static function reverse(){
        return strrev($this -> str);
      }
  }
?>
