<?php
namespace Model;

class Page extends \DB\Cortex {
    protected
        $fieldConf = array(
            'slug' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
             'title' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'content' => array(
                'type' => 'TEXT',
                'nullable' => false
            ),
            'categories' => array(
                'type' => 'JSON'
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'page';
}

