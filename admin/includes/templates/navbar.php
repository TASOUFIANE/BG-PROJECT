<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">Ecommerce</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashbord.php"><?php echo lang('HOME_AREA')?>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php"><?php echo lang('CATEGORY')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php"><?php echo lang('ITEMS')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php"><?php echo lang('MEMBERS')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS')?></a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="members.php?do=Edit& ID=<?php echo $_SESSION['UserId'];?>"><?php echo lang('EDIT_PROFIL')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('SETTINGS')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Logout.php"><?php echo lang('LOGOUT')?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo $_SESSION["Username"];?></a>
        </li>
      </ul>
      
    </div>
  </div>
</nav>