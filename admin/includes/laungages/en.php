<?php
  function lang($phrase)
  {
      static $lang=array(
          //Dashbord page
          'HOME_AREA'=>'Home',
          'CATEGORY'=>'Category',
          'ITEMS'=>'Items',
          'MEMBERS'=>'Members',
          'STATISTICS'=>'Statistics',
          'EDIT_PROFIL'=>'Edit profil',
          'SETTINGS'=>'Settings',
          'LOGOUT'=>'Logout',
           'COMMENTS'=>'Comments',
      );

      return $lang[$phrase];
  }