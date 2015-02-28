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
 * @file ajax/click.php
 * @brief Ajax method to register a 'click', a single hit on an existing Shorty
 * @param string $id: Internal id of a referenced shorty
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
	if (isset($_GET['id']))
	{
		$p_id  = Type::req_argument ( $_GET['id'], Type::ID, TRUE );
		$param = array (
			':id'   => $p_id,
			':time' => 'NOW()',
		);

		// record the click
		$query = \OCP\DB::prepare ( Query::URL_CLICK );
		$query->execute ( $param );

		// allow further processing by registered hooks
		$details = array ( );
		// for this we need two things: details about the Shorty AND about the request
		$query = \OCP\DB::prepare ( Query::URL_VERIFY );
		$entries = $query->execute($param)->FetchAll();
		if (  (1==count($entries))
			&&(isset($entries[0]['id']))
			&&($p_id==$entries[0]['id']) )
			$entries[0]['relay']=Tools::relayUrl ( $entries[0]['id'] );
		else
			throw new Exception ( "failed to verify clicked shorty with id '%1s'", array($p_id) );
		$details['shorty'] = $entries[0];
		// now collect some info about the request
		$details['click'] = array (
			'address' => $_SERVER['REMOTE_ADDR'],
			'host'    => $_SERVER['REMOTE_HOST'],
			'time'    => $details['shorty']['accessed'],
			'user'    => \OCP\User::getUser(),
		);
		// and off we go (IF any hooks were registered
		\OCP\Util::emitHook( '\OCA\Shorty', 'post_clickShorty', $details );

		// report result
		\OCP\Util::writeLog( 'shorty', sprintf("Registered click of shorty with id '%s'.",$p_id), \OCP\Util::DEBUG );
		\OCP\JSON::success ( array (
			'data'    => array('id'=>$p_id),
			'level'   => 'info',
			'message' => L10n::t("Click registered") ) );
	}
	else
		throw new Exception ( "request failed: missing mandatory argument 'id'" );

	// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
	Tools::ob_control ( FALSE );
} catch ( Exception $e ) { Exception::JSONerror($e); }
