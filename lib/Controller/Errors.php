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

use Closure;
use OCA\AsthmaDiary\Service\NotFoundException;
use OCA\AsthmaDiary\Service\ParameterErrorException;
use OCA\AsthmaDiary\Service\ParameterValidationException;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;


trait Errors {

	/**
	 * @param Closure $callback
	 * @return DataResponse
	 */
	protected function handleNotFound(Closure $callback) {
		try {
			return new DataResponse($callback());
		} catch (NotFoundException $e) {
			return new DataResponse("Not Found!", Http::STATUS_NOT_FOUND);
		}
	}

	/**
	 * @param Closure $callback
	 * @return DataResponse
	 */
	protected function handleParameterValidation(Closure $callback) {
		try {
			return new DataResponse($callback());
		} catch (ParameterValidationException $e) {
			return new DataResponse("Parameter error!", Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @param Closure $callback
	 * @return DataResponse
	 */
	protected function handleParameteAndNotFoundException(Closure $callback) {
		try {
			return new DataResponse($callback());
		} catch (ParameterValidationException $e) {
			return new DataResponse("Parameter error!", Http::STATUS_BAD_REQUEST);
		} catch (NotFoundException $e) {
			return new DataResponse("Not Found!", Http::STATUS_NOT_FOUND);
		}
	}

}