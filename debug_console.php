<?php
class debugConsole{
	private $state = true;
	private $out = '';
	private $style = '';
	private $mysqlStyleAdded = false;
	private $mysqli;
	private function json($json){ (json_encode($json) != '[]' ? json_encode($json) : 'NUA' . 'LL');}
	private function addElement($obj){
		$tagname = ($obj -> tagname ? $obj -> tagname : 'div');
		$inner_text = ($obj -> inner_text ? $obj -> inner_text : '');
		$name = ($obj -> name ? ' name="' . $name . '"' : '');
		$params = '';
		foreach($obj as $key => $value) {
			if ($key != 'tagname' and $key != 'inner_text' and $key != 'tagtype'){
				$params .= ($value != '' and $value !== false and $value !== NULL ? " $key=\"$value\"" : " $key");
			}
		}
		$this -> out = $this -> out . "<$tagname$params" . ($obj -> tagtype == 1 ? " value=\"$inner_text\">" : ($obj -> tagtype == 2 ? " value=\"$inner_text\"></$tagname>" : ">$inner_text</$tagname>"));
	}
	public function turnOn(){ $this -> state = true;}
	public function turnOff(){ $this -> state = false;}
	public function setReverseState(){ $this -> state = !($this -> state);}
	public function setPOST($json){ $_POST = json_decode($json);}
	public function setGET($json){ $_GET = json_decode($json);}
	public function message($message){ $this -> addElement(json_decode('{"inner_text":"' . $message . '","class":"message"}'));}
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
			$this -> addElement(json_decode('{"inner_text":"' . $this -> json($_POST) . '","name":"POST","class":"message"}'));
			$this -> addStyle('#debugConsole [name="POST"]', 'content:"POST: "','before');
			$this -> addElement(json_decode('{"inner_text":"' . $this -> json($_GET) . '","name":"GET","class":"message"}'));
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
