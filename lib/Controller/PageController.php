<?php
/**
 * *
 *  *
 *  * @copyright Copyright (c) 2019, Nils Griebner (nils@nils-griebner.de)
 *  *
 *  * @license GNU AGPL version 3 or any later version
 *  *
 *  * This program is free software: you can redistribute it and/or modify
 *  * it under the terms of the GNU Affero General Public License as
 *  * published by the Free Software Foundation, either version 3 of the
 *  * License, or (at your option) any later version.
 *  *
 *  * This program is distributed in the hope that it will be useful,
 *  * but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  * GNU Affero General Public License for more details.
 *  *
 *  * You should have received a copy of the GNU Affero General Public License
 *  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  *
 *
 */

namespace OCA\AsthmaDiary\Controller;

use OCP\AppFramework\Http\RedirectResponse;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCP\IURLGenerator;

class PageController extends Controller {
	private $userId;
	private $urlGenerator;

	public function __construct(
		$AppName, IRequest $request, IURLGenerator $urlGenerator, $UserId) {
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * Redirect root to measurements
	 *
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		//return new TemplateResponse('asthmadiary', 'index');
		$baseUrl = $this->urlGenerator->linkToRoute('asthmadiary.page.measurements');
		return new RedirectResponse($baseUrl);
	}

	/**
	 * Return measurements sub url
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 */
	public function measurements() {
		return new TemplateResponse('asthmadiary', 'index');
	}

	/**
	 * Return statistics sub url
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 */
	public function statistics() {
		return new TemplateResponse('asthmadiary', 'index');
	}

}
