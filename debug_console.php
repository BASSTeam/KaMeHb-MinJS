<?php
/*
Used CSS ICON -- project by Wenting Zhang => http://cssicon.space
To use this script you may require it directly from GitHub:
require('https://raw.githubusercontent.com/BASSTeam/KaMeHb-MinJS/master/debug_console.php');
or copy current version's code to the head of your script.

To know what commands are currently supported, you may type 'help' command in console.
NB!	All commands you are using in console are ONE-TIME LAUNCHABLE; directly in PHP script - PERMANENT
*/
class debugConsole{
	private $state = true;
	private $out = '';
	private $style = '';
	private $mysqlStyleAdded = false;
	private $mysqli;
	private $currentConsoleColor = '#67A9B1';
	private $currentConsoleBackground = '#293134';
	private function json($json){ return (json_encode($json) != '[]' ? json_encode($json) : 'NULL');}
	private function normalize_str($str){ return str_replace('"', '\\"', str_replace('\\', '\\\\', $str));}
	private function addElement($obj){
		if (isset($obj -> tagname) and $obj -> tagname !== false){
			$tagname = $obj -> tagname;
		} else {
			$tagname = 'div';
		}
		if (isset($obj -> inner_text) and $obj -> inner_text !== false){
			$inner_text = $obj -> inner_text;
		} else {
			$inner_text = '';
		}
		$params = '';
		foreach($obj as $key => $value) {
			if ($key != 'tagname' and $key != 'inner_text' and $key != 'tagtype'){
				if ($value != '' and $value !== false and $value !== NULL){
					$params = $params . " $key=\"$value\"";
				} else {
					$params = $params . " $key";
				}
			}
		}
		if (isset($obj -> tagtype) and $obj -> tagtype == 1){
			$this -> out = $this -> out . "<$tagname$params value=\"$inner_text\">";
		} elseif (isset($obj -> tagtype) and $obj -> tagtype == 2){
			$this -> out = $this -> out . "<$tagname$params value=\"$inner_text\"></$tagname>";
		} else {
			$this -> out = $this -> out . "<$tagname$params>$inner_text</$tagname>";
		}
	}
	public function turnOn(){ $this -> state = true;}
	public function turnOff(){ $this -> state = false;}
	public function setReverseState(){ $this -> state = !($this -> state);}
	public function setPOST($json){ $_POST = json_decode($json);}
	public function setGET($json){ $_GET = json_decode($json);}
	public function message($message){ $this -> addElement(json_decode('{"inner_text":"' . $this -> normalize_str($message) . '","class":"message"}'));}
	public function addStyle($selector, $style, $pseudo = NULL){
		if ($this -> state){
			$this -> style = $this -> style . $selector . ($pseudo !== NULL ? ":$pseudo" : '') . '{' . $style . '}' . PHP_EOL;
		}
	}
	public function getHeader(){
		if ($this -> state){
			$currentConsoleColor = $this -> currentConsoleColor;
			$currentConsoleBackground = $this -> currentConsoleBackground;
			$this -> addStyle('#debugConsoleBlockSelfCSSIcon', "position:absolute;margin-left:2px;margin-top:2px;width:15px;height:15px;border-radius:1px;border:solid 1px $currentConsoleColor;");
			$this -> addStyle('#debugConsoleBlockSelfCSSIcon', "content:'';position:absolute;left:3px;top:-2px;width:9px;height:19px;color:$currentConsoleBackground;background-color:$currentConsoleColor;-webkit-transform-origin:center;transform-origin:center;",'before');
			$this -> addStyle('#debugConsoleBlockSelfCSSIcon', "content:'';position:absolute;left:3px;top:-2px;width:9px;height:19px;color:$currentConsoleBackground;background-color:$currentConsoleColor;-webkit-transform-origin:center;transform-origin:center;-webkit-transform:rotate(90deg);transform:rotate(90deg);",'after');
			$this -> addStyle('#debugConsole', "position:absolute;bottom:0;background:$currentConsoleBackground;color:$currentConsoleColor;left:0;width:100%;font-family:Ubuntu;font-size:14px;font-style:normal;font-variant:normal;font-weight:400;line-height:18px;");
			return '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu"/>';
		}
	}
	public function getStyleSheet(){
		if ($this -> state){
			$style = $this -> style;
			return "<style>$style</style>";
		}
	}
	public function getArgs(){
		if ($this -> state){
			$this -> addElement(json_decode('{"inner_text":"' . $this -> normalize_str($this -> json($_POST)) . '","name":"POST","class":"message"}'));
			$this -> addStyle('#debugConsole [name="POST"]', 'content:"POST: "','before');
			$this -> addElement(json_decode('{"inner_text":"' . $this -> normalize_str($this -> json($_GET)) . '","name":"GET","class":"message"}'));
			$this -> addStyle('#debugConsole [name="GET"]', 'content:"GET: "','before');
		}
	}
	public function construct(){
		if ($this -> state){
			$out = $this -> out;
			echo "<div id=\"debugConsole\"><div><div id=\"debugConsoleBlockSelfCSSIcon\"></div></div>$out</div>";
		}
	}
	private function addMySQLStyle(){
		if (!($this -> mysqlStyleAdded)){
			addStyle('#debugConsole [name="mysql"]', 'content:"MySQL out: "','before');
			$this -> mysqlStyleAdded = true;
		}
	}
	public function mysql_connect($host, $user, $pass, $db){
		$this -> mysqli = new mysqli($host, $user, $pass, $db);
		if ($this -> state){
			$this -> addMySQLStyle();
			if ($this -> mysqli->connect_errno){
				$this -> addElement(json_decode('{"inner_text":"' . "connecting to $db from $user at $host..." . '","name":"mysql","class":"error_message"}'));
				$this -> addElement(json_decode('{"inner_text":"' . 'error at ' . $this -> mysqli -> connect_errno . ': ' . $this -> mysqli -> connect_error . '","name":"mysql","class":"error_message"}'));
			} else {
				$this -> addElement(json_decode('{"inner_text":"' . "connecting to $db from $user at $host... Success." . '","name":"mysql","class":"message"}'));
			}
		} else {
			return $this -> mysqli;
		}
	}
	public function mysql_query($sql){
		if (!$result = $this -> mysqli -> query($sql)){
			if ($this -> state){
				$this -> addMySQLStyle();
				// ADD ERROR ON QUERY LOG
			} else {
				return false;
			}
		} else {
			if ($this -> state){
				$this -> addMySQLStyle();
				// ADD NORMAL QUERY LOG
			} else {
				return $result;
			}
		}
	}
}
?>
