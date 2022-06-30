<?php

//process_data.php

$connect = new PDO("mysql:host=localhost;dbname=chatbot_db", "root", "");

if(isset($_POST["query"]))
{	

	$data = array();

	$condition = preg_replace('/[^A-Za-z0-9\- ]/', '', $_POST["query"]);

	$query = "
	SELECT question FROM questions 
		WHERE question LIKE '%".$condition."%' 
		ORDER BY id DESC 
		LIMIT 10
	";

	$result = $connect->query($query);

	$replace_string = '<b>'.$condition.'</b>';

	foreach($result as $row)
	{
		$data[] = array(
			'question'		=>	str_ireplace($condition, $replace_string, $row["question"])
		);
	}

	echo json_encode($data);
}

$post_data = json_decode(file_get_contents('php://input'), true);


?>
