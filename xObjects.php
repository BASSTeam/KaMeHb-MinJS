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
class jBinOp extends functionalObject{
    private static $unaries = [];
    public static function unary($name,$var){
        var_dump(self::unaries[$name]);
        return call_user_func_array("self::unaries[$name]", array($var));
    }
    public static function set_new_unary_operator($name,$callback){
        $reflection = new \ReflectionProperty('jBinOp', 'unaries');
        $reflection->setAccessible(true);
        $new_arr = $reflection->getProperty('unaries');
        $new_arr[$name] = $callback;
        $reflection->setValue(null, $new_arr);
        $reflection->setAccessible(false);
        //self::unaries[$name] = $callback;
    }
}
class jString extends stdClass{
    private $functions = [
            'set(string $str):string'		    => 'Propertly sets the value of extended string object',
            'split(string $delimiter):array'    => 'Splits the string',
            'reverse(void):string'			    => 'Returns reversed string',
            'length(void):int'				    => 'Returns string length',
            'charAt(int $pos):string'		    => 'Returns char at position $pos, or FALSE if position is not in range length()',
            'indexOf(string $str):int'		    => 'Returns position of the first occurrence, or -1 if not found'
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
            while (strpos($value, "\t\t	 ") !== false){
                $value = str_replace("\t\t	", "\t\t\t", $value);
            }
            $value = str_replace("\n}", "\n\t}", $value);
            $value = preg_replace("/=>\r?\n{1,1}\s*/", " => ", $value);
            echo "\t$name -> $key = $value";
        }
        echo "\nNative type: ";
        return [];
    }
    public function set(string $str){
        $this -> str = $str;
        return $this;
    }
    public function split(string $delimiter){
        $tmp_arr = explode($delimiter,$this -> str);
        $ret_arr = [];
        foreach($tmp_arr as $key => $value){
            $ret_arr[$key] = new jString();
            $ret_arr[$key] -> set($value);
        }
        return $ret_arr;
    }
    public static function fromCharCode(){
        $tmpstr = new jString();
        $tmpstr -> set(array_reduce(func_get_args(),function($a,$b){$a.=chr($b);return $a;}));
        return $tmpstr;
    }
    public function reverse(){
        $tmpstr = new jString();
        $tmpstr -> set(strrev($this -> str));
        return $tmpstr;
    }
    public function length(){
        return strlen($this -> str);
    }
    public function charAt(int $a){
        if ($a < strlen($this -> str)){
            return $this -> str[$a];
        } else {
            return false;
        }
    }
    public function indexOf(string $elem){
        $tmp = strpos($this -> str, $elem);
        if ($tmp === false){
            $tmp = -1;
        }
        return $tmp;
    }
}
jBinOp::set_new_unary_operator('~',function($a){
    return (binary) $a;
});
?>
