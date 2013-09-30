<?php
class Choir{

    public $db;

    public $peregrine;

    public function __construct(){
        require('Predis/Autoloader.php');
        Predis\Autoloader::register();
        $this->db = new Predis\Client(array(
            'port' => 6380
        ));
        $auth = new Predis\Command\ConnectionAuth;
        $auth->setRawArguments(array(file_get_contents('../redispass.txt')));
        $this->db->executeCommand($auth);
    }
/* WORK IN PROGRESS. I can work on this later, but it's really difficult to deal with.
    public function getUsers($pattern){
        // First, we just want to make sure the parenths are even
        if(substr_count($pattern, '(') !== substr_count($pattern, ')')){
            return 'A Syntax Error occurred.';
        }
        $users = $this->users();
        $this->match($pattern, $users, 0);
    }

    private function match($pattern, $users, $nest){
        $regex = '/(\([^\)]+\))/';
        preg_match($regex, $pattern, $matches);
        array_shift($matches);
        if(empty($matches)){

        } else {
            foreach($matches as $match){
                $this->match($match, $users, $nest + 1);
            }
        }
    }
*/

    public function saveBuild($type, $categories, $glue, $data){
        $cmd = new Predis\Command\KeyExists();
        $cmd->setRawArguments(array('builds:next_id'));
        if(!($this->db->executeCommand($cmd))){
            $cmd = new Predis\Command\StringSet();
            $cmd->setRawArguments(array('builds:next_id', 0));
            $this->db->executeCommand($cmd);
            $id = 0;
        } else {
            $cmd = new Predis\Command\StringGet();
            $cmd->setRawArguments(array('builds:next_id'));
            $id = $this->db->executeCommand($cmd);
        }
        $cmd = new Predis\Command\StringSet();
        $cmd->setRawArguments(array('build:' . $id . ':type', $type));
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('build:' . $id . ':glue', $glue));
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('build:' . $id . ':data', $data));
        $this->db->executeCommand($cmd);
        $cmd = new Predis\Command\SetAdd();
        if(!empty($categories)){
            $cmd->setRawArguments(array_merge(array('build:' . $id . ':categories'), $categories));
            $this->db->executeCommand($cmd);
        }
        $cmd->setRawArguments(array('builds', $id));
        $this->db->executeCommand($cmd);
        $cmd = new Predis\Command\StringIncrement();
        $cmd->setRawArguments(array('builds:next_id'));
        $this->db->executeCommand($cmd);
    }

    public function getBuilds(){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('builds'));
        return $this->db->executeCommand($cmd);
    }

    public function build($id){
        return new Build($id, $this);
    }

    public function getUsers($categories = array(), $type = "AND"){
        $users = $this->users();
        if(empty($categories)){
            return $users;
        }
        $result = array();
        if($type === "AND"){
            foreach($users as $username){
                $user = $this->user($username);
                if(array_intersect($categories, $user->getTags()) === $categories){
                    $result[] = $username;
                }
            }
        } elseif($type === "OR") {
            foreach($users as $username){
                $user = $this->user($username);
                if(sizeof(array_intersect($categories, $user->getTags())) > 0){
                    $result[] = $username;
                }
            }
        }
        return $result;
    }

    public function users(){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('users'));
        return $this->db->executeCommand($cmd);
    }

    public function groups(){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('tags'));
        return $this->db->executeCommand($cmd);
    }

    public function byGroup($group){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('tag:' . $group));
        return $this->db->executeCommand($cmd);
    }

    public function inGroup($group){
        $cmd = new Predis\Command\SetCardinality();
        $cmd->setRawArguments(array('tag:' . $group));
        return $this->db->executeCommand($cmd);
    }

    public function create($name, $email, $phone, $part, $notes, $contact){
        if(empty($name)) return false;
        $check = new Predis\Command\SetIsMember();
        $check->setRawArguments(array('users', $name));
        if($this->db->executeCommand($check)){
            return false;
        }
        $cmd = new Predis\Command\StringSet();
        $cmd->setRawArguments(array('user:'.$name.':name', $name)); // derp
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':email', $email));
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':phone', $phone));
        $this->db->executeCommand($cmd);
            $scmd = new Predis\Command\SetAdd();
            $scmd->setRawArguments(array('users', $name));
            $this->db->executeCommand($scmd);
        $notes = $notes ? $notes : array();
        foreach($notes as $note){
            if(empty($note)) continue;
            $scmd->setRawArguments(array('user:'.$name.':notes', $note));
            $this->db->executeCommand($scmd);
        }
        $contact = $contact ? $contact : array();
        foreach($contact as $c){
            if(empty($c)) continue;
            $scmd->setRawArguments(array('user:'.$name.':contact', $c));
            $this->db->executeCommand($scmd);
        }
        $part = $part ? $part : array();
        foreach($part as $tag){ // TODO cleanup variable names
            if(empty($tag)) continue;
            $tag = str_replace('-', ' ', $tag);
            $scmd->setRawArguments(array('user:'.$name.':tags', $tag));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('tags', $tag));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('tag:' . $tag, $name));
            $this->db->executeCommand($scmd);
        }
        return true;
    }

    public function edit($name, $email, $phone, $parts, $notes, $contact){
        if(empty($name)) return false;
        $check = new Predis\Command\SetIsMember();
        $check->setRawArguments(array('users', $name));
        if(!$this->db->executeCommand($check)){
            return false;
        }
        $cmd = new Predis\Command\StringSet();
        $cmd->setRawArguments(array('user:'.$name.':name', $name)); // derp
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':email', $email));
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':phone', $phone));
        $this->db->executeCommand($cmd);
        $del = new Predis\Command\KeyDelete();
        $del->setRawArguments(array('user:'.$name.':tags'));
        $this->db->executeCommand($del);
        $scmd = new Predis\Command\SetAdd();
        $parts = $parts ? $parts : array();
        foreach($parts as $part){
            if(empty($part)) continue;
            $part = str_replace('-', ' ', $part);
            $scmd->setRawArguments(array('user:'.$name.':tags', $part));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('tags', $part));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('tag:' . $part, $name));
            $this->db->executeCommand($scmd);
        }
        $del->setRawArguments(array('user:'.$name.':notes'));
        $this->db->executeCommand($del);
        $notes = $notes ? $notes : array();
        foreach($notes as $note){
            if(empty($note)) continue;
            $scmd->setRawArguments(array('user:'.$name.':notes', $note));
            $this->db->executeCommand($scmd);
        }
        $del->setRawArguments(array('user:'.$name.':contact'));
        $this->db->executeCommand($del);
        $contact = $contact ? $contact : array();
        foreach($contact as $c){
            if(empty($c)) continue;
            $scmd->setRawArguments(array('user:'.$name.':contact', $c));
            $this->db->executeCommand($scmd);
        }
        return true;
    }


    public function delete($name){
        if(empty($name)) return false;
        $check = new Predis\Command\SetIsMember();
        $check->setRawArguments(array('users', $name));
        if(!$this->db->executeCommand($check)){
            return false;
        }

        $cmd = new Predis\Command\KeyDelete();
        $cmd->setRawArguments(array('user:'.$name.':name')); // derp
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':email'));
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':phone'));
        $this->db->executeCommand($cmd);

        $parts = new Predis\Command\SetMembers();
        $parts->setRawArguments(array('user:'.$name.':tags'));
        foreach($this->db->executeCommand($parts) as $part){
            $rem = new Predis\Command\SetRemove();
            $rem->setRawArguments(array('tag:' . $part, $name));
            $this->db->executeCommand($rem);
            $card = new Predis\Command\SetCardinality();
            $card->setRawArguments(array('tag:' . $part));
            if($this->db->executeCommand($card) === 0){
                $rem->setRawArguments(array('tags', $part));
                $this->db->executeCommand($rem);
            }
        }
        $cmd->setRawArguments(array('user:'.$name.':tags'));
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':notes'));
        $this->db->executeCommand($cmd);
        $cmd->setRawArguments(array('user:'.$name.':contact'));
        $this->db->executeCommand($cmd);
        return true;
    }

    /*
     * Database Schema:
     * We index by name!
     * "user:<name>:(name|email|phone)": STRING("The stuff")
     * "user:<name>:(contact|notes|tags)": SET("Different", "Contact", "Parent?", "Details", "Or", "Details")
     * "users": SET("Names")
     * "tags": SET("Soprano", "Alto", "Tenor", "Base", "student")
     * "tag:<tag>": SET("People", "Singing", "That", "Part")
     */

    public function user($name){
        return new User(str_replace(' ', '-', $name), $this);
    }
}

