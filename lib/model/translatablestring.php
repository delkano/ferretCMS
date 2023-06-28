<?php
namespace Model;

/**
 * This model defines a translatable string.
 * It is identified by a combo identifier+lang
 */

class TranslatableString extends \DB\Cortex {
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
            'string' => array(
                'type' => 'VARCHAR256',
                'nullable' => false
            )
        ),
        $db = 'DB',
        $fluid = true,
        $table = 'string';
}
