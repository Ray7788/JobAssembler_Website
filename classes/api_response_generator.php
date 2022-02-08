<?php

use JetBrains\PhpStorm\NoReturn;

class ApiResponseGenerator
{
    #[NoReturn] public static function generate_error_json(int $response_code, string $message) {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($response_code);
        echo(json_encode(array("message" => $message)));
        die(1);
    }

    #[NoReturn] public static function generate_response_json(int $response_code, array $data) {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($response_code);
        echo(json_encode($data));
        die(0);
    }
}