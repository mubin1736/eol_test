<?php
include("../web/conn/connection.php") ;

  if($_POST) 
  {
      $name     = strip_tags($_POST['c_id']);
     /* 
	  $stmt=$dbcon->prepare("SELECT user_id FROM users WHERE user_id=:name");
	  $stmt->execute(array(':name'=>$name));
	  $count=$stmt->rowCount();
	  */
	  $sql = mysql_query("SELECT user_id FROM login WHERE user_id='$name'");
	  $row = mysql_fetch_array($sql);
	  $aaa = $row['user_id'];
	  
	  if($aaa != '')
	  {
		  echo "<span style='color:red;font-size: 10px;'>[Sorry Username Already Taken]</span>";
	  }
	  else
	  {
	  echo "<span style='color:green;font-size: 10px;'>[Available]</span>";
	  }
  }
?>