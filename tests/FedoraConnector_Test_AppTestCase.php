<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4; */

/**
 * FedoraConnector Omeka plugin allows users to reuse content managed in
 * institutional repositories in their Omeka repositories.
 *
 * The FedoraConnector plugin provides methods to generate calls against Fedora-
 * based content disemminators. Unlike traditional ingestion techniques, this
 * plugin provides a facade to Fedora-Commons repositories and records pointers
 * to the "real" objects rather than creating new physical copies. This will
 * help ensure longer-term durability of the content streams, as well as allow
 * you to pull from multiple institutions with open Fedora-Commons
 * respositories.
 *
 * PHP version 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package     omeka
 * @subpackage  fedoraconnector
 * @author      Scholars' Lab <>
 * @author      Ethan Gruber <ewg4x@virginia.edu>
 * @author      Adam Soroka <ajs6f@virginia.edu>
 * @author      Wayne Graham <wayne.graham@virginia.edu>
 * @author      Eric Rochester <err8n@virginia.edu>
 * @copyright   2010 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache 2 License
 * @version     $Id$
 * @link        http://omeka.org/add-ons/plugins/FedoraConnector/
 * @tutorial    tutorials/omeka/FedoraConnector.pkg
 */

/**
 * This class sets up the system for testing this plugin.
 *
 * This borrows from the SimplePages plugin rather extensively.
 */
class FedoraConnector_Test_AppTestCase extends Omeka_Test_AppTestCase
{
    const PLUGIN_NAME = 'FedoraConnector';

    public function setUp() {
        parent::setUp();

        // Authenticate and set the current user.
        $this->user = $this->db->getTable('User')->find(1);
        $this->_authenticateUser($this->user);

        // Add the plugin hooks and filters (including the install hook).
        $pluginBroker = get_plugin_broker();
        $this->_addPluginHooksAndFilters($pluginBroker, self::PLUGIN_NAME);

        $pluginHelper = new Omeka_Test_Helper_Plugin();
        $pluginHelper->setUp(self::PLUGIN_NAME);
    }

    public function _addPluginHooksAndFilters($pluginBroker, $pluginName) {
        // Set the current plugin so the add_plugin_hook function works.
        $pluginBroker->setCurrentPluginDirName($pluginName);

        // Add plugin hooks.
        add_plugin_hook('install', 'fedora_connector_install');
        add_plugin_hook('uninstall', 'fedora_connector_uninstall');
        add_plugin_hook('before_delete_item', 'fedora_connector_before_delete_item');
        add_plugin_hook('admin_theme_header', 'fedora_connector_admin_header');
        add_plugin_hook('define_acl', 'fedora_connector_define_acl');
        add_plugin_hook('config_form', 'fedora_connector_config_form');
        add_plugin_hook('config', 'fedora_connector_config');

        // Add filters.
        add_filter('admin_items_form_tabs', 'fedora_connector_item_form_tabs');
        add_filter('admin_navigation_main', 'fedora_connector_admin_navigation');
    }

}


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
