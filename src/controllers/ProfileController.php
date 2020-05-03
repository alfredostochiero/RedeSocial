<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;


class ProfileController extends Controller {

    private $loggedUser;

    public function __construct(){
        $this->loggedUser = UserHandler::checkLogin();
        if(UserHandler::checkLogin()=== false){
            $this->redirect('/login');
        }
    }

    public function index($atts = []) {
        $page =  intval(filter_input(INPUT_GET,'page'));
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id =  $atts['id'];
        }

        $user =  UserHandler::getUser($id,true);

        if(!$user){
            $this->redirect('/');
        }

        // Getting user's birthdate and converting into years
        $dateFrom =  new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');

        $user->ageYears =  $dateFrom->diff($dateTo)->y;

        // Getting user's feed
        $feed = PostHandler::getUserFeed($id,$page,$this->loggedUser->id);

        // Checking if current User is following that user
        $isFollowing =  false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing =  UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }



       $this->render('profile', [
           'loggedUser'=>$this->loggedUser,
           'user'=>$user,
           'feed'=>$feed,
           'isFollowing' => $isFollowing
           ]
        );
    }

    public function follow($atts) {
        $to =  intval($atts['id']);

        if(UserHandler::idExists($to)){

            if(UserHandler::isFollowing($this->loggedUser->id, $to)){
                UserHandler::unfollow($this->loggedUser->id, $to);
            } else {
                UserHandler::follow($this->loggedUser->id, $to);
            }

        }

        $this->redirect('/perfil/'.$to);
    }

    public function friends($atts=[]) {

        // teste alfredo 
        if(!empty($atts['s'])){
            $seg = $atts['s'];
        }else{
            $seg = "";
        }
        


        //

        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id =  $atts['id'];
        }

        $user =  UserHandler::getUser($id,true);

        if(!$user){
            $this->redirect('/');
        }

        // Getting user's birthdate and converting into years
        $dateFrom =  new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');

        $user->ageYears =  $dateFrom->diff($dateTo)->y;

        $isFollowing =  false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing =  UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_friends', [
            'loggedUser'=>$this->loggedUser,
            'user'=>$user,
            'isFollowing' => $isFollowing,
            'seg'=>$seg
        ]);

    }

    public function photos($atts=[]) {

        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id =  $atts['id'];
        }

        $user =  UserHandler::getUser($id,true);

        if(!$user){
            $this->redirect('/');
        }

        // Getting user's birthdate and converting into years
        $dateFrom =  new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');

        $user->ageYears =  $dateFrom->diff($dateTo)->y;

        $isFollowing =  false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing =  UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_photos', [
            'loggedUser'=>$this->loggedUser,
            'user'=>$user,
            'isFollowing' => $isFollowing
        ]);

    }

    // A partir daqui, eu criei o codigo sozinho

    public function config(){

        $this->render('profile_config', [
            'loggedUser'=>$this->loggedUser,
    
        ]);

    }

    public function updateUser() {
        $id =        filter_input(INPUT_POST,'id');
        $name =      filter_input(INPUT_POST,'name');
        $password =  filter_input(INPUT_POST,'password');
        $birthdate = filter_input(INPUT_POST,'birthdate');
        $work =      filter_input(INPUT_POST,'work');
        $city =      filter_input(INPUT_POST,'city');

    
        if($id && $name && $password && $birthdate){
            $birthdate = explode('/',$birthdate);
            if(count($birthdate) !=3){
                $_SESSION['flash'] =  'Data de nascimento inválida!';
                $this->redirect('/config');
            }
            $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
            if(strtotime($birthdate) === false){
                $_SESSION['flash'] =  'Data de nascimento inválida!';
                $this->redirect('/config');
            }

            UserHandler::upUser($id,$name,$password,$birthdate,$work,$city);
            $this->redirect('/config');

        } else  {
            $this->redirect('/config');
        }

    }


}