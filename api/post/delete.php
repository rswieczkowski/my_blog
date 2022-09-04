<?php

use models\Post;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorisation, X-Requested_With');

include_once '../../models/Post.php';
include_once '../../index.php';


$post = new Post();
$data = json_decode(file_get_contents('php://input'));

$post->setId($data->id);

if($post->delete()) {

    echo json_encode(['message'=> 'Post deleted']);

} else {

    echo json_encode(['message' => 'Post not deleted']);

}
