<?php
namespace Controller;

/**
 * This class has the job of creating the whole static thing.
 */
class Render {
    function createAll() {
        $f3 = \Base::instance();
        // Load config (if present)
        $f3->set("cfg.logo", \Model\Config::read("logo"));
        $f3->set("cfg.title", \Model\Config::read("title"));
        $f3->set("cfg.description", \Model\Config::read("description"));
        $f3->set("cfg.home", \Model\Config::read("home"));

        // Configurinc the static environment...
        $f3->set("static", true);
        $base = $f3->get("BASE");
        $f3->set("BASE", '');

        $f3->set("theme", \Model\Config::read("theme")?:"base");

        // Get the page list
        $pages = (new \Model\Page())->find();
        foreach($pages as $page)
            $this->create($page);

        $css = ['main.scss'];
        $js = ['plugin.js', 'main.js'];
        foreach($css as $file) $this->createAsset('css', $file);
        foreach($js as $file) $this->createAsset('js', $file);

        // Let's reset the variables
        $f3->set("static", false);
        $f3->set("BASE", $base);
        $f3->set("theme", "config");
    }

    function create($page) {
        $f3 = \Base::instance();
        $f3->set("site.title", $page->title);
        $f3->set("page", $page);

        $f3->set('site.template', "pageView");

        $html =  \Template::instance()->render($f3->theme."/layout.html");

        if($page->slug === \Model\Config::read("home")) {
            // Let's make a copy for the index
            $f3->write("../index.html", $html);
        }
        $filename = $page->slug . ".html";
        $f3->write("../" . $filename, $html);
    }

    function createAsset($type, $files) {
        $f3 = \Base::instance();

        $path = $f3->UI.$f3->theme.'/'.$type.'/';
        $newpath = '../' . $type . '/';

        if($type == 'css') {
            //$parser = new \Less_Parser(array('compress'=>true));
            $parser = new \ScssPhp\ScssPhp\Compiler;
            $parser->setImportPaths($path);
            $parser->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

            $endcontent = '';
            foreach(explode(",", $files) as $file) 
                $endcontent.= $parser->compileString($f3->read($path.$file))->getCss(); 

            $f3->write($newpath.str_replace('.scss', '.css', $file), $endcontent);

        } else {
            $files = preg_replace('/(\.+\/)/','',$files); // close potential hacking attempts  
            
            foreach(explode(",", $files) as $file) 
                $f3->write($newpath.$file, $f3->read($path.$file));
            //echo \Template::instance()->resolve(\Web::instance()->minify($files, null, true, $path));
        }
    }

}
