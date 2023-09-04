<?php
namespace Controller;

class Plugin{
    public function getList($f3) {
        $dir = dir($f3->PLUGINS);
        $list = [];
        while(false != ($entry = $dir->read())) {
            if($entry[0] == '.') continue;
            $name = "\\Plugin\\".$entry."\\".$entry;
            $class = new $name;
            $instance = new \Model\Plugin;
            $instance->load(["path=?", $entry]);

            $list[] = [
                'path' => $entry,
                'name' => $class->name,
                'description' => $class->description,
                'author' => $class->author,
                'version' => $class->version,
                'image' => $class->version,
                'active' => $instance->dry()?false:$instance->active
            ];
        }

        $f3->set('plugins', $list);
        $f3->set("site.title", "Plugin List");
        $f3->set('site.subtemplate', "pluginList");
        $f3->set('site.template', "config");

        echo \Template::instance()->render("layout.html');
    }

    public function toggle($f3, $params) {
        $path = $params['path'];
        $instance = new \Model\Plugin;
        $instance->load(["path=?", $path]);
        if($instance->dry()) {
            $instance->name = $path;
            $instance->path = $path;
            $instance->active = true;
        } else {
            $instance->active = !$instance->active;
        }
        $instance->save();
        $f3->reroute("plugins");
    }
}
