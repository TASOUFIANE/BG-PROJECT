<?php 
    ob_start();
    session_start();
    $pageTitle='Dashbord';
    if(isset($_SESSION["Username"])){
        $pageTitle='Dashbord';
        include 'ini.php';?>
        <div class="container home-stats text-center">
            <h1 class="dashboard-title">Dashboard</h1>

            <div class="row">
                <div class="col-md-3">
                  <div class="stat st-membres">
                     <i class="fa fa-users"></i>
                    <div class="info">
                        Total Members
                            <span><a href="members.php"><?php echo CountItems("ID","users") ?></a></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="stat st-panding">
                  <i class="fa fa-plus"></i>
                    <div class="info">
                      Panding Members
                     
                      <span><a href="members.php?do=Manage&page=Pending"><?php echo checkitem("RegStatus","users",0) ?></a></span>
                    </div>
                    </div>
                </div>
                <div class="col-md-3">
                  <div class="stat st-item">
                  <i class="fa fa-tag"></i>
                    <div class="info">
                      Total Items
                      <span><a href="itmes.php"><?php echo CountItems("Item_id","itmes") ?></a></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="stat st-comment">
                  <i class="fa fa-comments"></i>
                    <div class="info">
                      Total Comments
                      <span><a><?php echo CountItems("com_id","comments") ?></a></span>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        <div class="container latest">
            <div class="row">
              <div class="col-sm-6">
                 <div class="card" style="width: 34rem;">
                        <div class="card-header">
                            <i class="fa fa-users"></i> Latest 5 Registerd users
                        </div>
                        <?php $thelatsetuser=getLatest("*","users","ID");
                         foreach($thelatsetuser as $user){
                            echo '<ul class="list-group list-group-flush">';
                            echo '<li class="list-group-item">';
                            echo $user['Username'] ;
                            
                            echo '<a href="members.php?do=Edit&ID='.$user["ID"].'">';
                            echo '<span class="btn btn-outline-success pull-right"><i class="fa fa-edit"></i> Edit ';
                                if($user["RegStatus"]==0){
                                    echo" <a href='members.php?do=Approve&ID=".$user["ID"]."'class='btn btn-outline-info pull-right'><i class='fa fa-check'></i> Approve</a>";
                                }
                            echo' </span></li>';
                            echo  '</a>';  
                            echo'</ul>';}?>
                 </div>
               </div>
               <div class="col-sm-6">
                 <div class="card" style="width: 34rem;">
                        <div class="card-header">
                            <i class="fa fa-tag"></i> Latest 5 Items
                        </div>
                       <?php $thelatsetitem=getLatest("*","itmes","Item_id");
                         foreach($thelatsetitem as $item){
                            echo '<ul class="list-group list-group-flush">';
                            echo '<li class="list-group-item">';
                            echo $item['Item_name'] ;
                            
                            echo '<a href="itmes.php?do=Edit&ID='.$item["Item_id"].'">';
                            echo '<span class="btn btn-outline-success pull-right"><i class="fa fa-edit"></i> Edit ';
                                if($item["Approve"]==0){
                                    echo" <a href='items.php?do=Approve&ID=".$user["Item_id"]."'class='btn btn-outline-info pull-right'><i class='fa fa-check'></i> Approve</a>";
                                }
                            echo' </span></li>';
                            echo  '</a>';  
                            echo'</ul>';}?>
                    </div>
               </div>
            </div>
        </div>
        <?php include $tep.'footer.php';
    }
    
    else{ echo 'You are not allowed to view this page';
        header('Location:index.php');
        exit();
    }
    ob_end_flush();?>