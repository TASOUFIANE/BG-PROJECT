<?php
 ob_start();
 session_start();
 $pageTitle='';
 if(isset($_SESSION["Username"])){
     include 'ini.php'; 
     $do= isset($_GET['do']) ? $_GET['do']: 'Manage';
     if($do=='Manage'){

     }elseif ($do=='Add'){


     }elseif($do=='Insert'){
      

      }elseif($do=='Update'){//Update page
        
        
      }elseif($do=='Delete'){
        
       
      }elseif($do=='Approve'){

      }
      include $tep.'footer.php';

  }else {header('Location:index.php');
        exit();
    }
    ob_end_flush();
    ?>
     $sort='ASC';
        //$sort_array=array('ASC','DESC');
        //if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){
         // $sort=$_GET['sort'];
        //}
        $item_id = isset($_GET['ca_id']) && is_numeric($_GET['ca_id']) ? intval($_GET['ca_id']):0;// check if user id is numeric
$stmt=$con->prepare("SELECT * FROM categories WHERE C_id = ?  LIMIT 1");
$stmt->execute(array($ca_id));
$cat=$stmt->fetch();
$count=$stmt->rowCount(); 
if( $count>0){


?>

<