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

use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCA\AsthmaDiary\Service\ValueService;

class ValueController extends Controller {

	private $service;
	private $userId;

	use Errors;

	public function __construct($AppName, IRequest $request, ValueService $valueService, $userId) {
		parent::__construct($AppName, $request);
		$this->valueService = $valueService;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $from
	 * @param string $to
	 */
	public function index($from, $to) {
		return new DataResponse(
			$this->valueService->findAll($from, $to, $this->userId));
	}

	/**
	 * @NoAdminRequiredg
	 * @NoCSRFRequired
	 *
	 * @param int $id
	 */
	public function show($id) {
		return $this->handleNotFound(function () use ($id) {
			return $this->valueService->find($id, $this->userId);
		});
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $date
	 * @param string $time
	 * @param string $value
	 */
	public function create($date, $time, $value) {
		return $this->handleParameteAndNotFoundException(
			function () use ($date, $time, $value) {
				return $this->valueService
					->create($date, $time, $value, $this->userId);
			});
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $id
	 * @param string $date
	 * @param string $time
	 * @param string $value
	 */
	public function update($id, $date, $time, $value) {
		return $this->handleParameteAndNotFoundException(
			function () use ($id, $date, $time, $value) {
				return $this->valueService
					->update($id, $date, $time, $value, $this->userId);
			});
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $id
	 */
	public function destroy($id) {
		return $this->handleNotFound(function () use ($id) {
			return $this->valueService
				->delete($id, $this->userId);
		});
	}

}