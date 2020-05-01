<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;



class ConfigController extends Controller {

    private $loggedUser;

    public function __construct(){
        $this->loggedUser = UserHandler::checkLogin();
        if(UserHandler::checkLogin()=== false){
            $this->redirect('/login');
        }
    }

    public function index($atts = []) {
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id =  $atts['id'];
        }

        $user =  UserHandler::getUser($id,true);

        if(!$user){
            $this->redirect('/');
        }

        $this->render('config',['loggedUser'=>$this->loggedUser]);
        
    }


    public function action() {
        $id =        $this->loggedUser->id;
        $name =      filter_input(INPUT_POST,'name');
        $password =  filter_input(INPUT_POST,'password');
        $birthdate = filter_input(INPUT_POST,'birthdate');
        $work =      filter_input(INPUT_POST,'work');
        $city =      filter_input(INPUT_POST,'city');

        $user = UserHandler::getUser($this->loggedUser->id, false);

    
        if($id && $name && $password && $birthdate){
            $birthdate = explode('/',$birthdate);
            if(count($birthdate) !=3){
                $_SESSION['flash'] =  'Data de nascimento não está  inválida!';
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