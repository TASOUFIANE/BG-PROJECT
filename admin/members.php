<?php
 session_start();
 $pageTitle='Members';
 if(isset($_SESSION["Username"])){
     include 'ini.php'; 
     $do= isset($_GET['do']) ? $_GET['do']: 'Manage';
     if($do=='Manage'){
        $query='';
        if(isset($_GET['page']) && $_GET['page']=='Pending')
         $query='AND RegStatus = 0';
       //SELECT ALL USERS EXCEPECT ADMIN
        $stmt=$con->prepare("SELECT * FROM users WHERE GroupID !=1  $query");

        $stmt->execute();

        $rows=$stmt->fetchAll();

 ?>
            

      <h1 class="text-center">Manage Members</h1>
      <div class="container">
      <div class="table-responsive">
        <table class="table">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Username</th>
              <th scope="col">FullName</th>
              <th scope="col">Email</th>
              <th scope="col">Registerd Date</th>
              <th scope="col">Control </th>
            </tr>
            <?php
              foreach($rows as $row)
                {
                 echo"<tr>";
                        echo"<td>".$row["ID"]."</td>";
                      echo"<td>".$row["Username"]."</td>";
                      echo"<td>".$row["Email"]."</td>";
                      echo"<td>".$row["FullName"]."</td>";
                      echo"<td>".$row["RegDate"]."</td>";
                      echo"<td>
                        <a href='members.php?do=Edit&ID=".$row["ID"]."'class='btn btn-outline-success'><i class='fa fa-edit'></i> Edit</a>
                       
                        <a href='members.php?do=Delete&ID=".$row["ID"]."' class='btn btn-outline-danger'><i class='fa fa-close'></i> Delete</a>";
                      if($row["RegStatus"]==0){
                     echo" <a href='members.php?do=Approve&ID=".$row["ID"]."'class='btn btn-outline-info'><i class='fa fa-check'></i> Approve</a>";
                    }
                      echo "</td>";
                echo"</tr>";
                }
            ?>
           

        </table>
      </div>
      <a href="members.php?do=Add" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add new member</a>
      </div>

<?php }elseif ($do=='Add'){?>

      <h1 class="text-center">Add Member</h1>
      <div class="container">
          <form class= "form_horizantal" action="?do=Insert" method= "POST"> 
              
              <div class="mb-3 row">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Username<sup style="color:red; font-size:18px">*</sup></label>
                  <div class="col-sm-10 col-md-6">
                    <input type="text" class="form-control" id="inputPassword" name="user" placeholder="" autocomplete="off" required="required">
                  </div>
              </div>
              <div class="mb-3 row">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Password<sup style="color:red; font-size:18px">*</sup></label>
                  <div class="col-sm-10 col-md-6">
                    <input type="password" class="form-control" id="inputPassword"placeholder=""  name ="pass"autocomplete="off" required="required"/>
                    <!--<i class="showpass fa fa-eye fa-2x"></i>-->
                  </div>
              </div>
              <div class="mb-3 row">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Full Name<sup style="color:red; font-size:18px">*</sup></label>
                  <div class="col-sm-10 col-md-6">
                    <input type="text" class="form-control" id="inputPassword" placeholder=""  name ="full"  required="required"/>
                  </div>
              </div>
              <div class="mb-3 row">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Email<sup style="color:red; font-size:18px">*</sup></label>
                  <div class="col-sm-10 col-md-6">
                    <input type="email" class="form-control" id="inputPassword" placeholder="" name="email"  required="required"/>
                  </div>
              </div>
              <div class="mb-3 row">
                  <div class=" col-sm-10">
                    <input type="submit"  value="Add"  class="btn btn-secondary  " >
                  </div>
              </div>
  
  

          </form>
          
      </div>

<?php
     }elseif($do=='Insert'){
      if($_SERVER['REQUEST_METHOD']=='POST'){
        echo "<h1 class='text-center'>Insert Member</h1>";
          echo "<div class='container'>";
          $user=$_POST['user'];
          $pass=sha1($_POST['pass']);
          $email=$_POST['email'];
          $full=$_POST['full'];
          $pass=sha1($_POST['pass']);
          $formerrorrs=array();
          /*$stmt=$con->prepare("SELECT Username FROM users Where Username=? ");
          $stmt->execute(array($user));
          if(($stmt->rowCount())>0)
          $formerrorrs[]='<div class ="alert alert-danger">This Username has been chosen please chose another one </div>';*/
         if(strlen($user)<4 || strlen($user)>16 )
          $formerrorrs[]='<div class ="alert alert-danger">Username can not be less than 4 characters and the maximum is 16 characters</div>';
         if(empty($user)){
           $formerrorrs[]='<div class ="alert alert-danger">Username can not be empty</div>';
         }
         if(empty($full)){
          $formerrorrs[]='<div class ="alert alert-danger">Full name can not be empty</div>';
         }
         if(empty($email)){
          $formerrorrs[]='<div class ="alert alert-danger">Email can not be empty</div>';
         }
        
         if(sizeof($formerrorrs) > 0){
            foreach($formerrorrs as $error){
              echo $error;
            }
          }
         else{
           $check=checkitem("Username","users",$user);
           if($check == 1){
            echo "<div class ='alert alert-danger'>This Username has been chosen please chose another one </div>";
            redirectHome($msg,'back');}
            else{
              $stmt=$con->prepare("INSERT INTO users (Username,Password,Email,FullName,RegStatus,RegDate) VALUES (:zuser ,:zpass, :zemail,:zfull,1,now())");
              $stmt->execute(array(':zuser'=>$user,':zpass'=>$pass,':zemail'=>$email,':zfull'=>$full));
              $msg= " <div class='alert alert-success'>".$stmt->rowCount()."  record has been Updated </div>";
              redirectHome($msg,'back');
              }
          }
      }else{
        $msg=" <div class='alert alert-danger'>You are not allowed to view this page</div>";
        redirectHome($msg);
      }
      echo "</div>";

    }elseif($do=='Edit'){ //Edit page 
       
         $id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']):0;// check if user id is numeric
         $stmt=$con->prepare("SELECT * FROM users WHERE ID = ?  LIMIT 1");
         $stmt->execute(array($id));
         $row=$stmt->fetch();
         $count=$stmt->rowCount(); 
         if( $count>0){
 ?>  
        
        <h1 class="text-center">Edit Member</h1>
        <div class="container">
            <form class= "form_horizantal" action="?do=Update" method= "POST"> 
                 <input type="hidden" name="id" value="<?php echo $id;?>" />
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Username<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" class="form-control" id="inputPassword" name="user" value ="<?php echo $row['Username'];?>" autocomplete="off" required="required">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Password<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="hidden" name ="oldpass" value="<?php echo $row['Password'];?>"/>
                      <input type="password" class="form-control" id="inputPassword" name ="newpass"autocomplete="off" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Full Name<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" class="form-control" id="inputPassword"  name ="full" value ="<?php echo $row['FullName'];?>" required="required"/>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Email<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="email" class="form-control" id="inputPassword"name="email" value ="<?php echo $row['Email'];?>" required="required"/>
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
          $user=$_POST['user'];
          $email=$_POST['email'];
          $full=$_POST['full'];
          $pass=empty($_POST['newpass'])?$_POST['oldpass']:sha1($_POST['newpass']);
          $formerrorrs=array();
          
         if(strlen($user)<4 || strlen($user)>8 )
          $formerrorrs[]='<div class ="alert alert-danger">Username can not be less than 4 characters and the maximum is 8 characters</div>';
         if(empty($user)){
           $formerrorrs[]='<div class ="alert alert-danger">Username can not be empty</div>';
         }
         if(empty($full)){
          $formerrorrs[]='<div class ="alert alert-danger">Full name can not be empty</div>';
         }
         if(empty($email)){
          $formerrorrs[]='<div class ="alert alert-danger">Email can not be empty</div>';
         }
         if(sizeof($formerrorrs) > 0){
            foreach($formerrorrs as $error){
              echo $error;
            }
          }
         else{
          $stmt=$con->prepare("UPDATE users SET Username =? , Email=?,FullName=? ,Password=? WHERE ID =?");
          $stmt->execute(array($user,$email,$full,$pass,$id));
          $msg=" <div class='alert alert-success'>".$stmt->rowCount()."  record has been Updated </div>";
          redirectHome($msg,'back');
          }

        }else{
          $msg='<div class ="alert alert-danger">You are not allowed to view this page</div>';
          redirectHome($msg);
        }
        echo "</div>";
      }elseif($do=='Delete'){
        $id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']):0;// check if user id is numeric
        //$stmt=$con->prepare("SELECT * FROM users WHERE ID = ?  LIMIT 1");
        $check=checkitem("ID","users",$id);

       // $stmt->execute(array($id));
       // $count=$stmt->rowCount(); 
        if($check>0){
          $stmt=$con->prepare("DELETE FROM users WHERE ID=?");
          $stmt->execute(array($id));
          echo "<h1 class='text-center'>Delete Member</h1>";
          echo "<div class='container'>";
          $msg= " <div class='alert alert-success'>This Member has been deleted Successfuly </div>";
          redirectHome($msg,'back');
        }else{
          $msg ="<div class='alert alert-success'>There ID is not exist</div>";
          redirectHome($msg);
        }

         echo"</div>";
      }elseif($do=='Approve'){
        $id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']):0;// check if user id is numeric
        //$stmt=$con->prepare("SELECT * FROM users WHERE ID = ?  LIMIT 1");
        $check=checkitem("ID","users",$id);

       // $stmt->execute(array($id));
       // $count=$stmt->rowCount(); 
        if($check>0){
          $stmt=$con->prepare("UPDATE users SET  Regstatus=1 Where ID=?");
          $stmt->execute(array($id));
          echo "<h1 class='text-center'>Approve Member</h1>";
          echo "<div class='container'>";
          $msg= " <div class='alert alert-success'>This Member has been Approved Successfuly </div>";
          redirectHome($msg,'back');
        }else{
          $msg ="<div class='alert alert-success'>There ID is not exist</div>";
          redirectHome($msg,'back');
        }

         echo"</div>";

        
      }
 include $tep.'footer.php';

 }else {header('Location:index.php');
        exit();
    }