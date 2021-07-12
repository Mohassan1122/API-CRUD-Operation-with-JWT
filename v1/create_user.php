<?php
ini_set("display_errors", 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../Classes/user.php';

$database = new Database();
$db = $database->getConnection();

$user_obj = new User($db);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email) && !empty($data->password) && !empty($data->age) && !empty($data->designation)) {
    
        $user_obj->name = $data->name;
        $user_obj->email = $data->email;
        $user_obj->password = password_hash($data->password, PASSWORD_DEFAULT); 
        $user_obj->age = $data->age;
        $user_obj->designation = $data->designation;

        $data = $user_obj->checkEmail();

    

        if (empty($data)) {

            if ($user_obj->createEmployee()) {
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "Created successfully",
                ));
            } else {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "fail to Create User",
                ));
            }
        } else {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Email already exist",
            ));
        }
    }else {
        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "fill all feilds",
        ));
    }

  

} else {
    http_response_code(503);
    echo json_encode(array(
        "status" => 0,
        "message" => "Access Denied",
    ));
}
