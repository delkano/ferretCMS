<?php
namespace Controller;

class User {
    /***
     * Basic REST
     ***/

    public function getOne($f3, $params) {
        $id = intval($params['id']);
        $user = new \Model\User();
        $user->load(array('id=?', $id));

        if($user->dry()) {
            $f3->error(404);
        } else {
            $f3->set('user', $user);
            $f3->set('site.title', $user->name);
            $f3->set('site.template', "userView");

            if(!empty($f3->get("SESSION")) && !empty($f3->get("SESSION.user"))) {
                $me = \Controller\User::get($f3, $f3->get("SESSION.user"));
                if($id == $me->get("id")) 
                    $f3->set("myprofile", true);
            }
            echo \Preview::instance()->render('layout.html');
        }
    }
    public function getList($f3) {
        $useres = new \Model\User();
        $useres = $useres->find();

        $f3->set('useres', $useres);
        $f3->set("site.title", "User List");
        $f3->set("site.template", "userList");

        echo \Preview::instance()->render('layout.html');
    }
    public function profile($f3){
        if(!empty($f3->get("SESSION")) && !empty($f3->get("SESSION.user"))) {
            $user = \Controller\User::get($f3, $f3->get("SESSION.user"));
            $f3->set('user', $user);
            $f3->set('site.title', $user->name." - Edit");
            $f3->set('site.template', "userView");
            $f3->set("myprofile", true);

            echo \Preview::instance()->render('layout.html');
        }
    }

    public function profileEdit($f3){
        if(!empty($f3->get("SESSION")) && !empty($f3->get("SESSION.user"))) {
            $user = \Controller\User::get($f3, $f3->get("SESSION.user"));
            $f3->set('user', $user);
            $f3->set('site.title', $user->name." - Edit");
            $f3->set('site.template', "userEdit");
            $f3->set("myprofile", true);

            echo \Preview::instance()->render('layout.html');
        }
    }

    public function edit($f3, $params) {
        $user = new \Model\User();
        if(!empty($params['id'])) {
            $id = intval($params['id']);
            $user->load(array('id=?', $id));
            $new = false;
        } else {
            $new = true;
        }

        if($new || !$user->dry()) {
            $f3->set('user', $user);
            if($new)
                $f3->set('site.title', "New user");
            else
                $f3->set('site.title', $user->name." - Edit");
            $f3->set('site.template', "userEdit");

            echo \Preview::instance()->render('layout.html');
        } else {
            $f3->error(404);
        }
    }

    public function update($f3, $params) {
        if(!empty($params['hash'])) { // This is an invite. Let's check everything's alright
            $invite = new \Model\Invite();
            $invite->load(array('hash=?', $params['hash']));
            if($invite->dry()||$invite->status == 9) $f3->status(404);

            $invite->status = 9;
            $invite->save();
        } // All good? Resume.

        $user = new \Model\User();
        if(!empty($params['id'])) {
            $id = intval($params['id']);
            $user->load(array('id=?', $id));
        }
        //Getting the data
        $user->username = trim($f3->get("POST.username"));
        if(!empty($f3->get("POST.password")))
            $user->password = password_hash($f3->get("POST.password"), PASSWORD_DEFAULT);
        $user->name = trim($f3->get("POST.name"));
        $user->email = trim($f3->get("POST.email"));
        if($f3->get("user")->role == "admin" ) {
            $user->role = $f3->exists("POST.role")?$f3->get("POST.role"):"user";
        }

        $user->save();

        $f3->reroute("@user_view(@id=$user->id)");
    }
    public function delete($f3, $params) {
        $id = intval($params['id']);
        $user = new \Model\User();
        $user->load(array('id=?', $id));

        if($user->dry()) {
            $f3->error(404);
        } else {
            $user->erase();
            $f3->reroute("@users_list");
        }
    }

    public static function get($f3, $name) {
        $user = new \Model\User();
        $user->load(array('username = ?', $name));
        if($user->dry()) return false;
        else return $user;
    }
}
