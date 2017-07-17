<?php

namespace Controller;

class Menu {
    public function getOne($name) { //This is a special controller, as it will only be used as a filter
        $menu = new \Model\Menu();
        $menu->load(array('name=?', $name));

        if($menu->dry()) return "";

        \Base::instance()->set("menu", $menu);

        return \Preview::instance()->render("menu.html");
    }
}
