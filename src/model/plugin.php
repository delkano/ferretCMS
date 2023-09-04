<?php
namespace Model;

class Plugin extends \DB\Cortex {
    protected
        $fieldConf = array(
            'name' => array(
                'type' => 'VARCHAR256',
                'default' => '',
                'nullable' => false
            ),
            'path' => array(
                'type' => 'VARCHAR256',
                'default' => '',
                'unique' => true,
                'nullable' => false
            ),
            'active' => array(
                'type' => 'BOOLEAN',
                'default' => 0,
                'nullable' => false
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'plugin';
}


