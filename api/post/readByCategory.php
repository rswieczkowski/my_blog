<?php


use models\Post;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../../models/Post.php';
include_once '../../index.php';

$post = new Post();



if(isset($_GET['category_id'])){
    $category_id = $_GET['category_id'];

    $result = $post->read($category_id);


    $rowsNumber = sizeof($result);


    if ($rowsNumber > 0) {
        $postArray = [];
        $postArray['data'] = [];
        $postArray['data'] = $post->getData();

        echo json_encode($postArray);
    } else {
        echo json_encode(['message' => 'No posts found']);
    }
} else {
    die();
}