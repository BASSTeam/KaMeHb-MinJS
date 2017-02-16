<?php
class debugConsole{
	private $state = true;
	private $out = "";
	private function addElement($tagname, $text = "", $id = NULL, $name = NULL, $js = NULL, $css = NULL){
		$this -> out += "<$tagname" . ($id !== NULL ? " id=\"$id\"" : "") . ($name !== NULL ? " name=\"$name\"" : "") . ">$text</$tagname>";
	}
	public function turnOn(){ $this -> state = true;}
	public function turnOff(){ $this -> state = false;}
	public function setReverseState(){ $this -> state = !($this -> state);}
	public function setPOST($json){ $_POST = json_decode($json);}
	public function setGET($json){ $_POST = json_decode($json);}
	public function message($message){ $this -> $out += $this -> addElement("div", $message, NULL, "message");}
	public function getArgs(){
		if ($this -> state){
			$this -> addElement("div", json_encode($_POST), NULL, "POST");
			$this -> addElement("div", json_encode($_GET), NULL, "GET");
		}
	}
	public function construct(){
		if ($this -> state){
			$out = $this -> out;
			return "<div id=\"debugConsole\" style=\"\">$out</div>";
		}
	}
}
?> 
