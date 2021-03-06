<?php

class Cars {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
		
	}
	
	
	function getAllCars($q) {
		
		//kas otsib
		if ($q != "") {
			
			echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
			SELECT id, plate, color
			FROM cars_and_colors
			WHERE deleted is NULL
			AND (plate LIKE ? OR color LIKE ?)
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("ss", $searchWord, $searchWord);
			
		}else {
			$stmt = $this->connection->prepare("
			SELECT id, plate, color
			FROM cars_and_colors
			WHERE deleted is NULL
		");
			
		}
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $plate, $color);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$car = new StdClass();
			
			$car->id = $id;
			$car->plate = $plate;
			$car->carColor = $color;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr m�rgi
			array_push($result, $car);
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $result;
	}
	
	function saveCar ($plate, $color) {
		
		
		$stmt = $this->connection->prepare("INSERT INTO cars_and_colors (plate, color) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $plate, $color);
		
		if($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
	
	function getSingleCarData($edit_id){
    
		
		$stmt = $this->connection->prepare("SELECT plate, color FROM cars_and_colors WHERE id=? AND deleted is NULL");
		

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($plate, $color);
		$stmt->execute();
		
		//tekitan objekti
		$car = new Stdclass();
		
		//saime �he rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$car->plate = $plate;
			$car->color = $color;
			
			
		}else{
			// ei saanud rida andmeid k�tte
			// sellist id'd ei ole olemas
			// see rida v�ib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $car;
		
	}

	function updateCar($id, $plate, $color){
    	
		$stmt = $this->connection->prepare("UPDATE cars_and_colors SET plate=?, color=? WHERE id=? AND deleted is NULL");
		$stmt->bind_param("ssi",$plate, $color, $id);
		
		// kas �nnestus salvestada
		if($stmt->execute()){
			// �nnestus
			echo "salvestus �nnestus!";
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
	
	function deleteCar($id, $plate, $color){
		
		$stmt = $this->connection->prepare("UPDATE cars_and_colors SET deleted=NOW() WHERE id=? AND deleted is NULL");
		$stmt->bind_param("i",$id);
		
		// kas �nnestus salvestada
		if($stmt->execute()){
			// �nnestus
			echo "salvestus �nnestus!";
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
	
	
}	
	
?>