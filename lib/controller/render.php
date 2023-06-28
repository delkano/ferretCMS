<?php
namespace Controller;

/**
 * This class has the job of creating the whole static thing.
 */
class Render {
    function createAll() {
        // Get the page list
        $pages = (new \Model\Page())->find();
        foreach($pages as $page)
            $this->create($page);
    }

    function create($page) {
        $f3 = \Base::instance();
        $f3->set("site.title", $page->title);
        $f3->set("page", $page);

        $f3->set('site.template', "pageView");

        $filename = $page->slug . ".html";
        $html =  \Template::instance()->render("layout.html");

        $f3->write($filename, $html);
    }

}
