<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../Classes/user.php';

$database = new Database();
$db = $database->getConnection();

$user_obj = new User($db);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    //$data = json_decode(file_get_contents("php://input"));

    //how to pass token inside header

    $all_header = getallheaders();
    $data = $all_header['Authorization'];

    if (!empty($data)) {

        try {
            $secrect_key = "owt123456";

            $decode_data = JWT::decode($data, $secrect_key, array("HS512"));

            $user_id=$decode_data->data->id;

            http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "message" => "WE GOT JWT TOKEN",
                "user_data" => $decode_data,
                "user_id" => $user_id,

            ));
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => $ex->getMessage(),
            ));

        }
        
    }

}
