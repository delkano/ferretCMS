<?php 
// composer autoloader for required packages and dependencies
require_once('../vendor/autoload.php');
/** @var \Base $f3 */
$f3 = \Base::instance();
// F3 autoloader for application business code
$f3->config('../config.ini');

if(file_exists($f3->get("DB_NAME")))  {
    $f3->set('DB', new \DB\SQL('sqlite:'.$f3->get('DB_NAME')));

    // Load config (if present)
    $f3->set("cfg.logo", \Model\Config::read("logo"));
    $f3->set("cfg.title", \Model\Config::read("title"));
    $f3->set("cfg.description", \Model\Config::read("description"));
    $f3->set("cfg.home", \Model\Config::read("home"));

} else if($f3->get("URI") != '/install') {
    $f3->set('DB', new \DB\SQL('sqlite:'.$f3->get('DB_NAME')));
    (new \Controller\Base)->install($f3);
}

// Error handling
$f3->set('ONERROR',
    function($f3) {
        switch ($f3->get('ERROR.code')) {
        case 404: echo \Template::instance()->render('404.html'); break;
        case 500: echo "<h2>Error ".$f3->get('ERROR.code')."</h2><p>".$f3->get("ERROR.status")."</p><p>".$f3->get("ERROR.text")."</p><div><pre>".$f3->get("ERROR.trace")."</pre></div>"; break;
        case 403: $f3->reroute("/"); break;
        default: echo "<h2>Big mistake nº".$f3->get('ERROR.code')."</h2><p>".$f3->get("ERROR.status")."</p>"; break;
        }
    }
);

$f3->set("LOGGEDIN", (\Controller\Auth::role($f3) != 'guest'));
$f3->set("ADMIN", (\Controller\Auth::role($f3) == 'admin'));

// Special filters
\Template::instance()->filter('menu', '\Controller\Menu::instance()->getOne');

// Home
$f3->route("GET @home: /", function($f3) {
    if(!empty($f3->get("cfg.home"))) {
        $f3->reroute("@page_view(@slug=".$f3->get("cfg.home").")");
    } else {
        $f3->error(404);
        exit;
    }
});
$f3->route("GET @manifest: /manifest.json", function($f3) {
    echo \Template::instance()->render('manifest.json');
});

// Asset management
$f3->route('GET /assets/@type', '\Controller\Base->assets',	3600*24 );

// Login and auth
$f3->route('GET @login: /login', '\Controller\Auth->login');
$f3->route('POST @login_check: /login_check', '\Controller\Auth->check');
$f3->route('GET @logout: /logout', '\Controller\Auth->logout');

// Route access
$access = \Access::instance();
$access->policy('allow');
$access->deny('/config*');
$access->allow('/config*', 'admin');

$access->authorize(\Controller\Auth::role($f3), function() { \Base::instance()->reroute("@login"); });

// Normal routes
// - User management
$f3->route('GET @user_list: /users', '\Controller\User->getList');
$f3->route('GET @user_view: /user/@id/view', '\Controller\User->getOne');
$f3->route('GET @user_new: /user/new', '\Controller\User->edit');
$f3->route('GET @user_edit: /user/@id/edit', '\Controller\User->edit');
$f3->route('POST @user_update: /user/@id/update', '\Controller\User->update');
$f3->route('POST @user_create: /user/create', '\Controller\User->update');
$f3->route('GET @user_delete: /user/@id/delete', '\Controller\User->delete');
$f3->route('GET @user_profile: /user/profile', '\Controller\User->profile');
$f3->route('GET @user_profile_edit: /user/profile/edit', '\Controller\User->profileEdit');

// - Install: if base /install route doesn't exist, we can only access it by removing "db.sql": safer
//$f3->route('GET @install: /install', '\Controller\Base->install');
$f3->route('POST /postinstall', '\Controller\Base->post_install');

//Config
$f3->route('GET @config: /config/general', '\Controller\Config->create');
$f3->route('POST @config: /config/general', '\Controller\Config->save');
$f3->redirect('GET /config', '@config');

$f3->route('GET @menu_list: /config/menus', '\Controller\Menu->getList');
$f3->route('POST @menu_update: /config/menus', '\Controller\Menu->update');

// - Page navigation and stuff
$f3->route('GET @page_new: /config/page/new', '\Controller\Page->create');
$f3->route('GET @page_list: /config/pages', '\Controller\Page->editList');
$f3->route('GET @page_edit: /config/page/@id/edit', '\Controller\Page->edit');
$f3->route('POST @page_update: /config/page/@id/update', '\Controller\Page->update');
$f3->route('POST @page_create: /config/page/create', '\Controller\Page->update');
$f3->route('GET @page_delete: /config/page/@id/delete', '\Controller\Page->delete');
$f3->route('GET @category_view: /category/@cat', '\Controller\Page->viewList');
$f3->route('GET @category_edit: /config/category/@cat', '\Controller\Page->editList');
$f3->route('GET @page_view: /@slug', '\Controller\Page->getOne');

$f3->run();
