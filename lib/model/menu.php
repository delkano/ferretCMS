<?php
namespace Model;

class Menu extends \DB\Cortex {
    protected
        $fieldConf = array(
            'name' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'page' => array(
                'belongs-to-one' => '\Model\Menu',
                'nullable' => true
            ),
            'url' => array(
                'type' => 'TEXT',
                'nullable' => false
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'menu';
}

