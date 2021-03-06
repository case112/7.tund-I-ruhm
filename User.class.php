<?php
class User {
	
	// klassi sees saab kasutada
	private $connection;
	
	// $User = new User(see); j�uab siia sulgudesse
	function __construct($mysqli){
		
		// klassi sees muutuja kasutamseks $this->
		// $this viitab sellele klassile
		$this->connection = $mysqli;
		
		
	}
	
	//Teised funktsioonid
	
	function login ($email, $password) {
		
		$error = "";

		$stmt = $this->connection->prepare("
		SELECT id, email, password, created 
		FROM user_sample
		WHERE email = ?");
	
		echo $this->connection->error;
		
		//asendan k�sim�rgi
		$stmt->bind_param("s", $email);
		
		//m��ran v��rtused muutujatesse
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//andmed tulid andmebaasist v�i mitte
		// on t�ene kui on v�hemalt �ks vaste
		if($stmt->fetch()){
			
			//oli sellise meiliga kasutaja
			//password millega kasutaja tahab sisse logida
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				echo "Kasutaja logis sisse ".$id;
				
				//m��ran sessiooni muutujad, millele saan ligi
				// teistelt lehtedelt
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				
				$_SESSION["message"] = "<h1>Tere tulemast!</h1>";
				
				header("Location: data.php");
				exit();
				
			}else {
				$error = "vale parool";
			}
			
			
		} else {
			
			// ei leidnud kasutajat selle meiliga
			$error = "ei ole sellist emaili";
		}
		
		return $error;
		
	}
	
	
	function signUp ($email, $password) {
		

		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		//muidu katkeb �hendus tervele klassile
		//$mysqli->close();
		
	}
	
	
	
}
?>