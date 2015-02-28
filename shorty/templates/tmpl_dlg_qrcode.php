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
 * @file templates/tmpl_dlg_qrcode.php
 * Dialog popup to visualize and offer an url as a QRCode (2D barcode)
 * @access public
 * @author Christian Reiner
 */

namespace OCA\Shorty;
?>

<!-- begin of qrcode dialog -->
<div id="dialog-qrcode" class="shorty-popup" data-title="<?php p(L10n::t("Shorty as QRCode")) ?>">
	<fieldset>
		<div class='qrcode-img'>
			<div class="usage-explanation">
				<?php p(L10n::t("This 2d barcode encodes the url pointing to this Shorty")); ?>.
				<?php p(L10n::t("Use it in web pages by referencing or embedding or simply print or download it for offline usage")); ?>!
			</div>
			<div style="text-align:center;">
				<img class="usage-qrcode" alt="<?php p(L10n::t("QRCode")); ?>"
					src="<?php p(\OCP\Util::imagePath('shorty','loading-disk.gif')); ?>" >
			</div>
		</div>
		<div class='qrcode-ref' style="display:none;">
			<div class="usage-explanation">
				<?php p(L10n::t("This is the url referencing the QRCode shown before")); ?>.
				<?php p(L10n::t("Embed the QRCode as an image into some web page using this url")); ?>.
			</div>
			<input class="usage-qrcode" readonly="true">
			<div class="usage-instruction">
				<?php p(L10n::t("Copy to clipboard")); ?>:<span class="usage-token"><?php p(L10n::t("Ctrl-C")); ?></span>
				<br>
				<?php p(L10n::t("Paste to embed elsewhere")); ?>:<span class="usage-token"><?php p(L10n::t("Ctrl-V")); ?></span>
			</div>
			<hr>
			<div class="usage-explanation">
				<?php p(L10n::t("Alternatively the image can be downloaded for printout or storage")); ?>.
				<?php p(L10n::t("That image can be used when writing documents or setting up web sites")); ?>:
			</div>
		</div>
	</fieldset>
</div>
<!-- end of qrcode dialog -->
