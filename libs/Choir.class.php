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

    public function users(){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('users'));
        return $this->db->executeCommand($cmd);
    }

    public function groups(){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('parts'));
        return $this->db->executeCommand($cmd);
    }

    public function byGroup($group){
        $cmd = new Predis\Command\SetMembers();
        $cmd->setRawArguments(array('part:' . $group));
        return $this->db->executeCommand($cmd);
    }

    public function data($name, $key){
        $cmd = strtolower($key) === "contact" || strtolower($key) === "notes" ?
            new Predis\Command\SetMembers() :
            new Predis\Command\StringGet();
        $cmd->setRawArguments(array('user:' . $name . ':' . $key));
        return $this->db->executeCommand($cmd);
    }

    public function create($name, $email, $phone, $part, $notes, $contact){
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
        $cmd->setRawArguments(array('user:'.$name.':tag', $part));
        $this->db->executeCommand($cmd);
            $scmd = new Predis\Command\SetAdd();
            $scmd->setRawArguments(array('tags', $part));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('tag:' . $part, $name));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('users', $name));
            $this->db->executeCommand($scmd);
        foreach($notes as $note){
            $scmd->setRawArguments('user:'.$name.':notes', $note);
            $this->db->executeCommand($scmd);
        }
        foreach($contact as $c){
            $scmd->setRawArguments('user:'.$name.':contact', $c);
            $this->db->executeCommand($scmd);
        }
        return true;
    }

    public function edit($name, $email, $phone, $parts, $notes, $contact){
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
        foreach($parts as $part){
            $scmd->setRawArguments(array('user:'.$name.':tags', $part));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('tags', $part));
            $this->db->executeCommand($scmd);
            $scmd->setRawArguments(array('tag:' . $part, $name));
            $this->db->executeCommand($scmd);
        }
        $del->setRawArguments(array('user:'.$name.':notes'));
        $this->db->executeCommand($del);
        foreach($notes as $note){
            $scmd->setRawArguments('user:'.$name.':notes', $note);
            $this->db->executeCommand($scmd);
        }
        $del->setRawArguments(array('user:'.$name.':contact'));
        $this->db->executeCommand($del);
        foreach($contact as $c){
            $scmd->setRawArguments('user:'.$name.':contact', $c);
            $this->db->executeCommand($scmd);
        }
        return true;
    }

    public function note($name, $note){
        $cmd = new Predis\Command\SetAdd();
        $cmd->setRawArguments(array('user:'.$name.':notes', $note));
        $this->db->executeCommand($cmd);
    }

    public function delNote($name, $note){
        $cmd = new Predis\Command\SetRemove();
        $cmd->setRawArguments(array('user:'.$name.':notes', $note));
        $this->db->executeCommand($cmd);
    }

    public function contact($name, $contact){
        $cmd = new Predis\Command\SetAdd();
        $cmd->setRawArguments(array('user:'.$name.':contact', $contact));
        $this->db->executeCommand($cmd);
    }
    public function delContact($name, $contact){
        $cmd = new Predis\Command\SetRemove();
        $cmd->setRawArguments(array('user:'.$name.':contact', $contact));
        $this->db->executeCommand($cmd);
    }

    public function delete($name){
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
            $card->setRawArguments('tag:' . $part);
            if($this->db->executeCommand($card) === 0){
                $rem->setRawArguments(array('tags', $part));
                $this->db->executeCommand($rem);
            }
        }
        $cmd->setRawArguments(array('user:'.$name.':tagss'));
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
        return new User($name, $this);
    }
}

class User{

    private $name;
    private $manager;

    public function __construct($name, $manager){
        $this->name = $name;
        $this->manager = $manager;
    }
}