<?php
class debugConsole{
	private $state = true;
	private $out = true;
	private function addElement($tagname, $text, $id, $name, $js, $css){
		$out += "<$tagname" . ($id !== NULL ? " id=\"$id\"" : "") . ($name !== NULL ? " name=\"$name\"" : "") . ">$text</$tagname>";
	}
	public function setOn(){ $state = true;}
	public function setOff(){ $state = false;}
	public function setReverseState(){ $state = !$state;}
	public function setPOST($json){ $_POST = json_decode($json) -> post;}
	public function setGET($json){ $_POST = json_decode($json) -> get;}
	public function message($message){ $out += addElement("div", $message, NULL, "message");}
	public function getArgs(){
		if ($state){
			addElement("div", json_encode($_POST), NULL, "POST");
			addElement("div", json_encode($_GET), NULL, "GET");
		}
	}
	public function construct(){
		if ($state){
			return "<div id=\"debugConsole\" style=\"\">$out</div>"
		}
	}
}
?> 
