<?php
namespace Model;

/**
 * This model defines a translatable text.
 * It is identified by a combo identifier+lang
 */

class TranslatableText extends \DB\Cortex {
    protected
        $fieldConf = array(
            'identifier' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            ),
            'lang' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            )
,
            'text' => array(
                'type' => 'TEXT',
                'nullable' => false
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'text';
}
