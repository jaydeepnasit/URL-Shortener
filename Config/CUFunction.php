<?php

class CUFunction{

    private $DBHOST = 'localhost';
    private $DBUSER = 'root';
    private $DBNAME = 'urldatabase';
    private $DBPASS = '';
    public $conn;

    public function __construct(){
        try{
            $this->conn = @mysqli_connect($this->DBHOST, $this->DBUSER, $this->DBPASS, $this->DBNAME);
            if(!$this->conn){  
                throw new Exception('Connection Is Not Establish');
            }
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            
        }

    }

    public function validate($string){
        $string_vali = htmlspecialchars(strip_tags(stripslashes($string)));
        $string_vali = mysqli_real_escape_string($this->conn, $string_vali);
        return $string_vali;
    }

    public function insert($tb_name, $tb_field){
       
        $q_data = "";

        foreach($tb_field as $q_key => $q_value){
            $q_data = $q_data."$q_key='$q_value',";
        }
        $q_data = rtrim($q_data,",");

        $query = "INSERT INTO $tb_name SET $q_data";
        $insert_fire = mysqli_query($this->conn, $query);
        if($insert_fire){
            return $insert_fire;
        }
        else{
            return false;
        }

    }

    public function select($tbl_name, $Column, $Order="Asc"){

        $select = "SELECT * FROM $tbl_name ORDER BY $Column $Order";
        $query = mysqli_query($this->conn, $select);
        if(mysqli_num_rows($query) > 0){
            $select_fetch = mysqli_fetch_all($query, MYSQLI_ASSOC);
            if($select_fetch){
                return $select_fetch;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

    }

    public function delete($tbl_name, $condition, $op='AND'){

        $de_data = "";

		foreach ($condition as $q_key => $q_value) {
			$de_data = $de_data."$q_key='$q_value' $op ";
		}
        $de_data = rtrim($de_data,"$op ");

		$delete = "DELETE FROM $tbl_name WHERE $de_data";
		$delete_fire = mysqli_query($this->conn, $delete);
		if($delete_fire){
			return $delete_fire;
		}
		else{
			return false;
        }
        
    }
	public function check_exists($tbl_name, $condition, $op='AND'){

		$field_op = "";
		foreach ($condition as $q_key => $q_value) {
			$field_op = $field_op."$q_key='$q_value' $op ";
		}
		$field_op = rtrim($field_op,"$op ");

        $select_assoc = "SELECT * FROM $tbl_name WHERE $field_op";
		$select_assoc_query = mysqli_query($this->conn, $select_assoc);
		if(mysqli_num_rows($select_assoc_query) > 0){	
            return false;
		}
		else{	
			return true;
		}

    } 
    
    public function select_assoc($tbl_name, $condition){
		$field_op = "";
		foreach ($condition as $q_key => $q_value) {
			$field_op = $field_op."$q_key='$q_value' $op ";
		}
		$field_op = rtrim($field_op,"$op ");

        $select_assoc = "SELECT * FROM $tbl_name WHERE $field_op";
        $select_assoc_query = mysqli_query($this->conn, $select_assoc);
        if(mysqli_num_rows($select_assoc_query) == 1){
            $select_assoc_fire = mysqli_fetch_assoc($select_assoc_query);
            if($select_assoc_fire){
                return $select_assoc_fire;
            }
            else{
                return false;
            }

        }
        else{
            return false;
        }
    }

}




?>