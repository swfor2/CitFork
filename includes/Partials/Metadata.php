<?php
/**
 * Citizen - A responsive skin developed for the Star Citizen Wiki
 *
 * This file is part of Citizen.
 *
 * Citizen is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Citizen is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Citizen.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup Skins
 */

declare( strict_types=1 );

namespace MediaWiki\Skins\Citizen\Partials;

use Exception;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

final class Metadata extends Partial {

	/**
	 * Adds metadata to the output page
	 */
	public function addMetadata(): void {
		// Theme color
		$this->out->addMeta( 'theme-color', $this->getConfigValue( 'CitizenThemeColor' ) ?? '' );

		// Generate webapp manifest
		$this->addManifest();
	}

	/**
	 * Adds the manifest if:
	 * * Enabled in 'CitizenEnableManifest'
	 * * User has read access (i.e. not a private wiki)
	 * Manifest link will be empty if wfExpandUrl throws an exception.
	 */
	private function addManifest(): void {
		if (
			$this->getConfigValue( 'CitizenEnableManifest' ) !== true ||
			$this->getConfigValue( MainConfigNames::GroupPermissions )['*']['read'] !== true
		) {
			return;
		}

		try {
			$href = MediaWikiServices::getInstance()->getUrlUtils()
				->expand( wfAppendQuery( wfScript( 'api' ),
					[ 'action' => 'webapp-manifest' ] ), PROTO_RELATIVE );
		} catch ( Exception $e ) {
			$href = '';
		}

		$this->out->addLink( [
			'rel' => 'manifest',
			'href' => $href,
		] );
	}
}
