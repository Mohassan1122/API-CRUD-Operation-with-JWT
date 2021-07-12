<?php
ini_set('display_errors', 1);

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

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

    $data = json_decode(file_get_contents("php://input"));

    $all_header = getallheaders();

    if (!empty($data->name)) {

        try {

            $jwt = $all_header['Authorization'];

            $secrect_key = "owt123456";

            $decode_data = JWT::decode($jwt, $secrect_key, array("HS512"));

            $user_obj->user_id = $decode_data->data->id;
            $user_obj->project_name = $data->name;

            if ($user_obj->deleteProject()) {

                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "PROJECT DELETED successfully",
                ));
            } else {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "fail to Create project",
                ));
            }

        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => $ex->getMessage(),
            ));

        }

    }else {
        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "fill all field",
        ));
    }
}
