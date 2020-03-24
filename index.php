<?php

include_once 'Config/CUFunction.php';
$CUF_OBJ = New CUFunction();

$Domain = $_SERVER['HTTP_HOST'];

if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $Request_Query = $_SERVER['REQUEST_URI'];
    $Request_Query = $CUF_OBJ->validate(trim($Request_Query));
    $Request_Query = str_replace("/",'',$Request_Query);

    if((strlen($Request_Query) == 7) && is_string($Request_Query)){

        $condition['s_new_link'] = $Domain.'/'.$Request_Query;
        $Find_Fetch = $CUF_OBJ->select_assoc('shorturl', $condition);

        if($Find_Fetch){

            $Get_Link = $Find_Fetch['s_original_link'];

            header("Location: $Get_Link");
            exit();

        }
        else{
            header("Location: Error.php");
            exit();
        }

    }
    else{

        if($Request_Query == 'index.php' || strlen($Request_Query) >= 1){
            header("Location: http://urlshortner.com/"); 
            exit();
        }

    }

}
else{
    header("Location: Error.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortner Service</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="Assets/CSS/Style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="container">
            <div class="center-screen">
                <div class="col-lg-8">
                    <div class="main-box">
                        <div class="URL-Head">
                            <span class="head-style">URL Shorter</span>
                        </div>
                        <form id="S_Form" method="POST" autocomplete="off">
                            <div class="form-group row">
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="A_URL" id="A_URL" max="800" placeholder="Enter The Link To Here">
                            </div>
                            <div class="col-lg-3">
                                <input type="submit" id="S_BTN" class="btn btn-outline-danger w-100" value="Shorten URL">
                            </div>
                            </div>
                        </form>
                        <div class="status">&#128071; Status &#128071;</div>
                        <div class="URL-Footer">
                            <span id="Status"></span>
                            <div class="spinner-border" role="status" id="Loader">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <button class="btn btn-lg btn-outline-danger mt-4" data-toggle="modal" data-target=".bd-example-modal-xl">VIEW ALL URL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Modal -->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ALL LINKS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="View_Data">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript">
    $(document).ready(function (){
        
        $('.View_Data').load('view_links.php');

        $('#Loader').hide();
           
        $(document).on('submit', '#S_Form', function(e){
            e.preventDefault();

            $AURL = $('#A_URL').val().trim();

            if($AURL.length <= 800 && $AURL.length != 0 && $AURL.match(/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&\'\(\)\*\+,;=.]+$/)){
                $.ajax({
                    type: "POST",
                    url: "AJAX/URL-Insert.php",
                    data: { 'ENCODED_AURL' : encodeURIComponent($AURL) },
                    beforeSend: function() {
                        $('#Loader').show();
                        $('#S_BTN').prop('disabled', true).addClass('disable-icon');
                    },
                    complete: function() {
                        $('#Loader').hide();
                        $('#S_BTN').prop('disabled', false).removeClass('disable-icon');
                    },
                    success: function (response) {
                        $Res_Status = JSON.parse(response);
                        if($Res_Status.status == 800){
                            $('#S_Form').trigger('reset');
                            $('#Status').text('');
                            $("#Status").append("&#9989;   "+$Res_Status.msg+"   &#9989;").delay(0).fadeIn( "slow", function (){
                                $('#S_BTN').prop('disabled', true).addClass('disable-icon');
                                $('.View_Data').load('view_links.php');
                                $(this).delay(5000).fadeOut("slow", function(){
                                    $('#S_BTN').prop('disabled', false).removeClass('disable-icon');
                                    location.reload();
                                });
                            });
                        }else{
                            $('#Status').text('');
                            $('#Status').append("&#128545;"+$Res_Status.msg+"&#128545;");
                        }
                    }
                });
            }
            else{
                if($AURL.length == 0){
                    $('#Status').text('');
                    $('#Status').append("&#128545; Please Enter URL &#128545;");
                }
                else{
                    $('#Status').text('');
                    $('#Status').append("&#128545; Please Enter Valid URL &#128545;");
                }
            }

        }); 

        $(document).on('click', '#Delete', function(){
            
            $Delete_No = $(this).data('deleteno');

            if($.isNumeric($Delete_No)){
                $.ajax({
                    type: "POST",
                    url: "AJAX/Delete.php",
                    data: { 'Delete_No' : encodeURIComponent($Delete_No) },
                    success: function (response) {
                        $Res_Del_Status = JSON.parse(response);
                        if($Res_Del_Status.status == 700){
                            $('.View_Data').load('view_links.php');
                            console.log($Res_Del_Status.msg);
                        }
                        else{
                            console.log($Res_Del_Status.msg);
                        }
                    }
                });
            }
            else{
                console.log("Invalid Data Not Allow");
            }

        });

    });
    </script>
</body>
</html>