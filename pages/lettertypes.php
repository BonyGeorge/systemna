<?php include "../template/header.php"; ?>

<?php if(!isset($_SESSION['username'])){header('Location:../index.php');}
  ?>
  <?php

  if (isset($_POST['priority'])) {

    $emp_id=$_SESSION['id'];
    $priority=$_POST['priority'];
    $arr=$_POST['arr'];
    $Type_id;
    $Status=2;
    $salary=$_POST['salary'];
    $length=count($arr);


    for ($i=0; $i <$length ; $i++) {
      if($arr[$i]=="General HR letter" ){
        $Type_id=1;
      }
      if($arr[$i]=="Embassy HR letter"){
        $Type_id=2;
      }
      if($arr[$i]=="Letter directed to specific organization"){
        $Type_id=3;
      }
      if($arr[$i]=="Letter to whom might concern"){
        $Type_id=4;
      }
      $sql="INSERT INTO requests (emp_id,Type_id,Status,priority,salary) VALUES ('$emp_id','$Type_id','$Status','$priority','$salary') ";
      $DB->query($sql);
      $DB->execute();
    }




      header("location: .php");
  }
  ?>
<h2>Choose the type of the letter that you want to apply for : </h2>
<hr>

<div id="row">
  <form method="post">
  <div id="column" >
<br>
    <b><input type="button" class="Letterbutton" id="btn2"
       value="General HR Letter" ></b>
  </div>
  <div id="column" >
    <br>
  <b><input type="button" class="Letterbutton"
    id="btn3" value="Embassy HR Letter"
      > </b>
  </div>
  <div id="column" >
    <br>
    <b><input type="button" class="Letterbutton" id="btn4"
       value="HR Letter directed to specific organization"></b>
  </div>
  <div id="column" >
    <br>
  <b>  <input type="button" class="Letterbutton" id="btn5"
    value="HR Letter to whom it may concern" ></b>

  </div>
  <br><br>
  <div id="Priorityform">
    <h4> Please choose the Letter priority : </h4>
    <label><input type="radio" name="Option1" id ="rdbtn1" value="Urgent"
      required> Urgent Request</label><br>

    <label><input type="radio" name="Option1" id ="rdbtn2" value="Normal">
      Normal Request</label><br>
    </div><br><hr><br>
    <div>
  <h4> Please choose the type that you want : </h4>
  <label><input type="radio" name="Option" id ="rdbtn3" value="With"
    required> With Salary</label><br>

  <label><input type="radio" name="Option" id ="rdbtn4" value="Without">
    Without Salary</label><br>
    <br><br><br>
    <input type="submit" id="submitbtn" value="Apply!">
</div>
  </form>




<?php include "../template/footer.php"; ?>
