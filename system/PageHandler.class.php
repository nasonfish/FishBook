<?php

function include_e($page = false, $pass = array()){
    if($page !== false && file_exists($page)){
        global $manager;
        global $peregrine;
        include($page);
        return true;
    }
    return false;
}

class PageHandler {

    private $template;
    private $pass;

    /**
     * Handle the page.
     * @param string $template The name of the page we're using
     * @param array $pass What variables to pass along
     */
    public function __construct($template = "index", $pass = array()){
        $this->template = $template;
        $this->pass = $pass;
        include('../templates/main_tpl.php');
    }

    public function title(){
        if(!file_exists('../templates/' . $this->template . '_tpl.php')){
            return "404 - Page Not Found";
        }
        if(file_exists('../templates/' . $this->template . '_tpl.title.php')){
            include_e('../templates/' . $this->template . '_tpl.title.php', $this->pass);
            return "";
        }
        if(has('title:' . $this->template)){
            return get('title:' . $this->template);
        }
        return ucwords(str_replace('-', ' ', $this->template));
    }

    public function head(){
        include_e('../templates/' . $this->template . '_tpl.head.php', $this->pass);
        if(!file_exists('../templates/' . $this->template . '_tpl.php')){
            return '<link rel="canonical" href="/404/" />';
        }
        return "";
    }

    public function foot(){
        include_e('../templates/' . $this->template . '_tpl.foot.php', $this->pass);
    }

    public function js(){
        include_e('../templates/' . $this->template . '_tpl.js.php', $this->pass);
    }

    public function css(){
        include_e('../templates/' . $this->template . '_tpl.css.php', $this->pass);
    }

    public function page(){
        if(!include_e('../templates/' . $this->template . '_tpl.php', $this->pass)){
            $template = $this->template;
            include('../templates/404_tpl.php');
            //echo "<br/>I couldn't find the page you were looking for, sorry. Maybe you should head <a href='/'>home</a> and find your way back to where you were.<br/><br/>";// Let nasonfish &lt;nasonfish [at] gmail {dot} com&gt; know if you believe this is a mistake.";
        }
    }

    public function active($page = "index"){
        if($this->template == $page){
            return "active";
        }
        return "";
    }
}
