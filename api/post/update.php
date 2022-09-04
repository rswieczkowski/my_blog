<?php

use models\Post;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorisation, X-Requested_with');

include_once '../../models/Post.php';
include_once '../../index.php';

$post = new Post();
$data = json_decode(file_get_contents('php://input'));

$post->setId($data->id);
$post->setCategoryId($data->category_id);
$post->setTitle($data->title);
$post->setBody($data->body);

if($post->update()) {

    echo json_encode(['message'=> 'Post updated']);

} else {

    echo json_encode(['message' => 'Post not updated']);
}
