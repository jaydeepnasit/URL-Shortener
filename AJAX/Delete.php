<?php

include_once '../Config/CUFunction.php';
$CUF_OBJ = New CUFunction();

$Json_Data = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['Delete_No']) && is_numeric($_POST['Delete_No'])){
        
        $Delete_No = $CUF_OBJ->validate($_POST['Delete_No']);

        try{
            $condition['s_unique_no'] = $Delete_No;
            $DELETE = $CUF_OBJ->delete('shorturl', $condition);
            if($DELETE == false){
                throw New Exception("Error Occurred! Try Again.");
            }
            else{
                $Json_Data['status'] = 700;
                $Json_Data['msg'] = "Link is Deleted";
            }
        }
        catch(Exception $e){
            $Json_Data['status'] = 701;
            $Json_Data['msg'] = $e->getMessage();
        }

    }
    else{
        $Json_Data['status'] = 702;
        $Json_Data['msg'] = "Invalid Data Not Allow";
    }

}
else{
    $Json_Data['status'] = 703;
    $Json_Data['msg'] = "Invalid Request Not Allow";
}

echo json_encode($Json_Data);

?>