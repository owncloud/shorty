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
 * @file js/settings.js
 * @brief Client side activity initialization for the system settings dialog
 * @description
 * This script takes care if initializing the client side reactions to events
 * during an opened (system) settings dialog. Shorty follows the paradigm of
 * change-and-store, so all inputs and changes are stored right away, no
 * specific 'save' button has to be used, nor does it exist.
 * @author Christian Reiner
 */

$(document).ready(function(){
	// store backend selection upon change
	$('#shorty-backend-default').bind('change',function(e){
		// save setting
		$.when(
			OC.Shorty.Action.Setting.set($(e.currentTarget).serialize())
		).fail(function(response){
			OC.Notification.show(response.message);
		})
		return false;
	});
	// initialize by triggering an initial verification and update of the example url
	if ($('#shorty-backend-static-base').val().length) {
		$('#shorty-backend-example').text($('#shorty-backend-static-base').val()+'<shorty id>');
		OC.Shorty.Action.Verification.verify();
	}
	// backend 'static': verify configuration when changed
	$('#shorty-backend-static-base').bind('keyup input',function(event){
		event.preventDefault();
		// modify example
		$('#shorty-backend-example').text($('#shorty-backend-static-base').val()+'<shorty id>');
		// verify setting
		OC.Shorty.Action.Verification.verify();
	});
	// store setting
	$('#shorty-backend-static-base').focusout(function(){
		if (	($('#shorty-backend-static-base').val().length)
				&&($('#shorty-backend-static-base').hasClass('valid')) ){
			OC.Shorty.Action.Setting.set($('#shorty-backend-static-base').serialize())
		} else {
			OC.Shorty.Action.Setting.set('backend-static-base=');
		}
	});
});
