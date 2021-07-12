<?php
ini_set("display_errors", 1);

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

include_once '../config/Database2.php';
include_once '../Classes/user.php';

$database = new Database();
$db = $database->getConnection();

$user_obj = new User($db);

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $all_header = getallheaders();
    $jwt = $all_header['Authorization'];

    try {
        $secrect_key = "owt123456";

        $decode_data = JWT::decode($jwt, $secrect_key, array("HS512"));

        $user_obj->user_id = $decode_data->data->id;

        $projects = $user_obj->get_single_users();

        if ($projects->num_rows > 0) {

            $projects_array = array();

            while ($row = $projects->fetch_assoc()) {

                $projects_array[] = array(
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "description" => $row["description"],
                    "user_id" => $row["user_id"],
                    "status" => $row["status"],
                    "created_at" => $row["created_at"],
                );

            }
            http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "projects" => $projects_array,
            ));
        } else {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "fail to insert",
            ));
        }
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(array(
            "status" => 0,
            "message" => $ex->getMessage(),
        ));

    }
}
