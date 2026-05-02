<?php

function jsonResponse($data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getJsonInput(): array
{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    return is_array($data) ? $data : [];
}