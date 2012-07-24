<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
* @link repository https://svn.christian-reiner.info/svn/app/oc/shorty
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the license, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * @file settings.php
 * This plugins system settings dialog
 * The dialog will be included in the general framework of the system settings page
 * @access public
 * @author Christian Reiner
 */

$OC_version = OCP\Util::getVersion();
OCP\Util::addStyle  ( '3rdparty', 'chosen/chosen' );
// OCP\Util::addStyle  ( 'shorty',   'shorty' );
OCP\Util::addStyle  ( 'shorty',   (5<=$OC_version[0]||(4==$OC_version[0]&&80<=$OC_version[1]))?'shorty-oc5':'shorty-oc4' );
OCP\Util::addStyle  ( 'shorty',   'settings' );

OCP\Util::addScript ( '3rdparty', 'chosen/chosen.jquery.min' );
OCP\Util::addScript ( 'shorty',   'shorty' );
OCP\Util::addScript ( 'shorty',   'settings' );
if ( OC_Log::DEBUG==OC_Config::getValue( "loglevel", OC_Log::WARN ) )
  OCP\Util::addScript ( 'shorty',  'debug' );


// fetch template
$tmpl = new OCP\Template ( 'shorty', 'tmpl_settings' );
// inflate template
$tmpl->assign ( 'backend-static-base', OCP\Config::getAppValue('shorty','backend-static-base','') );
// render template
return $tmpl->fetchPage ( );
?>