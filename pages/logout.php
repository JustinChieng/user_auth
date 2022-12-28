<?php 


    session_start();
    
    //make sure the user is logged in
    if ( isset ($_SESSION['user'] ) ){
        //delete the user's session data
        unset ($_SESSION['user']);
        //redirect user back to index
        header('location:/');
        exit;
    }else{
        //redirect to login page if user is not logged in
        header('location:/');
        exit;
    }


?>