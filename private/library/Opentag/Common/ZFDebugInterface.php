<?php
namespace Opentag\Common;

use \ZFDebug_Controller_Plugin_Debug_Plugin_Interface as ZFDebugPluginInterface
    ;

/**
 * Description of ZFDebugInterface
 *
 * @author mrhelly
 */
interface ZFDebugInterface extends ZFDebugPluginInterface {

//    /**
//     * Has to return html code for the menu tab
//     *
//     * @return string
//     */
//    public function getTab();
//
//    /**
//     * Has to return html code for the content panel
//     *
//     * @return string
//     */
//    public function getPanel();
//
//    /**
//     * Has to return a unique identifier for the specific plugin
//     *
//     * @return string
//     */
//    public function getIdentifier();
//
//
//    /**
//     * Return the path to an icon
//     *
//     * @return string
//     */
//    public function getIconData();

    public function getConnection();

    public function getErrorLog();
    
//    getException();
}
