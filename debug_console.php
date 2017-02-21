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
	private $currentConsoleWarningBackground = '#424017';
	private $currentConsoleErrorBackground = '#512828';
	private $currentConsoleWarningColor = '#FFFC00';
	private $currentConsoleErrorColor = '#FF0000';
	private $codename = 'tester';
	private function json($json){ return (json_encode($json) != '[]' ? json_encode($json) : 'NULL');}
	private function normalize_str($str){ return str_replace('"', '\\"', str_replace('\\', '\\\\', $str));}
	private function _addElement($obj){
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
		$cur_class_prefix = 'std_line';
		foreach($obj as $key => $value) {
			if ($key != 'tagname' and $key != 'inner_text' and $key != 'tagtype'){
				if ($value != '' and $value !== false and $value !== NULL){
					if ($key == 'class'){ $cur_class_prefix = $value; }
					$params = $params . " $key=\"$value\"";
				} else {
					$params = $params . " $key";
				}
			}
		}
		if (isset($obj -> tagtype) and $obj -> tagtype == 1){
			return '<div class="icon_wrapper"><div class="' . $cur_class_prefix . "_icon\"></div></div><$tagname$params value=\"$inner_text\">";
		} elseif (isset($obj -> tagtype) and $obj -> tagtype == 2){
			return '<div class="icon_wrapper"><div class="' . $cur_class_prefix . "_icon\"></div></div><$tagname$params value=\"$inner_text\"></$tagname>";
		} else {
			return '<div class="icon_wrapper"><div class="' . $cur_class_prefix . "_icon\"></div></div><$tagname$params>$inner_text</$tagname>";
		}
	}
	private function addElement($obj){
		$this -> out = $this -> out . $this -> _addElement($obj);
	}
	private function addElementToTop($obj){
		$this -> out = $this -> _addElement($obj) . $this -> out;
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
	private function getHeader(){
		if ($this -> state){
			$currentConsoleColor = $this -> currentConsoleColor;
			$currentConsoleBackground = $this -> currentConsoleBackground;
			$currentConsoleWarningBackground = $this -> currentConsoleWarningBackground;
			$currentConsoleErrorBackground = $this -> currentConsoleErrorBackground;
			$currentConsoleWarningColor = $this -> currentConsoleWarningColor;
			$currentConsoleErrorColor = $this -> currentConsoleErrorColor;
			$this -> addStyle('#debugConsoleBlockSelfCSSIcon', "position:absolute;margin-left:2px;margin-top:-16px;width:15px;height:15px;border-radius:1px;border:solid 1px $currentConsoleColor;right:3px;");
			$this -> addStyle('#debugConsoleBlockSelfCSSIcon', "content:'';position:absolute;left:3px;top:-2px;width:9px;height:19px;color:$currentConsoleBackground;background-color:$currentConsoleBackground;-webkit-transform-origin:center;transform-origin:center;",'before');
			$this -> addStyle('#debugConsoleBlockSelfCSSIcon', "content:'';position:absolute;left:3px;top:-2px;width:9px;height:19px;color:$currentConsoleBackground;background-color:$currentConsoleBackground;-webkit-transform-origin:center;transform-origin:center;-webkit-transform:rotate(90deg);transform:rotate(90deg);",'after');
			$this -> addStyle('#KaMeHb_debugConsole', "position:absolute;bottom:0;color:$currentConsoleColor;left:0;width:100%;font-family:Ubuntu;font-size:14px;font-style:normal;font-variant:normal;font-weight:400;line-height:18px;");
			$this -> addStyle('#KaMeHb_debugConsole #mainDebugConsoleOutput', "max-height:200px;overflow:auto;overflow-wrap:break-word;");
			$this -> addStyle('#KaMeHb_debugConsole #mainDebugConsoleOutput,#KaMeHb_debugConsole .console-button', "background-color:$currentConsoleBackground;");
			$this -> addStyle('#KaMeHb_debugConsole .warning_message', "background-color:$currentConsoleWarningBackground;");
			$this -> addStyle('#KaMeHb_debugConsole .error_message', "background-color:$currentConsoleErrorBackground;");
			$this -> addStyle('#KaMeHb_debugConsole .error_message,#KaMeHb_debugConsole .warning_message', "padding-left:20px;");
			$this -> addStyle('#KaMeHb_debugConsole .console-button', "cursor:pointer;position:absolute;right:0;width:75px;height:20px;margin-top:-20px;border-top-left-radius:5px;padding-left:3px");
			$this -> addStyle('#KaMeHb_debugConsole .console-button', "content:'console';", 'before');
			$this -> addStyle('#KaMeHb_debugConsole .icon_wrapper', 'position:relative;');
			$this -> addStyle('#KaMeHb_debugConsole .warning_message_icon', "color:$currentConsoleWarningColor;position:absolute;margin-left:2px;margin-top:2px;width:12px;height:12px;background-color:$currentConsoleWarningColor;border:1px solid $currentConsoleWarningColor;border-radius:8px;");
			$this -> addStyle('#KaMeHb_debugConsole .warning_message_icon', 'top:1px;height:7px;', 'before');
			$this -> addStyle('#KaMeHb_debugConsole .warning_message_icon', 'top:9px;height:2px;', 'after');
			$this -> addStyle('#KaMeHb_debugConsole .warning_message_icon:before,#KaMeHb_debugConsole .warning_message_icon:after', "left:5px;width:2px;background-color:$currentConsoleWarningBackground;content:'';position:absolute;display:block;");
			return '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu"/>';
		}
	}
	private function getStyleSheet(){
		if ($this -> state){
			$style = $this -> style;
			return "<style>$style</style>";
		}
	}
	private function getArgs(){
		if ($this -> state){
			$this -> addStyle('#KaMeHb_debugConsole [name="POST"]', 'content:"POST: "','before');
			$this -> addStyle('#KaMeHb_debugConsole [name="GET"]', 'content:"GET: "','before');
			$currentGetStyle = ($this -> normalize_str($this -> json($_GET)) == 'NULL' ? 'warning_message' : 'message');
			$currentPostStyle = ($this -> normalize_str($this -> json($_POST)) == 'NULL' ? 'warning_message' : 'message');
			$this -> addElementToTop(json_decode('{"inner_text":"' . $this -> normalize_str($this -> json($_GET)) . '","name":"GET","class":"' . $currentGetStyle . '"}'));
			$this -> addElementToTop(json_decode('{"inner_text":"' . $this -> normalize_str($this -> json($_POST)) . '","name":"POST","class":"' . $currentPostStyle . '"}'));
		}
	}
	public function construct(){
		if ($this -> state){
			$currentConsoleColor = $this -> currentConsoleColor;
			$currentConsoleBackground = $this -> currentConsoleBackground;
			$out = $this -> out;
			$codename = $this -> codename;
			$server = $_SERVER['SERVER_NAME'];
			echo "<div id=\"KaMeHb_debugConsole\"><div class=\"console-button\" onclick=\"var elem=this.parentNode.querySelector('#mainDebugConsoleOutput');if (elem.style.display=='none'){elem.style.display='block';}else{elem.style.display='none';}\"><div id=\"debugConsoleBlockSelfCSSIcon\"></div></div><div id=\"mainDebugConsoleOutput\">";
			echo $out;
			echo "<table style=\"width:100%;color:inherit;font:inherit;font-size:inherit;border-collapse:collapse;border:0;\"><tbody><tr><td style=\"width:1px;border:0;\">$codename@$server:$</td><td style=\"overflow:hidden;border:0;\"><div style=\"height: 18px;\">";
			echo "<form method=\"POST\" style=\"width:100%;\" onsubmit=\"
			var c_value = this.getElementsByName('command_for_debug_console')[0].value;
			var expression = /(/i;
			var pos = c_value.search(expression);
			if (pos != -1){
				this.getElementsByName('command_type_for_debug_console')[0].value = 'function';
				c_value = string.substr(0, pos);
				alert(pos);
			}
			submit(this);
			\"><input name=\"command_for_debug_console\" value=\"\" style=\"width:100%;color:inherit;background-color:inherit;border:0;\"><input name=\"command_args_for_debug_console\" value=\"\" type=\"hidden\"><input name=\"command_type_for_debug_console\" value=\"value\" type=\"hidden\"><input value=\"OK\" style=\"display:none;\" type=\"submit\"></form></div></td></tr></tbody></table></div></div>";
		}
	}
	private function addMySQLStyle(){
		if (!($this -> mysqlStyleAdded)){
			addStyle('#KaMeHb_debugConsole [name="mysql"]', 'content:"MySQL out: "','before');
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
				$errno = $this -> mysqli -> errno;
				$error = $this -> mysqli -> error;
				$this -> addElement(json_decode('{"inner_text":"' . "error $errno on query ''$sql'': $error" . '.","name":"mysql","class":"error_message"}'));
			} else {
				return false;
			}
		} else {
			if ($this -> state){
				$this -> addMySQLStyle();
				$info = $this -> mysqli -> info;
				$rows = $this -> mysqli -> affected_rows;
				$this -> addElement(json_decode('{"inner_text":"' . "''$sql'': $info Affected rows: $rows" . '","name":"mysql","class":"message"}'));
			} else {
				return $result;
			}
		}
	}
	public function constructHead($buildArgs = false){
		if ($buildArgs){ $this -> getArgs(); }
		echo $this -> getHeader();
		echo $this -> getStyleSheet();
	}
	public function __construct(){
		$command_args = json_decode('{"arguments":[]}') -> arguments;
		$command_type = 'value';
		if (isset($_POST['command_args_for_debug_console'])){
			$command_args = json_decode($_POST['command_args_for_debug_console']) -> arguments;
			unset($_POST['command_args_for_debug_console']);
		}
		if (isset($_POST['command_type_for_debug_console'])){
			$command_type = $_POST['command_type_for_debug_console'];
			unset($_POST['command_type_for_debug_console']);
		}
		if (isset($_POST['command_for_debug_console'])){
			$command = $_POST['command_for_debug_console'];
			unset($_POST['command_for_debug_console']);
			if ($command_type == 'function'){
				$this -> $command($command_args);
			} else {
				$this -> $command;
			}
		}
	}
}
?>
