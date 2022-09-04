<?php

use models\Post;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../../models/Post.php';
include_once '../../index.php';

$post = new Post();

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $post->readSingle($id);
} else {
    die();
}




$postArray = $post->getData();

echo json_encode($postArray);


