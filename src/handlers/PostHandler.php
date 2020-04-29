<?php
namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\UserRelation;

class PostHandler {
    
    public static function addPost($idUser, $type, $body) {
        if(!empty($idUser)) {

            Post::insert([
                'id_user'=>$idUser,
                'type'=>$type,
                'created_at'=> date('Y-m-d H:i:s'),
                'body'=>$body
            ])->execute();
        }
    }

    public static function getHomeFeed($idUser,$page) {
        $perPage = 10; // number of feeds per page shown


        $userList =  UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];
        foreach($userList as $userItem) {
            $users[] = $userItem['user_to'];
        }
        $users[] = $idUser;

      

        $postList = Post::select()
            ->where('id_user','in',$users)
            ->orderBy('created_at','desc')
            ->page($page,$perPage)
        ->get();

        $total = Post::select()
            ->where('id_user','in',$users)
        ->count();  
        $pageCount =  ceil($total / $perPage); // total of pages to show
    

    $posts = [];
    foreach($postList as $postItem){
            $newPost =  new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->created_at =  $postItem['created_at'];
            $newPost->body = $postItem['body'];
            $newPost->mine = false;

            // check if the current user is post author
            if($postItem['id_user'] == $idUser){
                $newPost->mine = true;
            }

            $newUser =  User::select()->where('id',$postItem['id_user'])->one();
            $newPost->user =  new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name =  $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            // amount of likes 
            $newPost->likeCount = 0;

            $newPost->liked = false;

            //amount of comments
            $newPost->comments = [];

            $posts[] =  $newPost;
        }

    return ['posts'=>$posts, 'pageCount'=>$pageCount, 'currentPage' => $page];
    }

}

?>