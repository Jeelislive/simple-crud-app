<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: Content-Type,Authorization"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$connect = new PDO("mysql:host=127.0.0.1;port=3307;dbname=mycrud", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$method = $_SERVER['REQUEST_METHOD']; 

if($method === 'GET')
{
	if(isset($_GET['id']))
	{
		$query = "SELECT * FROM sample_users WHERE id = '".$_GET["id"]."'";

		$result = $connect->query($query, PDO::FETCH_ASSOC);

		$data = array();

		foreach($result as $row)
		{
			$data['first_name'] = $row['first_name'];

			$data['last_name'] = $row['last_name'];

			$data['email'] = $row['email'];

			$data['id'] = $row['id'];
		}

		echo json_encode($data);
	}
	else 
	{
		//fetch all user

		$query = "SELECT * FROM sample_users ORDER BY id DESC";

		$result = $connect->query($query, PDO::FETCH_ASSOC);

		$data = array();

		foreach($result as $row)
		{
			$data[] = $row;
		}

		echo json_encode($data);
	}

	
}

if($method === 'POST')
{
	//Insert User Data

	$form_data = json_decode(file_get_contents('php://input'));

	$data = array(
		':first_name'		=>	$form_data->first_name,
		':last_name'		=>	$form_data->last_name,
		':email'			=>	$form_data->email
	);

	$query = "
	INSERT INTO sample_users (first_name, last_name, email) VALUES (:first_name, :last_name, :email);
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	echo json_encode(["success" => "done"]);
}

if($method === 'PUT')
{
	//Update User Data

	$form_data = json_decode(file_get_contents('php://input'));

	$data = array(
		':first_name'		=>	$form_data->first_name,
		':last_name'		=>	$form_data->last_name,
		':email'			=>	$form_data->email,
		':id'				=>	$form_data->id
	);

	$query = "
	UPDATE sample_users 
	SET first_name = :first_name, 
	last_name = :last_name, 
	email = :email 
	WHERE id = :id
	";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	echo json_encode(["success" => "done"]);
}

if($method === 'DELETE')
{
	//Delete User Data
	
	$data = array(
		':id' => $_GET['id']
	);

	$query = "DELETE FROM sample_users WHERE id = :id";

	$statement = $connect->prepare($query);

	$statement->execute($data);

	echo json_encode(["success" => "done"]);
}


?>