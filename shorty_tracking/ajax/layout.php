<?php
/**
* @package shorty-tracking an ownCloud url shortener plugin addition
* @category internet
* @author Christian Reiner
* @copyright 2012-2015 Christian Reiner <foss@christian-reiner.info>
* @license GNU AFFERO GENERAL PUBLIC LICENSE (AGPL)
* @link information http://apps.owncloud.com/content/show.php/Shorty+Tracking?content=152473
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
 * @file ajax/layout.php
 * @brief Ajax method to retrieve the basic html layout for the list dialog
 * @param id (string) Alphanumerical id of the Shorty
 * @param title (string) Titel of the Shorty
 * @returns (json) success/error state indicator
 * @returns (json) Associative array of click records matching the Shorty
 * @returns (json) Human readable message
 * @author Christian Reiner
 */

namespace OCA\Shorty\Tracking;

// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
Tools::ob_control ( TRUE );

//no apps or filesystem
$RUNTIME_NOSETUPFS = TRUE;
$RUNTIME_NOAPPS = TRUE;

// Sanity checks
\OCP\JSON::callCheck ( );
\OCP\JSON::checkLoggedIn ( );
\OCP\JSON::checkAppEnabled ( 'shorty' );
\OCP\JSON::checkAppEnabled ( 'shorty_tracking' );

try
{
	// which dialog has been requested
	$p_dialog = Type::req_argument ( 'dialog', Type::ID, TRUE );
	// render dialog layout
	switch ($p_dialog)
	{
		case 'list':
			$tmpl = new \OCP\Template( 'shorty_tracking', 'tmpl_trc_dlg_list' );
			break;

		case 'click':
			$tmpl = new \OCP\Template( 'shorty_tracking', 'tmpl_trc_dlg_click' );
			break;

		default:
			throw new Exception ( 'No such dialog defined: %s', $p_dialog );
	} // switch
	// available status options (required for select filter in toolbox)
	$shorty_result['']=sprintf('- %s -',L10n::t("all"));
	foreach ( Type::$RESULT as $result )
		$shorty_result[$result] = L10n::t($result);
	$tmpl->assign ( 'shorty-result', $shorty_result );

	// swallow any accidential output generated by php notices and stuff to preserve a clean JSON reply structure
	Tools::ob_control ( FALSE );
	\OCP\Util::writeLog( 'shorty_tracking', sprintf("Generated dialog layout '%s'",$p_dialog), \OCP\Util::INFO );
	\OCP\JSON::success ( array ( 'layout'  => $tmpl->fetchPage(), ) );
} catch ( Exception $e ) { Exception::JSONerror($e); }
