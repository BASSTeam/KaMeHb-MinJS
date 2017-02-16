<?php
class debugConsole{
	private $state = true;
	private $out = "";
	private function addElement($tagname, $text = "", $id = NULL, $name = NULL, $js = NULL, $css = NULL){
		$this -> out = $this -> out . "<$tagname" . ($id !== NULL ? " id=\"$id\"" : "") . ($name !== NULL ? " name=\"$name\"" : "") . ">$text</$tagname>";
	}
	public function turnOn(){ $this -> state = true;}
	public function turnOff(){ $this -> state = false;}
	public function setReverseState(){ $this -> state = !($this -> state);}
	public function setPOST($json){ $_POST = json_decode($json);}
	public function setGET($json){ $_POST = json_decode($json);}
	public function message($message){ $this -> addElement("div", $message, NULL, "message");}
	public function getHeader(){
		if ($this -> state){
			return "<link rel=\"stylesheet\" type=\"text/css\" href=\"//fonts.googleapis.com/css?family=Ubuntu\" />";
		}
	}
	public function getStyleSheet(){
		if ($this -> state){
			return "<style>#debugConsole{position:absolute;bottom:0;background:#293134;color:#E0E2E4;left:0;width:100%;}</style>";
		}
	}
	public function getArgs(){
		if ($this -> state){
			$this -> addElement("div", json_encode($_POST), NULL, "POST");
			$this -> addElement("div", json_encode($_GET), NULL, "GET");
		}
	}
	public function construct(){
		if ($this -> state){
			$out = $this -> out;
			echo "<div id=\"debugConsole\">$out</div>";
		}
	}
}
?> 
