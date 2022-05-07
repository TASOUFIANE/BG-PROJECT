<?php
 ob_start();
 session_start();
 $pageTitle='Categories';
 if(isset($_SESSION["Username"])){
     include 'ini.php'; 
     $do= isset($_GET['do']) ? $_GET['do']: 'Manage';
     if($do=='Manage'){
        $sort='ASC';
        $sort_array=array('ASC','DESC');
        if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){
          $sort=$_GET['sort'];
        }
        $stmt=$con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
        $stmt->execute();
        $cats=$stmt->fetchAll();?>
                    <h1 class="text-center">Manage Categories</h1>
                    <div class="container">
                      <div class="card" style="width: 50rem;">  
                        <?php
                           foreach($cats as $cat){  
                             
                            echo' <div class="card-header">';
                            echo'<strong>';
                            echo $cat['Name'];
                            
                            echo '</strong>';
                            echo '<div class="ordering pull-right">';
                            
                            echo'<a href="?sort=ASC "style= "text-decoration:none;"><i class="fa fa-arrow-up"></i></a> |';
                            echo' <a href="?sort=DESC" style= "text-decoration:none;"><i class="fa fa-arrow-down"></i></a>';
                            echo '</div>';
                            echo'</div> ';                 
                            echo' <ul class="list-group list-group-flush">';                       
                                echo'<li class="list-group-item">';                            
                                if($cat['Description']=='') {
                                  echo 'Description : This category has no description';
                                }else echo 'Description :'.$cat['Description'];
                                echo '</li>';
                                echo'<li class="list-group-item">';                         
                                  if($cat['Visibility']=='1') {
                                    echo 'Visibilty of Category : <i class="fa fa-eye"></i> Hidden';
                                  }else echo 'Visibilty of Category : <i class="fa fa-eye-slash"></i> not Hidden';
                                echo '</li>';
                                echo'<li class="list-group-item">';                           
                                  if($cat['Allow_comments']=='1') {
                                    echo 'Comments Status : <i class="fa fa-ban"></i> not allowed';
                                  }else echo 'Comments Status : <i class="fa fa-check"></i> allowed';
                                echo '</li>';
                                echo'<li class="list-group-item">';                           
                                  if($cat['Allow_ads']=='1') {
                                    echo 'Ads Status : <i class="fa fa-ban"></i>not allowed';
                                  }else echo 'Ads Status :<i class="fa fa-check"></i> allowed';
                                echo '</li>';
                                echo'<li class="list-group-item">';                            
                                echo '<a href="categories.php?do=Edit&ca_id='. $cat["C_id"].'">';
                                echo '<span class="btn btn-outline-success "><i class="fa fa-edit"></i> Edit</span></a> ';
                                echo '<a href="categories.php?do=Delete&ca_id='.$cat["C_id"].'"><span class="btn btn-outline-danger "><i class="fa fa-close"></i> Delete </span></a>';
                                echo '</li>';
                            echo '</ul>';}?>
                                
                                <div style="margin:10px 17px;">
                                <a href="categories.php?do=Add" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add new member</a>
                                </div>

                        </div>
                    </div>
 <?php }elseif ($do=='Add'){
        ?>
        <h1 class="text-center">Add Category</h1>
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
                      <input type="text" class="form-control" id="description"placeholder=""  name ="description"autocomplete="off" />
                      
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="ordering" class="col-sm-2 col-form-label">Ordering</label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" class="form-control" id="ordering" placeholder=""  name ="ordering" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Visible<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="radio" id="yes"    name="visible" value="0"  checked/>
                      <label for="yes">Yes</label><br>
                      <input type="radio" id="no"   name="visible" value="1"  />
                      <label for="no">No</label>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Allow Comments<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="radio" id="yes"   name="allow" value="0"  checked/>
                      <label for="yes">Yes</label><br>
                      <input type="radio" id="no"    name="allow" value="1"  />
                      <label for="no">No</label>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Allow ADS<sup style="color:red; font-size:18px">*</sup></label>
                    <div class="col-sm-10 col-md-6">
                      <input type="radio" id="yes"    name="ads" value="0"  checked/>
                      <label for="yes">Yes</label><br>
                      <input type="radio" id="no"    name="ads" value="1"  />
                      <label for="no">No</label>
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

     }elseif($do=='Insert'){ if($_SERVER['REQUEST_METHOD']=='POST'){
        echo "<h1 class='text-center'>Insert Category</h1>";
          echo "<div class='container'>";
          $name=$_POST['name'];
          $description=$_POST['description'];
          $ordering=$_POST['ordering'];
          $visible=$_POST['visible'];
          $comment=$_POST['allow'];
          $ads=$_POST['ads'];
         
         if(empty($name)){
         '<div class ="alert alert-danger">Name can not be empty</div>';
         }
         else{
           $check=checkitem("Name","categories",$name);
           if($check == 1){
            $msg= "<div class ='alert alert-danger'>This Name has been chosen please chose another one </div>";
            redirectHome($msg,'back');}
            else{
              $stmt=$con->prepare("INSERT INTO categories (Name,Description,Ordering,Visibility,Allow_comments,Allow_ads) VALUES (:zname ,:zdescription, :zordering,:zvisibl,:zcomments,:zads)");
              $stmt->execute(array(':zname'=>$name,':zdescription'=>$description,':zordering'=>$ordering,':zvisibl'=>$visible,':zcomments'=>$comment,':zads'=>$ads));
              $msg= " <div class='alert alert-success'>".$stmt->rowCount()."  record has been Updated </div>";
              redirectHome($msg,'back');
              }
          }
      }else{
        $msg=" <div class='alert alert-danger'>You are not allowed to view this page</div>";
        redirectHome($msg);
      }
      echo "</div>";
      

      }elseif($do=='Edit'){ $ca_id = isset($_GET['ca_id']) && is_numeric($_GET['ca_id']) ? intval($_GET['ca_id']):0;// check if user id is numeric
                            $stmt=$con->prepare("SELECT * FROM categories WHERE C_id = ?  LIMIT 1");
                            $stmt->execute(array($ca_id));
                            $cat=$stmt->fetch();
                            $count=$stmt->rowCount(); 
                            if( $count>0){
                    ?>  
                    
                    <h1 class="text-center">Edit Category</h1>
                    <div class="container">
                        <form class= "form_horizantal" action="?do=Update" method= "POST"> 
                              <input type="hidden" name="id" value="<?php echo $id;?>" />
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Name<sup style="color:red; font-size:18px">*</sup></label>
                                <div class="col-sm-10 col-md-6">
                                  <input type="hidden" name="id" value="<?php echo $cat['C_id'];?>" />
                                  <input type="text" class="form-control" id="inputPassword" name="name" value ="<?php echo $cat['Name'];?>" autocomplete="off" required="required">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="input" class="col-sm-2 col-form-label">Description<sup style="color:red; font-size:18px">*</sup></label>
                                <div class="col-sm-10 col-md-6">
                                  
                                  <input type="text" class="form-control" id="input" name ="descr"autocomplete="off" value="<?php echo $cat['Description'];?>" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Ordering</label>
                                <div class="col-sm-10 col-md-6">
                                  <input type="text" class="form-control" id="inputPassword"  name ="order" value ="<?php echo $cat['Ordering'];?>" required="required"/>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label  class="col-sm-2 col-form-label">Visible<sup style="color:red; font-size:18px">*</sup></label>
                                <div class="col-sm-10 col-md-6">
                                  <input type="radio" id="yes"    name="visible" value="0"  <?php if($cat["Visibility"]==0) echo 'checked';?>/>
                                  <label for="yes">Yes</label><br>
                                  <input type="radio" id="no"   name="visible" value="1" <?php if($cat["Visibility"]==0) echo 'checked';?>/>
                                  <label for="no">No</label>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label  class="col-sm-2 col-form-label">Allow Comments<sup style="color:red; font-size:18px">*</sup></label>
                                <div class="col-sm-10 col-md-6">
                                  <input type="radio" id="yes"   name="allow" value="0" <?php if($cat["Allow_comments"]==0) echo 'checked';?> />
                                  <label for="yes">Yes</label><br>
                                  <input type="radio" id="no"    name="allow" value="1" <?php if($cat["Allow_comments"]==1) echo 'checked';?> />
                                  <label for="no">No</label>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label  class="col-sm-2 col-form-label">Allow ADS<sup style="color:red; font-size:18px">*</sup></label>
                                <div class="col-sm-10 col-md-6">
                                  <input type="radio" id="yes"    name="ads" value="0" <?php if($cat["Allow_ads"]==0) echo 'checked';?>  />
                                  <label for="yes">Yes</label><br>
                                  <input type="radio" id="no"    name="ads" value="1" <?php if($cat["Allow_ads"]==1) echo 'checked';?> />
                                  <label for="no">No</label>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class=" col-sm-10">
                                  <input type="submit"  value="Save"  class="btn btn-secondary bt-lg " >
                                </div>
                            </div>
                        </form>
                        
                    </div>
       

<?php }

      }elseif($do=='Update'){//Update page
        
                if($_SERVER['REQUEST_METHOD']=='POST'){
                  echo "<h1 class='text-center'>Update Category</h1>";
                  echo "<div class='container'>";
                
                  $id=$_POST['id'];
                  $name=$_POST['name'];
                  $description=$_POST['descr'];
                  $ordering=$_POST['order'];
                  $visible=$_POST['visible'];
                  $allocomments=$_POST['allow'];
                  $allowads=$_POST['ads'];
                if(empty($name)){
                  $msg='<div class ="alert alert-danger">Name can not be empty</div>';
                  redirectHome($msg,'back');
                }
                else{
                  $stmt=$con->prepare("UPDATE categories SET Name =? , Description=?,Ordering=? ,Visibility=?,Allow_comments=?,Allow_ads=? WHERE C_id =?");
                  $stmt->execute(array($name,$description,$ordering,$visible,$allocomments,$allowads,$id));
                  $msg=" <div class='alert alert-success'>".$stmt->rowCount()."  record has been Updated </div>";
                  redirectHome($msg,'back');
                  }

                }else{
                  $msg=" You are not allowed to view this page";
                  redirectHome($msg);
                }
                echo "</div>";
      }elseif($do=='Delete'){
        $id = isset($_GET['ca_id']) && is_numeric($_GET['ca_id']) ? intval($_GET['ca_id']):0;// check if user id is numeric
        //$stmt=$con->prepare("SELECT * FROM users WHERE ID = ?  LIMIT 1");
        $check=checkitem("C_id","categories",$id);

       // $stmt->execute(array($id));
       // $count=$stmt->rowCount(); 
        if($check>0){
          $stmt=$con->prepare("DELETE FROM categories WHERE C_id=?");
          $stmt->execute(array($id));
          echo "<h1 class='text-center'>Delete Category</h1>";
          echo "<div class='container'>";
          $msg= " <div class='alert alert-success'>This Category has been deleted Successfuly </div>";
          redirectHome($msg,'back');
        }else{
          $msg ="<div class='alert alert-success'>There Category ID is not exist</div>";
          redirectHome($msg);
        }

         echo"</div>";
       
        }
      include $tep.'footer.php';

  }else {header('Location:index.php');
        exit();
    }
    ob_end_flush();
  ?>