class User{

    private $name;
    private $manager;

    public function __construct($name, $manager){
        $this->name = $name;
        $this->manager = $manager;
    }

    public function getName(){
        return str_replace('-', ' ', $this->name);
    }

    public function exists(){
        $cmd = new Predis\Command\SetIsMember();
        $cmd->setRawArguments(array('users', $this->name));
        return $this->manager->db->executeCommand($cmd);
    }

    public function getRawName(){
        return $this->data('name');
    }

    public function note($name, $note){
        $cmd = new Predis\Command\SetAdd();
        $cmd->setRawArguments(array('user:'.$name.':notes', $note));
        $this->manager->db->executeCommand($cmd);
    }

    public function delNote($name, $note){
        $cmd = new Predis\Command\SetRemove();
        $cmd->setRawArguments(array('user:'.$name.':notes', $note));
        $this->manager->db->executeCommand($cmd);
    }

    public function contact($name, $contact){
        $cmd = new Predis\Command\SetAdd();
        $cmd->setRawArguments(array('user:'.$name.':contact', $contact));
        $this->manager->db->executeCommand($cmd);
    }
    public function delContact($name, $contact){
        $cmd = new Predis\Command\SetRemove();
        $cmd->setRawArguments(array('user:'.$name.':contact', $contact));
        $this->manager->db->executeCommand($cmd);
    }

