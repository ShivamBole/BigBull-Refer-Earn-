<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:*");
header("Access-Control-Allow-Methods:*");

$db_conn = mysqli_connect("localhost", "root", "", "bigbull");

if ($db_conn === false) {

  die("ERROR: Could Not Connect" . mysqli_connect_error());

}

$method = $_SERVER['REQUEST_METHOD'];

//echo "test".$method; die;


switch ($method) {

  case "GET":
    $alluser = mysqli_query($db_conn, "SELECT * FROM users WHERE user_id='663673f2176ff'");
    
    if (mysqli_num_rows($alluser) > 0) {
      while ($row = mysqli_fetch_array($alluser)) {
        $json_array["userdata"][]= array("id" => $row['id'], "user_id"=>$row['user_id'], "username" => $row['username'], "balance" => $row['balance'], "profit" => $row['profit'], "date" => $row['date'], "reffer" => $row['refferal']);
      }
  $info=$json_array["userdata"];
   
    $refferid= $info[0]['user_id'];
   
     $reffer =  mysqli_query($db_conn, "SELECT * FROM users WHERE refferal='$refferid'");
      if (mysqli_num_rows($reffer) > 0) {
        $invite=[];
        while($row = mysqli_fetch_array($reffer)) {
          $json_array["userdata"][]= array("id" => $row['id'], "user_id"=>$row['user_id'], "username" => $row['username'], "date" => $row['date'],"plan"=>$row['plan']);
        //  array_push($invite,$row);
        }
         echo json_encode( $json_array["userdata"]);
      return;
    }else{
     $data= $json_array["userdata"];
         echo json_encode($data);
         return;
    }
  }else {
      echo json_encode(["result" => "Please check the data"]);
      return;
    }
    break;

  case "POST":

    $userpostdata = json_decode(file_get_contents("php://input"));

    // echo "sucess data";

    // print_r($userpostdata); die;
    $user_id=uniqid();
    $username = $userpostdata->username;
    $email = $userpostdata->email;
    $mobile = $userpostdata->mobile;
    $address = $userpostdata->address;
    $plan = $userpostdata->palns;
    $profit = '00000';
    $balance = $userpostdata->balance +$profit;
    $refferal = $userpostdata->refferal;
    $password = $userpostdata->password;
    $bonus = (25*($balance-$profit))/100;
    $result1 = mysqli_query($db_conn,"INSERT INTO `users`(`user_id`,`username`, `mobile`, `email`, `address`, `plan`, `balance`, `profit`, `refferal`, `password`) VALUES('$user_id','$username', '$mobile', '$email','$address','$plan','$balance',$profit,'$refferal',' $password')")or die('username in not available');
    $result2 = mysqli_query($db_conn,"UPDATE `users` SET profit=profit+$bonus ,balance=balance+$bonus WHERE user_id='$refferal'" )or die();
    // if ($result2) {
    //   $result4=mysqli_query($db_conn,"SELECT 'refferal' FROM users WHERE 'user_id'='$refferal'" );
    //   if (mysqli_num_rows($result4) > 0) {
    //     while ($row = mysqli_fetch_assoc($result4)) {
    //    echo($row);
    //     }

    //     return;
    //   } else {
    //     echo json_encode(["result" => "Please check the data"]);
    //     return;
    //   }
    //   while($result4!="NUll"){
    //   $reff=mysqli_query($db_conn,"SELECT `refferal` FROM users WHERE user_id=`$result4`" );
    //   $intensive=((4*($balance-$profit))/100);
    //   $result3=mysqli_query($db_conn,"UPDATE `users` SET profit=profit+$intensive ,balance=balance+$bonus WHERE user_id='$reff'" );
    //   $result4=$reff;
    //   }
      
    //   return;
    // } else {
 
    //   return;

    // }
    // $profit2 =(25*$balance)/100 
   // $result2 = mysqli_query($db_conn,"UPDATE `users` SET `profit`='$profit' WHERE 1")
    if ($result1) {
      echo json_encode(["success" => "User Added Successfully"]);
      return;

    } else {

      echo json_encode(["success" => "Please Check the User Data!"]);

      return;

    }

    break;
   
}
