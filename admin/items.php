<?php
 ob_start();
 session_start();
 $pageTitle='Items';
 if(isset($_SESSION["Username"])){
     include 'ini.php'; 
     $do= isset($_GET['do']) ? $_GET['do']: 'Manage';
     if($do=='Manage'){

      $stmt=$con->prepare("SELECT itmes.*,categories.Name,users.Username FROM itmes,users,categories where itmes.Member_id=users.ID And itmes.Cat_id=categories.C_id");

      $stmt->execute();

      $rows=$stmt->fetchAll();

?>
          

    <h1 class="text-center">Manage Itmes</h1>
    <div class="container">
    <div class="table-responsive">
      <table class="table">
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Adding Date</th>
            <th scope="col">Description</th>
            <th scope="col">Category</th>
            <th scope="col">Member</th>
            <th scope="col">Control </th>
          </tr>
          <?php
            foreach($rows as $row)
              {
               echo"<tr>";
                    echo"<td>".$row["Item_id"]."</td>";
                    echo"<td>".$row["Item_name"]."</td>";
                    echo"<td>".$row["Price"]."</td>";
                    echo"<td>".$row["Add_date"]."</td>";
                    echo"<td>".$row["Description"]."</td>";
                    echo"<td>".$row["Name"]."</td>";
                    echo"<td>".$row["Username"]."</td>";
                    echo"<td>
                      <a href='items.php?do=Edit&item_id=".$row["Item_id"]."'class='btn btn-outline-success'><i class='fa fa-edit'></i> Edit</a>
                     
                      <a href='items.php?do=Delete&item_id=".$row["Item_id"]."' class='btn btn-outline-danger'><i class='fa fa-close'></i> Delete</a>";
                   
                      if($row["Approve"]==0)
                      echo" <a href='items.php?do=Approve&item_id=".$row["Item_id"]."'class='btn btn-outline-info'><i class='fa fa-check'></i> Approve</a>";
                  
                    echo "</td>";
              echo"</tr>";
              }
          ?>
         

      </table>
    </div>
    <a href="items.php?do=Add" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add new member</a>
    </div>

<?php



     }elseif ($do=='Add'){
        ?>
        <h1 class="text-center">Add Items</h1>
        <div class="container">
            <form class= "form_horizantal" action="?do=Insert" method= "POST"> 
                
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Name<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" class="form-control" id="inputPassword" name="name" placeholder="" autocomplete="off" required="required">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" class="form-control" id="description"placeholder=""  name ="description"autocomplete="off" required="required" />
                      
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="price" class="col-sm-2 col-form-label">Price<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" class="form-control" id="price" placeholder=""  name ="price"required="required" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cntry" class="col-sm-2 col-form-label">Country Made</label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" class="form-control" id="cntry" placeholder=""  name ="cntry" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cntry" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                      <select class="form-select" aria-label="Default select example" name ="status"> 
                              <option selected value="0">Open this select menu</option>
                              <option value="1">New</option>
                              <option value="2">Like New</option>
                              <option value="3">Used</option>
                              <option value="3">Old</option>
                      </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cntry" class="col-sm-2 col-form-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                      <select class="form-select" aria-label="Default select example" name ="mamb"> 
                              <option selected value="0">Select member</option>
                              <?php
                               $stmt=$con->prepare("SELECT * from users") ;
                               $stmt->execute();
                               $users=$stmt->fetchAll();
                               foreach($users as $user){
                                 echo "<option value='".$user["ID"]."'>".$user['Username']."</option>";
                               }
                              ?>
                              
                      </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cntry" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                      <select class="form-select" aria-label="Default select example" name ="cat"> 
                              <option selected value="0">Select category</option>
                              <?php
                               $stmt=$con->prepare("SELECT * from categories") ;
                               $stmt->execute();
                               $categories=$stmt->fetchAll();
                               foreach($categories as $cat){
                                 echo "<option value='".$cat['C_id']."'>".$cat['Name']."</option>";
                               }
                              ?>
                              
                      </select>
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
        echo "<h1 class='text-center'>Insert items</h1>";
        echo "<div class='container'>";
        $name=$_POST['name'];
        $description=$_POST['description'];
        $price=$_POST['price'];
        $country=$_POST['cntry'];
        $status=$_POST['status'];
        $member=$_POST['mamb'];
        $category=$_POST['cat'];
        
        $formerrorrs=array();
        if(empty($name) )
        $formerrorrs[]='<div class ="alert alert-danger">Name can not be empty</div>';
       if(empty($description)){
         $formerrorrs[]='<div class ="alert alert-danger">Description can not be empty</div>';
       }
       if(empty($price)){
        $formerrorrs[]='<div class ="alert alert-danger">Price can not be empty</div>';
       }
       if(empty($country)){
        $formerrorrs[]='<div class ="alert alert-danger">Country made can not be empty</div>';
       }
       if($status==0){
        $formerrorrs[]='<div class ="alert alert-danger">Please choose the status of your item</div>';
       }
       if($category==0){
        $formerrorrs[]='<div class ="alert alert-danger">Please choose the status of your item</div>';
       }
       if($member==0){
        $formerrorrs[]='<div class ="alert alert-danger">Please choose the status of your item</div>';
       }
       if(sizeof($formerrorrs)>0)
         {
           foreach($formerrorrs as $error)
           echo $error;
         }
          else{
            $stmt=$con->prepare("INSERT INTO itmes (Item_name,Description,Price,Add_date,Country_made,Status,Member_id,Cat_id) 
                                  VALUES (:zname ,:zdescription, :zprice,now(),:zcountry,:zstatus,:zmemberid,:zcatid)");
            $stmt->execute(array( ':zname'=>$name,
                                  ':zdescription'=>$description,
                                  ':zprice'=>$price,
                                  ':zcountry'=>$country,
                                  ':zstatus'=>$status,
                                  ':zmemberid'=>$member,
                                  ':zcatid'=>$category,));
            $msg= " <div class='alert alert-success'>".$stmt->rowCount()."  record has been Updated </div>";
            redirectHome($msg);
            }
        }?>

        

     <?php }elseif($do=='Edit'){  

                      $item_id = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']):0;// check if user id is numeric
                      $stmt=$con->prepare("SELECT itmes.*,categories.Name, categories.C_id,users.Username, users.ID FROM itmes ,categories ,users 
                                           WHERE Item_id = ?  
                                           AND itmes.Cat_id=categories.C_id 
                                           AND itmes.Member_id=users.ID  LIMIT 1");
                      $stmt->execute(array($item_id));
                      $item=$stmt->fetch();
                      $count=$stmt->rowCount(); 
                      if( $count>0){?>
                                       <h1 class="text-center">Edit Items</h1>
                                      <div class="container">
                                              <form class= "form_horizantal" action="?do=Update" method= "POST"> 
                                                  <div class="mb-3 row">
                                                  <label for="inputPassword" class="col-sm-2 col-form-label">Name<sup style="color:red; font-size:18px">*</sup></label>
                                                  <div class="col-sm-10 col-md-6">
                                                  <input type="hidden" name="id" value="<?php echo $item['Item_id'];?>"> 
                                                  <input type="text" class="form-control" id="inputPassword" value="<?php echo $item['Item_name'];?>"name="name"  placeholder="" autocomplete="off" required="required">
                                                  </div>
                                                  </div>

                                                  <div class="mb-3 row">
                                                  <label for="description" class="col-sm-2 col-form-label">Description<sup style="color:red; font-size:18px">*</sup></label>
                                                  <div class="col-sm-10 col-md-6">
                                                  <input type="text" class="form-control" id="description" value="<?php echo $item['Description'];?>" placeholder=""  name ="description"autocomplete="off" required="required" />

                                                  </div>
                                                  </div>

                                                  <div class="mb-3 row">
                                                  <label for="price" class="col-sm-2 col-form-label">Price<sup style="color:red; font-size:18px">*</sup></label>
                                                  <div class="col-sm-10 col-md-6">
                                                  <input type="text" class="form-control" id="price"  value="<?php echo $item['Price'];?>"placeholder=""  name ="price"required="required" />
                                                  </div>
                                                  </div>

                                                  <div class="mb-3 row">
                                                  <label for="cntry" class="col-sm-2 col-form-label">Country Made</label>
                                                  <div class="col-sm-10 col-md-6">
                                                  <input type="text" class="form-control" id="cntry" value="<?php echo $item['Country_made'];?>" placeholder=""  name ="cntry" />
                                                  </div>
                                                  </div>

                                                  <div class="mb-3 row">
                                                      <label for="cntry" class="col-sm-2 col-form-label">Status</label>
                                                      <div class="col-sm-10 col-md-6">
                                                        <select class="form-select" aria-label="Default select example" name ="status"> 
                                                          <option selected value="0">Select status</option>
                                                         
                                                          <option  value="1"<?php if($item['Status']==1) echo'selected' ;?>>New</option>
                                                          <option  value="2"<?php if($item['Status']==2) echo'selected' ;?>>Like New</option>
                                                          <option  value="3"<?php if($item['Status']==3) echo'selected' ;?>>Used</option>
                                                          <option  value="4"<?php if($item['Status']==4) echo'selected' ;?>>Old</option>
                                                        </select>
                                                  </div>
                                              </div>
                                                <div class="mb-3 row">
                                                    <label for="cntry" class="col-sm-2 col-form-label">Member</label>
                                                    <div class="col-sm-10 col-md-6">
                                                        <select class="form-select" aria-label="Default select example" name ="mamb"> 
                                                         <?php  echo' <option selected value="'.$item["ID"].'" >'.$item["Username"].'</option> ';?>
                                          

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                  <label for="cntry" class="col-sm-2 col-form-label">Category</label>
                                                  <div class="col-sm-10 col-md-6">
                                                      <select class="form-select" aria-label="Default select example" name ="cat"> 
                                                       <?php echo' <option selected value="'.$item["C_id"].'"  >'.$item["Name"].'</option>';?>
                                                      

                                                      </select>
                                                    </div>
                                                </div>


                                                <div class="mb-3 row">
                                                <div class=" col-sm-10">
                                                <input type="submit"  value="Edit"  class="btn btn-secondary  " >
                                                </div>
                                                </div>
                                            </form>
                                        </div>
 <?php                                     $stmt=$con->prepare("SELECT comments.*  ,users.Username AS USER
                                            FROM comments,users 
                                            where comments.item_id= ? and comments.user_id= users.ID");

                                             $stmt->execute(array($item_id));

                                             $rows=$stmt->fetchAll();

?>

                                <h1 class="text-center">Manage <?php echo $item['Item_name'];?>  Comments</h1>
                                      <div class="container">
                                      <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                             
                                              <th scope="col">Comment</th>
                                              <th scope="col">User name</th>
                                              <th scope="col">Add Date</th> 
                                              <th scope="col">Control </th>
                                            </tr>
                                            <?php
                                              foreach($rows as $row){
                                                

                                                echo"<tr>";
                                                     
                                                      echo"<td>".$row["comment"]."</td>";
                                                    
                                                      echo"<td>".$row["USER"]."</td>";
                                                      echo"<td>".$row["comment_date"]."</td>";
                                                      echo"<td>
                                                        <a href='comments.php?do=Edit&commid=".$row["com_id"]."'class='btn btn-outline-success'><i class='fa fa-edit'></i> Edit</a>
                                                      
                                                        <a href='comments.php?do=Delete&commid=".$row["com_id"]."' class='btn btn-outline-danger'><i class='fa fa-close'></i> Delete</a>";
                                                      if($row["status"]==0){
                                                    echo" <a href='comments.php?do=Approve&commid=".$row["com_id"]."'class='btn btn-outline-info'><i class='fa fa-check'></i> Approve</a>";
                                                    
                                                      echo "</td>";
                                                echo"</tr>";
                                                }}
                                            ?>
                                          

                                        </table>

                      <?php }
             }elseif($do=='Update'){//Update page
              
                             
                if($_SERVER['REQUEST_METHOD']=='POST'){
                  echo "<h1 class='text-center'>Update Itmes</h1>";
                  echo "<div class='container'>";
                
                  $id=$_POST['id'];
                  $name=$_POST['name'];
                  $description=$_POST['description'];
                  $price=$_POST['price'];
                  $contry=$_POST['cntry'];
                  $status=$_POST['status'];
                  $member=$_POST['mamb'];
                  $category=$_POST['cat'];
                  $formerrorrs=array();
                if(empty($name)){
                  $formerrorrs='<div class ="alert alert-danger">Name can not be empty</div>';
                }
                if(empty($price)){
                  $formerrorrs='<div class ="alert alert-danger">Price can not be empty</div>';
                }
                if(empty($description)){
                  $formerrorrs='<div class ="alert alert-danger">Description can not be empty</div>';
                }
                if(sizeof($formerrorrs)>0){
                  foreach($formerrorrs as $error)
                       echo $error;
                }
                else{
                  $stmt=$con->prepare("UPDATE itmes SET Item_name =? , Description=?,Price=? ,Country_made=?,Status=?,Member_id=?,Cat_id=? WHERE Item_id =?");
                  $stmt->execute(array($name,$description,$price,$contry,$status,$member,$category,$id));
                  $msg=" <div class='alert alert-success'>".$stmt->rowCount()."  record has been Updated </div>";
                  redirectHome($msg,'back');
                  }

                }else{
                  $msg=" You are not allowed to view this page";
                  redirectHome($msg);
                }
                echo "</div>";
            }elseif($do=='Delete'){
                    $id = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']):0;// check if user id is numeric
                    //$stmt=$con->prepare("SELECT * FROM users WHERE ID = ?  LIMIT 1");
                    $check=checkitem("Item_id","itmes",$id);
            
                    if($check>0){
                      $stmt=$con->prepare("DELETE FROM itmes WHERE Item_id=?");
                      $stmt->execute(array($id));
                      echo "<h1 class='text-center'>Delete Items</h1>";
                      echo "<div class='container'>";
                      $msg= " <div class='alert alert-success'>This Itme has been deleted Successfuly </div>";
                      redirectHome($msg,'back');
                    }else{
                      $msg ="<div class='alert alert-success'>There Itme is not exist</div>";
                      redirectHome($msg);
                    }
            
                    echo"</div>";
            }elseif($do=='Approve'){


              $id = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']):0;// check if user id is numeric
              //$stmt=$con->prepare("SELECT * FROM users WHERE ID = ?  LIMIT 1");
              $check=checkitem("Item_id","itmes",$id);
      
             // $stmt->execute(array($id));
             // $count=$stmt->rowCount(); 
              if($check>0){
                $stmt=$con->prepare("UPDATE itmes SET  Approve=1 Where Item_id=?");
                $stmt->execute(array($id));
                echo "<h1 class='text-center'>Approve itme</h1>";
                echo "<div class='container'>";
                $msg= " <div class='alert alert-success'>This item has been Approved Successfuly </div>";
                redirectHome($msg,'back');
              }else{
                $msg ="<div class='alert alert-success'>These ID is not exist</div>";
                redirectHome($msg,'back');
              }
      
               echo"</div>";

            }
            include $tep.'footer.php';

        }else {header('Location:index.php');
              exit();
          }
          ob_end_flush();
?>