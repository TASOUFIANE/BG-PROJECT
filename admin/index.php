<?php 
session_start();

$Nonavbar='';
$pageTitle='Login';
if(isset($_SESSION["Username"])){header('Location:dashbord.php');
    //Recdirect to dashbord;
}  
include 'ini.php';
//check if user coming from http request;
if($_SERVER['REQUEST_METHOD']=='POST'){
    $user=$_POST['user'];
    $pass=$_POST['pass'];
    $hashedpass=sha1($pass);
    
    //check if the user  coming from database
    $stmt=$con->prepare("SELECT ID,Password,Username FROM users WHERE Username=? AND Password= ? AND GroupId=1  LIMIT 1");
    $stmt->execute(array($user,$hashedpass));
    $row=$stmt->fetch();
    $count=$stmt->rowCount();
    //if count >0 there is a user in database; 
    if( $count>0){
        $_SESSION["Username"]=$user;
        $_SESSION["UserId"]=$row['ID'];
        header('Location:dashbord.php');
        exit();
    }
}
 ?>

<form class="login" action=<?php echo $_SERVER['PHP_SELF'] ?> method ="POST">
    <h3 class=text-center>Admin login </h3>
    <label for="exampleInputEmail1" class="form-label">Username</label>
    <input type="text" id="myInput" class="form-control" name="user" id="exampleInputEmail1" placeholder="Username" aria-describedby="emailHelp"  autocomplete="off" > 
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" id="myInput" class="form-control" name="pass" id="exampleInputPassword1" placeholder="Password"  autocomplete="off" >
    <input type="submit" class="btn btn-primary" value="Login">
</form>

<?php include $tep.'footer.php';?>
