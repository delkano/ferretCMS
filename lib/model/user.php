<?php
namespace Model;

class User extends \DB\Cortex {
    protected
        $fieldConf = array(
            'username' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'password' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'name' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'email' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'role' => array(
                'type' => 'VARCHAR256',
                'default' => 'user' 
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'user';
}