    public function data($key){
        $cmd = strtolower($key) === "contact" || strtolower($key) === "notes" || strtolower($key) == "tags" ?
            new Predis\Command\SetMembers() :
            new Predis\Command\StringGet();
        $cmd->setRawArguments(array('user:' . $this->name . ':' . $key));
        return $this->manager->db->executeCommand($cmd);
    }
    //$name, $email, $phone, $part, $notes, $contact
    public function getEmail(){
        return $this->data('email');
    }
    public function getPhone(){
        return $this->data('phone');
    }
    public function getTags(){
        return $this->data('tags');
    }
    public function getNotes(){
        return $this->data('notes');
    }
    public function getContact(){
        return $this->data('contact');
    }
}

class Build{

    private $handler;
    private $id;

    public function __construct($id, $handler){
        $this->id = $id;
        $this->handler = $handler;
    }

    public function exists(){
        $cmd = new Predis\Command\SetIsMember();
        $cmd->setRawArguments(array('builds', $this->id));
        return $this->handler->db->executeCommand($cmd);
    }

    public function data($key){
        $cmd = strtolower($key) === "categories" ?
            new Predis\Command\SetMembers() :
            new Predis\Command\StringGet();
        $cmd->setRawArguments(array('build:' . $this->id . ':' . $key));
        return $this->handler->db->executeCommand($cmd);
    }

    public function getType(){
        return $this->data('type');
    }
    public function getGlue(){
        return $this->data('glue');
    }
    public function getCategories(){
        return $this->data('categories');
    }
    public function getData(){
        return $this->data('data');
    }
    public function getID(){
        return $this->id;
    }

}
