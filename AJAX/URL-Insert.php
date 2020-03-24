<?php

include_once '../Config/CUFunction.php';
$CUF_OBJ = New CUFunction();

$Domain_Name = $_SERVER['HTTP_HOST'];

$Json_Data = [];

function getName() { 
    $n = 7;
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
} 

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['ENCODED_AURL']) && !empty(trim($_POST['ENCODED_AURL']))){

        $Decode_AURL = urldecode($_POST['ENCODED_AURL']);
        $AURL = $CUF_OBJ->validate($Decode_AURL);

        $AURL_LENGTH = strlen($AURL);

        if(preg_match("/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&\'\(\)\*\+,;=.]+$/", $AURL) && ($AURL_LENGTH <= 800)){
            
            $New_Link = $Domain_Name.'/'.getName();

            $Condition['s_original_link'] = $AURL;
            $Check_Exists = $CUF_OBJ->check_exists('shorturl', $Condition);

            if($Check_Exists == true){

                $ins_field['s_new_link'] = $New_Link;
                $ins_field['s_original_link'] = $AURL;
                $ins_field['s_created_at'] = date('Y-m-d H:i:s e');
                $ins_field['s_unique_no'] = rand(10000000, 99999999);
    
                try{
                    $INSERT = $CUF_OBJ->insert('shorturl', $ins_field);
                    if($INSERT == false){
                        throw New Exception("Error Occurred! Try Again.");
                    }else{
                        $Json_Data['status'] = 800;
                        $Json_Data['msg'] = "$New_Link";
                    }
                }
                catch(Exception $e){
                    $Json_Data['status'] = 801;
                    $Json_Data['msg'] = $e->getMessage();
                }

            }
            else{
                $Json_Data['status'] = 802;
                $Json_Data['msg'] = "Link Already Exists";
            }

        }
        else{
            $Json_Data['status'] = 803;
            $Json_Data['msg'] = "Invalid Url Not Allow";
        }

    }
    else{
        $Json_Data['status'] = 804;
        $Json_Data['msg'] = "Invalid Data Not Allow";
    }

}
else{
    $Json_Data['status'] = 805;
    $Json_Data['msg'] = "Invalid Request Not Allow";
}

echo json_encode($Json_Data);

?>