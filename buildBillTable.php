<?php
  $servername = "chester.cs.unm.edu";
  $username = "hhweeks";
  $password = "tFL0ruzf";
  $db = $username;
  //$data_file = '/Users/hhweeks/Documents/University/Databases564/bills/hconres/hconres1/data.json';
  $dir = '/Users/hhweeks/Documents/University/Databases564/bills';

  /*
  $conn = new mysqli($servername, $username, $password, $db);
  if ($conn->connect_error){
     die("Connection failed: " . $conn->connect_error);
  }
  */

  $conn =  mysql_connect($servername, $username,$password,"") or die('Could not connect: ' . mysql_error());
    mysql_select_db($username, $conn);

  echo "Connected successfully\n";

  listFiles($dir, $conn);

  function listFiles($dir, $conn){
    $ffs = scandir($dir);
    foreach($ffs as $ff){
      if($ff != '.' && $ff != '..'){
        if(is_dir($dir.'/'.$ff)){
          $subdir = $dir.'/'.$ff;
	  //echo "open subdir $subdir\n";
	  listFiles($subdir, $conn);
      	}
	else{
	  if($ff == "data.json"){
	    $data_file = $dir.'/'.$ff;
	    //echo "found file $data_file\n";
	    addBillTuple($data_file, $conn);
	  }
	}
      }
    }
  }
  


  /*
  if($handle = opendir($dir)){
    echo "Directory handle: $handle\n";
    echo "Entries: \n";

    while (false !== ($entry = readdir($handle))) {
      echo "$entry\n";
    }
  }
  */  

  function addBillTuple($data_file, $conn){
  
    $jsondata = file_get_contents($data_file);
    $data = json_decode($jsondata, true);
  
    $id = $data['bill_id'];
    $type = $data['bill_type'];
    $title = $data['official_title'];
    $popular_title = $data['popular_title'];
    $short_title = $data['short_title'];
    $status = $data['status'];
    $introduction_date = $data['introduced_at'];
    $summary = $data['summary']['text'];
    $congress = $data['actions']['text'];
    $number = $data['number'];

    $type = trimQuotes($type);
    $title = trimQuotes($title);
    $popular_title = trimQuotes($popular_title);
    $short_title = trimQuotes(short_title);
    $status = trimQuotes(status);
    $summary = trimQuotes($summary);
    

    //echo "bill:\n" . $id . $type . $title . $popular_title . $short_title . $status . $inctroduction_date . $summary . $congress . $number . "\n";
  
    $sql = "insert into Bill(id, type, title, popular_title, short_title, status, introduction_date, summary, congress, number)
         values('$id', '$type', '$title', '$popular_title', '$short_title', '$status', '$introduction_date', '$summary', '$congress', '$number')";
    if(!mysql_query($sql, $conn)){
  	  //die('Error : ' . mysql_error());
	  echo('Error : ' . mysql_error()) . "\n";
    }
  }

  function trimQuotes($str){
    return str_replace("'", "", $str);
  }



?>