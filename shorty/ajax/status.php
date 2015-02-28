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
 * @file ajax/status.php
 * @brief Ajax method to modify the status of an existing shorty
 * @param string $id: Internal id of the referenced shorty
 * @param string $title: Human readable title
 * @param string $notes: Any additional information in free text form
 * @return json: success/error state indicator
 * @return json: Associative array holding the id of the shorty whose click was registered
 * @author Christian Reiner
 */

namespace OCA\Shorty;

// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
Tools::ob_control ( TRUE );

//no apps or filesystem
$RUNTIME_NOSETUPFS = true;

// Sanity checks
\OCP\JSON::callCheck ( );
\OCP\JSON::checkLoggedIn ( );
\OCP\JSON::checkAppEnabled ( 'shorty' );

try
{
	$p_id     = Type::req_argument ( 'id',     Type::ID,     TRUE );
	$p_status = Type::req_argument ( 'status', Type::STATUS, FALSE );
	$param = array
	(
		'user'   => \OCP\User::getUser ( ),
		'id'     => $p_id,
		'status' => $p_status,
	);
	$query = \OCP\DB::prepare ( Query::URL_STATUS );
	$query->execute ( $param );

	// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
	Tools::ob_control ( FALSE );
	\OCP\Util::writeLog( 'shorty', sprintf("Status change for shorty with id '%s' saved",$p_id), \OCP\Util::INFO );
	\OCP\JSON::success ( array (
		'data'    => array('id'=>$p_id),
		'level'   => 'info',
		'message' => L10n::t("Status change for shorty with id '%s' saved",$p_id) )  );
} catch ( Exception $e ) { Exception::JSONerror($e); }
