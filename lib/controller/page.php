<?php

namespace Controller;

class Page {
    public function getOne($f3, $params) {
        $page = new \Model\Page();
        if(!empty($params['id'])) {
            $id = intval($params['id']);
            $page->load(array('id=?', $id));
        } elseif(!empty($params['slug'])) {
            $slug = trim($params['slug']);
            $page->load(array('slug=?', $slug));
        }

        $f3->set("site.title", $page->title);
        $f3->set("page", $page);

        $f3->set('site.template', "pageView");
        echo \Template::instance()->render("layout.html");
    }

    public function editList($f3, $params) {
        $this->getList($f3, $params);
        $f3->set('site.subtemplate', "pageList");
        $f3->set('site.template', "config");
        echo \Template::instance()->render("layout.html");
    }
    public function viewList($f3, $params) {
        $this->getList($f3, $params);
        $f3->set('site.template', "categoryView");
        echo \Template::instance()->render("layout.html");
    }
    public function getList($f3, $params) {
        $pages = new \Model\Page();

        if(!empty($params["cat"])) {
            $pages = $pages->find(array("categories LIKE ?", "%\"$params[cat]\"%"));
        } else {
            $pages = $pages->find();
        }

        $f3->set("pages", $pages);
        $f3->set("site.title", $f3->get("L.page.list"));

    }
    
    public function create($f3) {
        $f3->set("site.title", $f3->get("L.page.creating"));
        $f3->set('site.subtemplate', "pageEdit");
        $f3->set('site.template', "config");

        $page = new \Model\Page();
        $f3->set("page", $page);

        echo \Template::instance()->render("layout.html");
    }

    public function edit($f3, $params) {
        $f3->set("site.title", $f3->get("L.page.updating"));
        $f3->set('site.subtemplate', "pageEdit");
        $f3->set('site.template', "config");

        $page = new \Model\Page();
        if(!empty($params['id'])) {
            $id = intval($params['id']);
            $page->load(array('id=?', $id));
        }

        $f3->set("page", $page);

        echo \Template::instance()->render("layout.html");
    }

    public function update($f3, $params) {
        $title = trim($f3->get("POST.title"));
        $content = trim($f3->get("POST.content"));
        if(empty($f3->get("POST.slug"))) {
            $slug = \Web::instance()->slug($title);
        }
        $cats = explode(",",$f3->get("POST.categories"));

        $page = new \Model\Page();
        if(!empty($params['id'])) {
            $id = intval($params['id']);
            $page->load(array('id=?', $id));
        }

        $page->title = $title;
        $page->content = $content;
        $page->slug = $slug;
        $page->categories = $cats;

        $page->save();

        $f3->reroute("@page_view(@slug=$page->slug)");
    }

    public function delete($f3, $params) {
        $id = intval($params['id']);
        $page = new \Model\Page();
        $page->load(array('id=?', $id));

        if($page->dry()) {
            $f3->error(404);
        } else {
            $page->erase();
            $f3->reroute("@page_list");
        }
    }
}
