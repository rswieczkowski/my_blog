<?php

use models\Post;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../../models/Post.php';
include_once '../../index.php';

$post = new Post();

$result = $post->read();


$rowsNumber = sizeof($result);


if ($rowsNumber > 0) {
    $postArray = [];
    $postArray['data'] = [];
    $postArray['data'] = $post->getData();

    echo json_encode($postArray);
} else {
    echo json_encode(['message' => 'No posts found']);
}
