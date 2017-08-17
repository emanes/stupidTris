<?php

class TrissMainClass {

    protected $db_host = 'localhost';
    protected $db_user = 'root';
    protected $db_password = 'mysql';
    protected $db_name = 'StupidTris';


    public function getNextMove($current_grid) {

        //Variables
        $result = array();
        $position = 0;
        $free_count = 0;

        //Array of grid
        $my_grid = explode(',', $current_grid);

        //Number of moves
        $number_of_move = 0;
        foreach ($my_grid as $value) {
            if ($value != 0) $number_of_move++;
        }
        $number_of_move = 9 - $number_of_move;

        //Generate move
        $move = (rand(1, $number_of_move) - 1);
        
        //Find my move on grid
        while (!count($result)) {
            switch (true) {
                case ($my_grid[$position] == 0 and $free_count == $move):
                    $result[0] = floor($position / 3);
                    $result[1] = ($position % 3);
                    break;
                case ($my_grid[$position] == 0 and $free_count != $move):
                    $free_count++;
                    $position++;
                    break;
                case ($my_grid[$position] != 0):
                    $position++;
                    break;
            }
        }
        
        //Return move
        if (count($result)) echo json_encode($result, true);
        return;
    }
    
    
    public function saveScore($who) {

		//DB connection
		$connection = @mysqli_connect($this->db_host, $this->db_user , $this->db_password);
		if (!$connection) {
			$current_error = [
				"success" => "false",
				"error" => mysqli_connect_error()
			];
			echo json_encode($current_error, true);
			return;
		}
		
		//Building query
		switch ($who) {
			case 'player':
				$insert_query = "INSERT INTO `StupidTris`.`st_score` (`round`, `player`, `ia`) VALUES (NULL, '1', '0');";
				break;
			case 'ia':
				$insert_query = "INSERT INTO `StupidTris`.`st_score` (`round`, `player`, `ia`) VALUES (NULL, '0', '1');";
				break;		
		}
		
		//Executing query
		$insert_result = @mysqli_query($connection, $insert_query);
		if (!$insert_result) {
			$current_error = [
				"success" => "false",
				"error" => mysqli_error($connection)
			];
			echo json_encode($current_error, true);
			@mysqli_close($connection);
			return;
		}
		
		//Results
		echo json_encode(["success" => "true"], true);
		return;
	}
}
