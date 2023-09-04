<?php
namespace Model;

class Menu extends \DB\Cortex {
    protected
        $fieldConf = array(
            'name' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'parent' => array(
                'belongs-to-one' => '\Model\Menu',
                'nullable' => true
            ),
            'children' => array(
                'has-many' => array('\Model\Menu', 'parent')
            ),
            'page' => array(
                'belongs-to-one' => '\Model\Page',
                'nullable' => true
            ),
            'url' => array(
                'type' => 'TEXT',
                'nullable' => true
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'menu';
}

