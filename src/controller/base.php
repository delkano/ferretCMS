<?php
namespace Controller;

class Base {
    public function install($f3) {
        echo "<h3>Creando bases de datos...</h3>";

        $models = array('User', 'Page', 'Config', 'Menu', 'Plugin');
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
        $f3->set("ADMIN", false);

        $f3->route('POST @user_create: /postinstall', '\Controller\Base->post_install');
        echo \Template::instance()->render('templates/userEdit.html');
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

        // Let's create an empty home page and set it up as home
        $page = new \Model\Page();
        $page->title = "Home";
        $page->content = "New page...";
        $page->slug = \Web::instance()->slug($page->title);
        $page->save();
        \Model\Config::store("home", $page->slug);

        $f3->set("user", $user);
        $f3->set("SESSION.user", $user->username);

        // We should create here the whole admin menu
        $menu = new \Model\Menu();
        $menu->name = "Config Menu";
        $menu->save();

        $menuentry = new \Model\Menu();
        $menuentry->parent = $menu;
        $menuentry->name = "General";
        $menuentry->url = $f3->BASE."/general";
        $menuentry->save();
        $menuentry = new \Model\Menu();
        $menuentry->parent = $menu;
        $menuentry->name = "Pages";
        $menuentry->url = $f3->BASE."/pages";
        $menuentry->save();
        $menuentry = new \Model\Menu();
        $menuentry->parent = $menu;
        $menuentry->name = "Menus";
        $menuentry->url = $f3->BASE."/menus";
        $menuentry->save();

        // And an empty main menu
        $menu = new \Model\Menu();
        $menu->name = "Main Menu";
        $menu->save();

        \Model\Config::store("logo", "img/logo.png");

        $f3->reroute("config");
    }

    public function assets($f3, $args) {
        $path = $f3->UI.'/'.$args['type'].'/';

        if($args['type'] == 'css') {
            //$parser = new \Less_Parser(array('compress'=>true));
            $parser = new \ScssPhp\ScssPhp\Compiler;
            $parser->setImportPaths($path);
            $parser->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
            $files = empty($_GET['files'])?$_GET['?files']:$_GET['files']; // Lighttpdf fix

            header('Content-type: text/css');
            foreach(explode(",", $files) as $file) 
                echo $parser->compileString($f3->read($path.$file))->getCss();

        } else {
            $files = empty($_GET['files'])?$_GET['?files']:$_GET['files']; // Lighttpdf fix
            $files = preg_replace('/(\.+\/)/','',$files); // close potential hacking attempts  
            
            header('Content-type: application/x-javascript');
            foreach(explode(",", $files) as $file) 
                echo $f3->read($path.$file);
            //echo \Template::instance()->resolve(\Web::instance()->minify($files, null, true, $path));
        }
    }
}
