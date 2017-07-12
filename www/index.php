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

//new \DB\SQL\Session($f3->get("DB"));

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

// Home
$f3->route("GET @home: /", '\Controller\Base->home');
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
$access = Access::instance();
$access->policy('allow');
$access->deny('/config');
$access->allow('/config', 'admin');

$access->authorize(\Controller\Auth::role($f3));

// Normal routes
// - User management
$f3->route('GET @user_list: /useres', '\Controller\User->getList');
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

// - Page navigation and stuff
$f3->route('GET @page_view: /@slug', '\Controller\Page->getOne');

$f3->run();
