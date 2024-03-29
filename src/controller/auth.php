<?php

namespace Controller;

class Auth {
    /***
     * Login and authorization related functions
     ***/

    public function login($f3) {
        $f3->set('site.title', 'login');
        $f3->set("site.template", "login");
        echo \Template::instance()->render('layout.html');
    }

    public function check($f3) {
        $user = new \Model\User();
        $username = $f3->get("POST.username");
        $user->load(array('username = ?', $username));
        if($user->dry()) {
            $f3->reroute("@login");
        }
        if($f3->exists("POST.password") && password_verify($f3->get("POST.password"), $user->password)) {
            $f3->set("SESSION.user", $user->username);
            $f3->set("user", $user);
            $f3->reroute("@config");
        } else {
            $f3->reroute("@login");
        }
    }

    public static function role($f3) {
        $user = false;
        if(!empty($f3->get("user"))) $user = $f3->get("user");
        else if(!empty($f3->get("SESSION")) && !empty($f3->get("SESSION.user"))) {
            $user = \Controller\User::get($f3, $f3->get("SESSION.user"));
            $f3->set("user", $user);
        }
        if(!$user) return "guest";
        else return $user->role;
    }

    public function logout($f3) {
        $f3->set("SESSION.user", false);
        $f3->set("user", false);
        $f3->reroute("@login");
    }
}
