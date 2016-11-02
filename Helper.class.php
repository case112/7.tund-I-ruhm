<?php

class Helper {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
		
	}
	
	function cleanInput($input){
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
	
	
	
}	
	
?>