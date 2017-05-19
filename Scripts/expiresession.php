<?php
/**Checks for idle or absolute session expiration**/

    if($_SESSION['logged_in']=true){
        if( $_SESSION['last_activity'] < time() -$_SESSION['expire_time']) 
        {
            session_destroy(); 
        } 
        else{ 
            $_SESSION['last_activity'] = time();
        }
        if(isset($_SESSION['expire']))
        {
            if(time() > $_SESSION['expire'])
            {
                session_destroy();
            } 
        }
    }
?>