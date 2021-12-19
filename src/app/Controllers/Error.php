<?php

namespace App\Controllers;

class Error
{

    public function notFound()
    {
        header('HTTP/1.1 404 Not Found');
    }

    public function showUsers() {
        header('HTTP/1.1 404 Not Found');
        header('Content-Type: application/json');

        $jsonArray = array();
        $jsonArray['status'] = "404";
        $jsonArray['status_text'] = "route not defined+";

        echo json_encode($jsonArray);
    }

}