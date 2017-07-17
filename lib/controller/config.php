<?php

namespace Controller;

class Config {
    public function create($f3) {
        $f3->set("cfg.logo", \Model\Config::read("logo"));
        $f3->set("cfg.title", \Model\Config::read("title"));
        $f3->set("cfg.description", \Model\Config::read("description"));
        $f3->set("cfg.home", \Model\Config::read("home"));

        $f3->set("site.title", "Site Configuration");
        $f3->set('site.template', "configEdit");

        echo \Preview::instance()->render("layout.html");
    }

    public function save($f3) {
        $title = trim($f3->get("POST.title"));
        $description = trim($f3->get("POST.description"));
        $home = trim($f3->get("POST.home"));

        \Model\Config::store("title", $title);
        \Model\Config::store("description", $description);
        if(!empty($home))
            \Model\Config::store("home", $home);

        if(!empty($f3->get("FILES.upload-logo.name"))) {
            $f3->set('UPLOADS', 'img/');
            $web = new \Web();
            $file = $web->receive(function($file) {
                if($file['size'] > (2 * 1024 * 1024)) // if bigger than 2 MB
                    return false; // this file is not valid, return false will skip moving it

                // Let's make a few resized versions for the webapp icon
                $img = new \Image($file['tmp_name'], false, '');
                $img->resize(558, 558, false, false);
                $name = "tile.png";
                \Base::instance()->write( $name, $img->dump('png') );

                $img = new \Image($file['tmp_name'], false, '');
                $img->resize(558, 270, false, false);
                $name = "tile-wide.png";
                \Base::instance()->write( $name, $img->dump('png') );

                $img = new \Image($file['tmp_name'], false, '');
                $img->resize(144, 144, false, false);
                $name = "webapp-icon.png";
                \Base::instance()->write( $name, $img->dump('png') );

                $img = new \Image($file['tmp_name'], false, '');
                $img->resize(180, 180, false, false);
                $name = "apple-touch-icon.png";
                \Base::instance()->write( $name, $img->dump('png') );

                $img = new \Image($file['tmp_name'], false, '');
                $img->resize(32, 32, false, false);
                $name = "favicon.ico";
                \Base::instance()->write( $name, $img->dump('png') );

                return true;
            },true, function() { return "logo.png"; });

            $logo = array_keys($file)[0];
            \Model\Config::store("logo", $logo);
        }

        $f3->reroute("@home");
    }
}
