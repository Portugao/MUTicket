<?php
/**
 * MUTicket.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUTicket
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Feb 02 13:43:18 CET 2012.
 */


/**
 * Module bootstrap called when module is first initialised at runtime.
 *
 * This is only called once, and only if the core has reason to initialise this module, usually
 * to dispatch a controller request or API.
 *
 * For example you can register additional AutoLoaders with ZLoader::addAutoloader($namespace, $path)
 * whereby $namespace is the first part of the PEAR class name
 * and $path is the path to the containing folder.
 */
// initialise doctrine extension listeners
$helper = ServiceUtil::getService('doctrine_extensions');
$helper->getListener('timestampable');
$helper->getListener('standardfields');

