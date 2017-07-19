<?php

namespace Controller;

class Menu extends \Prefab {
    public function getOne($name) { //This is a special controller, as it will only be used as a filter
        $menu = new \Model\Menu();
        $menu->load(array('name=?', $name));

        if($menu->dry()) return "";

        \Base::instance()->set("menu", $menu);

        return \Template::instance()->render("menu.html");
    }

    public function getList($f3) {
        $menus = new \Model\Menu();
        $menus = $menus->find(array("parent=?", null));

        $pages = (new \Model\Page())->find();

        $f3->set("menus", $menus);
        $f3->set("pages", $pages);
        

        $f3->set("site.title", $f3->get("L.menu.list"));
        $f3->set('site.subtemplate', "menuList");
        $f3->set('site.template', "config");

        $f3->set("js_files", array("config"));

        echo \Template::instance()->render("layout.html");
    }

    public function update($f3) {
        foreach($f3['POST']['delete']?:[] as $id) {
            $menu = new \Model\Menu();
            $menu->load(array("id=?", $id));
            $menu->erase();
        }
        foreach($f3['POST']['menu']?:[] as $line) {
            $menu = new \Model\Menu();
            if(isset($line['id'])) {
                $menu->load(array('id=?',intval($line['id'])));
                if($menu->dry())
                    $f3->error(500);
            }

            $menu->name = trim($line['name']);
            $menu->url = empty($line['url'])?null:trim($line['url']);
            $menu->page = empty($line['page'])?null:intval($line['page']);
            $menu->parent = empty($line['parent'])?null:intval($line['parent']);

            $menu->save();
        }
        $f3->reroute("@menu_list");
    }
}
