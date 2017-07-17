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
        echo \Preview::instance()->render("layout.html");
    }
    
    public function create($f3) {
        $f3->set("site.title", $f3->get("L.page.creating"));
        $f3->set('site.template', "pageEdit");

        $page = new \Model\Page();
        $f3->set("page", $page);

        echo \Preview::instance()->render("layout.html");
    }

    public function edit($f3, $params) {
        $f3->set("site.title", $f3->get("L.page.updating"));
        $f3->set('site.template', "pageEdit");

        $page = new \Model\Page();
        if(!empty($params['id'])) {
            $id = intval($params['id']);
            $page->load(array('id=?', $id));
        }

        $f3->set("page", $page);

        echo \Preview::instance()->render("layout.html");
    }

    public function update($f3, $params) {
        $title = trim($f3->get("POST.title"));
        $content = trim($f3->get("POST.content"));
        if(empty($f3->get("POST.slug"))) {
            $slug = \Web::instance()->slug($title);
        }

        $page = new \Model\Page();
        if(!empty($params['id'])) {
            $id = intval($params['id']);
            $page->load(array('id=?', $id));
        }

        $page->title = $title;
        $page->content = $content;
        $page->slug = $slug;

        $page->save();

        $f3->reroute("@page_view(@id=$page->id)");
    }

    public function delete($f3, $params) {
        $id = intval($params['id']);
        $page = new \Model\Page();
        $page->load(array('id=?', $id));

        if($page->dry()) {
            $f3->error(404);
        } else {
            $page->erase();
            $f3->reroute("@pages_list");
        }
    }
}
