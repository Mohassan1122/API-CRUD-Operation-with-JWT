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

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email) && !empty($data->password)) {

        $user_obj->email = $data->email;

        $user_data = $user_obj->user_login();

        if (!empty($user_data)) {
            // echo json_encode($user_data['name']);

            // echo json_encode($data->password);

            $name = $user_data['name'];
            $email = $user_data['email'];
            $password = $user_data['password'];
            $age = $user_data['age'];
            $designation = $user_data['designation'];

            if (password_verify($data->password, $password)) {

                $iss = "localhost";
                $iat = time();
                $nbf = $iat + 10;
                $exp = $iat + 360;
                $aud = "myusers";
                $user_data = array(
                    "id" => $user_data['id'],
                    "name" => $user_data['name'],
                    "email" => $user_data['email'],
                    "age" => $user_data['age'],
                    "designation" => $user_data['designation'],
                );

                $payload_info = array(

                    "iss" => $iss,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "exp" => $exp,
                    "aud" => $aud,
                    "data" => $user_data,
                );

                $secrect_key = "owt123456";

                $jwt = JWT::encode($payload_info, $secrect_key, "HS512");

                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "jwt" => $jwt,
                    "message" => "LOGIN SUCESSFULLY",
                ));
            } else {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "fail to insert",
                ));
            }

        } else {
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
}
