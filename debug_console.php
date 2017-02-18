<?php
class debugConsole{
	private $state = true;
	private $out = '';
	private $style = '';
	private $mysqlStyleAdded = false;
	private $mysqli;
	private function json($json){ return (json_encode($json) != '[]' ? json_encode($json) : 'NUA' . 'LL');}
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
					$params = $params . $this -> normalize_str(" $key=\"$value\"");
				} else {
					$params = $params . $this -> normalize_str(" $key");
				}
			}
		}
		echo json_encode($obj) . "\n";
		echo "{\"tagname\":\"$tagname\",\"params\":\"$params\",\"inner_text\":\"$inner_text\"}\n";
		if (isset($obj -> tagtype) and $obj -> tagtype == 1){
			$this -> out = $this -> out . "<$tagname$params value=\"$inner_text\">";
			echo "<$tagname$params value=\"$inner_text\">";
		} elseif (isset($obj -> tagtype) and $obj -> tagtype == 2){
			$this -> out = $this -> out . "<$tagname$params value=\"$inner_text\"></$tagname>";
			echo "<$tagname$params value=\"$inner_text\"></$tagname>";
		} else {
			$this -> out = $this -> out . "<$tagname$params>$inner_text</$tagname>";
			echo "<$tagname$params>$inner_text</$tagname>";
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
			$this -> addStyle('#debugConsole', 'position:absolute;bottom:0;background:#293134;color:#67A9B1;left:0;width:100%;font-family:Ubuntu;font-size:14px;font-style:normal;font-variant:normal;font-weight:400;line-height:18px;');
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
			echo "<div id=\"debugConsole\">$out</div>";
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
