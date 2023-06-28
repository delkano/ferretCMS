<?php
namespace Plugin;

/**
 * The mother class for plugins, for which all inherit.
 * It defines a series of functions that are used to load and use the plugin
 * This class also works as a documentation of sorts.
 */

class PluginBase {
    public function info() {
        return array(
            "name" => "",
            "description" => "",
            "author" => ""
        );
    }

    /**
     * When the plugin is loaded, this method is called.
     * It should do anything the plugin needs to work, like
     * Database table creation and so.
     * @return an array with data about the plugin
     */
    public function setup() {
        return array(
            "config" => true,
            "filter" => 'pluginbase'
        );
    }

    /**
     * Loads up a config form for the plugin.
     */
    public function config() {
        return "";
    }

    // TODO: Rename this, find a better name for the method that is called when the filter finds its name
    public function filter() {
        return "";
    }
}
