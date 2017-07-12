<?php
namespace Controller;

class Base {
    public function install($f3) {
        echo "<h3>Creando bases de datos...</h3>";

        $models = array('User', 'Page', 'Config', 'Menu');
        foreach($models as $model) {
            $class = "\Model\\$model";
            if( $class::setup() )
                echo "<p>Tabla <code>$model</code> creada</p>";
            else
                echo "<p>No se ha podido crear la tabla <code>$model</code>.</p>";
        }

        // Let's create the Manager
        $user = new \Model\User();
        $user->role='admin';
        $f3->set("user", $user);

        $f3->set("SESSION.INSTALLING", true);

        $f3->route('POST @user_create: /postinstall', '\Controller\Base->post_install');
        echo \Template::instance()->render('templates/userEdit.html');
        exit;
    }

    public function post_install($f3) {
        if(!$f3->exists("SESSION.INSTALLING")) {
            $f3->status(404);
            exit;
        }
        $f3->set("SESSION.INSTALLING", false);

        $user = new \Model\User();
        //Getting the data
        $user->username = trim($f3->get("POST.username"));
        if(!empty($f3->get("POST.password")))
            $user->password = password_hash($f3->get("POST.password"), PASSWORD_DEFAULT);
        $user->name = trim($f3->get("POST.name"));
        $user->email = trim($f3->get("POST.email"));
        $user->role = 'admin';
        $user->save();

        $f3->set("user", $user);
        $f3->set("SESSION.user", $user->username);

        $f3->reroute("config");
    }

    public function assets($f3, $args) {
        $path = $f3->get('UI').$args['type'].'/';

        if($args['type'] == 'less') {
            $parser = new \Less_Parser(array('compress'=>true));
            $files = $_GET['files'];

            foreach(explode(",", $files) as $file) 
                $parser->parseFile($path.$file);

            header('Content-type: text/css');
            echo $parser->getCss();
        } else {
            $files = preg_replace('/(\.+\/)/','',$_GET['files']); // close potential hacking attempts  
            
            echo \Preview::instance()->resolve(\Web::instance()->minify($files, null, true, $path));
        }
    }
}
