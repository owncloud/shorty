<?php
/**
* @package shorty an ownCloud url shortener plugin
* @category internet
* @author Christian Reiner
* @copyright 2011-2015 Christian Reiner <foss@christian-reiner.info>
* @license GNU Affero General Public license (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty?content=150401
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
 * @file ajax/settings.php
 * @brief Ajax method to store one or more system settings  (plugin settings)
 * @param string $backend-static-base: Url to use as a base when the static backend is active (plugins default, may be overridden by user preference)
 * @return json: success/error state indicator
 * @return json: Associative array holding the stored values by their key
 * @return json: Human readable message describing the result
 * @author Christian Reiner
 */

namespace OCA\Shorty;

// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
Tools::ob_control ( TRUE );

//no apps or filesystem
$RUNTIME_NOSETUPFS = true;

// Sanity checks
\OCP\JSON::callCheck ( );
\OCP\JSON::checkAdminUser ( );
\OCP\JSON::checkAppEnabled ( 'shorty' );

try
{
	switch ( $_SERVER['REQUEST_METHOD'] )
	{
		case 'POST':
			$data = array();
			// detect provided settings
			foreach (array_keys($_POST) as $key) {
				// acept only known preference keys
				if ( isset(Type::$SETTING[$key]) )
				{
					$type = Type::$SETTING[$key];
					// for correct syntax, or an empty string (to erase a value)
					$data[$key] = Type::req_argument ( $key, $type, FALSE );
					$data[$key] = $data[$key] ? $data[$key] : '';
				}
			} // foreach
			// store settings one by one
			foreach ( $data as $key=>$val )
			{
				\OCP\Config::setAppValue( 'shorty', $key, $val );
			}
			// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
			Tools::ob_control ( FALSE );
			\OCP\Util::writeLog( 'shorty', sprintf("Set setting(s) '%s'",implode(',',array_keys($data))), \OCP\Util::DEBUG );
			\OCP\JSON::success ( array (
				'data'    => $data,
				'level'   => 'debug',
 				'message' => L10n::t("Set setting(s) '%s'",implode(',',array_keys($data)))
			) );
			break;

		case 'GET':
			$data = array();
			// detect requested settings
			foreach (array_keys($_GET) as $key)
			{
				// acept only known preference keys
				if ( isset(Type::$SETTING[$key]) )
				{
					$type = Type::$SETTING[$key];
					$data[$key] = \OCP\Config::getAppValue( 'shorty', $key );
					// morph value into an explicit type
					switch ($type)
					{
						default:
							settype ( $data[$key], 'string' );
							break;

						case Type::INTEGER:
						case Type::TIMESTAMP:
							settype ( $data[$key], 'integer' );
							break;

						case Type::FLOAT:
							settype ( $data[$key], 'float' );
							break;
					} // switch
				}
			} // foreach
			// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
			Tools::ob_control ( FALSE );
			\OCP\Util::writeLog( 'shorty', sprintf("Get setting(s) '%s'",implode(',',array_keys($data))), \OCP\Util::DEBUG );
			\OCP\JSON::success ( array (
				'data'    => $data,
				'level'   => 'debug',
				'message' => L10n::t("Get setting(s) '%s'",implode(',',array_keys($data)))
			) );
			break;

		default:
			throw new Exception ( "unexpected request method '%s'", $_SERVER['REQUEST_METHOD'] );
	} // switch

} catch ( Exception $e ) { Exception::JSONerror($e); }
