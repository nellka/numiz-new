<?php 
require_once("new/config.php");
require_once("new/models/mails.php");
$mail_class = new mails();	

if(isset($_GET['gate_pass']) AND $_GET['gate_pass'] == '1020304050' AND isset($_POST['data'])){
	
	// $handle =  mysql_connect('localhost', 'tester', '1020304050'  ) or die( 'Could not open connection to server' );
	// mysql_select_db( 'tet', $handle ) or die( 'Could not select database '. $handle );
	// mysql_query("SET NAMES 'cp1251'");
	// mysql_query("SET CHARACTER SET 'cp1251'");
	
	$data = unserialize(urldecode($_POST['data']));

	if(!isset($data['status'])) die('Error!');
	
	$insert_array = array();
	foreach($data['data'] as $row){
	   
		$insert_array["email"] = $row["email"];
		$insert_array["subject"] = $row["subject"];
		$insert_array["message"] = $row["message"];
		$insert_array["headers"] = $row["headers"];
		$insert_array["is_new_send_method"]  = $row["is_new_send_method"];
		$insert_array["is_send"] = 1;
		if($insert_array["is_new_send_method"]){
		    $mail_class->subscriptionLetter($row["email"],iconv("cp1251","utf-8",$row["subject"]),iconv("cp1251","utf-8",$row["message"]));
		} else {
		     mail($row["email"], $row["subject"], $row["message"], $row["headers"]);
		}
	}
	
}else{
	die('Security error!');
}


function mysql_insert_array($table, $data, $exclude = array()) {

    $fields = $values = array();

    if( !is_array($exclude) ) $exclude = array($exclude);

    foreach( array_keys($data) as $key ) {
        if( !in_array($key, $exclude) ) {
            $fields[] = "`$key`";
            $values[] = "'" . mysql_real_escape_string($data[$key]) . "'";
        }
    }

    $fields = implode(",", $fields);
    $values = implode(",", $values);

    if( mysql_query("INSERT INTO `$table` ($fields) VALUES ($values)") ) {
        return array( "mysql_error" => false,
                      "mysql_insert_id" => mysql_insert_id(),
                      "mysql_affected_rows" => mysql_affected_rows(),
                      "mysql_info" => mysql_info()
                    );
    } else {
        return array( "mysql_error" => mysql_error() );
    }

}

?>