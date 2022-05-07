<?php
 ob_start();
 session_start();
 $pageTitle='Comments';
 if(isset($_SESSION["Username"])){
     include 'ini.php'; 
     $do= isset($_GET['do']) ? $_GET['do']: 'Manage';
     if($do=='Manage'){

        $stmt=$con->prepare("SELECT comments.*, itmes.Item_id AS Item_name ,users.Username AS USER
                               FROM comments,itmes,users 
                               where comments.item_id=itmes.Item_id and user_id=ID;");

        $stmt->execute();

        $rows=$stmt->fetchAll();

?>

<h1 class="text-center">Manage Comments</h1>
      <div class="container">
      <div class="table-responsive">
        <table class="table">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Comment</th>
              <th scope="col">Item name</th>
              <th scope="col">User name</th>
              <th scope="col">Add Date</th> 
              <th scope="col">Control </th>
            </tr>
            <?php
              foreach($rows as $row)
                {
                 echo"<tr>";
                        echo"<td>".$row["com_id"]."</td>";
                      echo"<td>".$row["comment"]."</td>";
                      echo"<td>".$row["Item_name"]."</td>";
                      echo"<td>".$row["USER"]."</td>";
                      echo"<td>".$row["comment_date"]."</td>";
                      echo"<td>
                        <a href='comments.php?do=Edit&commid=".$row["com_id"]."'class='btn btn-outline-success'><i class='fa fa-edit'></i> Edit</a>
                       
                        <a href='comments.php?do=Delete&commid=".$row["com_id"]."' class='btn btn-outline-danger'><i class='fa fa-close'></i> Delete</a>";
                      if($row["status"]==0){
                     echo" <a href='comments.php?do=Approve&commid=".$row["com_id"]."'class='btn btn-outline-info'><i class='fa fa-check'></i> Approve</a>";
                    }
                      echo "</td>";
                echo"</tr>";
                }
            ?>
           

        </table>

<?php
     }elseif ($do=='Edit'){
        $id = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):0;
        $stmt=$con->prepare("SELECT * FROM comments WHERE com_id = ?  LIMIT 1");
        $stmt->execute(array($id));
        $row=$stmt->fetch();
        $count=$stmt->rowCount(); 
        if( $count>0){
            ?>  
                   
                   <h1 class="text-center">Edit Comments</h1>
                   <div class="container">
                       <form class= "form_horizantal" action="?do=Update" method= "POST"> 
                            <input type="hidden" name="id" value="<?php echo $id;?>" />
                           <div class="mb-3 row">
                               <label for="inputPassword" class="col-sm-2 col-form-label">Comment<sup style="color:red; font-size:18px">*</sup></label>
                               <div class="col-sm-10 col-md-6">
                                <textarea class="form-control" name="comment"><?php echo row["comment"];?></textarea>
                               </div>
                           </div>
            
                         

                           <div class="mb-3 row">
                               <div class=" col-sm-10">
                                 <input type="submit"  value="Save"  class="btn btn-secondary bt-lg " >
                               </div>
                           </div>
                       </form>
                       
                   </div>
                   
           
           <?php
                    }else {
                      $msg= "<div class ='alert alert-danger'>there is no such id</div>";
                      redirectHome($msg);
                    }

    
      

      }elseif($do=='Update'){//Update page
        if($_SERVER['REQUEST_METHOD']=='POST'){
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
           
            $id=$_POST['id'];
            $comm=$_POST['comment'];

          
           
            $stmt=$con->prepare("UPDATE comments SET comment =?  WHERE com_id =?");
            $stmt->execute(array($comm,$id));
            $msg=" <div class='alert alert-success'>".$stmt->rowCount()."  record has been Updated </div>";
            redirectHome($msg,'back');
            
  
          }else{
            $msg='<div class ="alert alert-danger">You are not allowed to view this page</div>';
            redirectHome($msg);
          }
          echo "</div>";
        
      }elseif($do=='Delete'){
        
        $id = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):0;

        $check=checkitem("com_id","comments",$id);

       // $stmt->execute(array($id));
       // $count=$stmt->rowCount(); 
        if($check>0){
          $stmt=$con->prepare("DELETE FROM users WHERE ID=?");
          $stmt->execute(array($id));
          echo "<h1 class='text-center'>Delete Comment</h1>";
          echo "<div class='container'>";
          $msg= " <div class='alert alert-success'>This Comment has been deleted Successfuly </div>";
          redirectHome($msg,'back');
        }else{
          $msg ="<div class='alert alert-success'>There ID is not exist</div>";
          redirectHome($msg);
        }

         echo"</div>";
      }elseif($do=='Approve'){

      }
      include $tep.'footer.php';

  }else {header('Location:index.php');
        exit();
    }
    ob_end_flush();
    ?>