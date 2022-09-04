<?php

use models\Post;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorisation, X-Requested_With');

include_once '../../models/Post.php';
include_once '../../index.php';


$post = new Post();
$data = json_decode(file_get_contents('php://input'));

$post->setCategoryId($data->category_id);
$post->setTitle($data->title);
$post->setBody($data->body);
$post->setAuthor($data->author);
$post->setEmail($data->email);


if ($post->create()) {
    echo json_encode(['message' => 'Post created']);
} else {
    echo json_encode(['message' => 'Post not created']);
}