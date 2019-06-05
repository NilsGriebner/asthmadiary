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

namespace OCA\AsthmaDiary\Tests\Integration\Controller;

use OCA\AsthmaDiary\Db\Value;
use OCA\AsthmaDiary\Service\ParameterValidationException;
use OCA\AsthmaDiary\Service\ValueService;
use OCP\AppFramework\App;
use OCP\AppFramework\Db\DoesNotExistException;
use Test\TestCase;

/**
 * @group DB
 */
class ValueServiceIntegrationTest extends TestCase {

	private $controller;
	private $mapper;
	private $service;
	private $userId = 'john';
	private $validTime = "01:00:00";
	private $invalidTime = "251:00:00";
	private $validDate = "2012-08-05";
	private $invalidDate = "20144-05-04";
	private $validValue = 300;
	private $invaliValueHigh = 901;
	private $invaliValueLow = 0;

	public function setUp() {
		parent::setUp();
		$app = new App('asthmadiary');
		$container = $app->getContainer();

		// only replace the user id
		$container->registerService('UserId', function ($c) {
			return $this->userId;
		});

		$this->controller = $container->query(
			'OCA\AsthmaDiary\Controller\ValueController'
		);

		$this->mapper = $container->query(
			'OCA\AsthmaDiary\Db\ValueMapper'
		);

		$this->service = new ValueService($this->mapper);
	}

	public function testCreateDateMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			null, $this->validTime, $this->validValue, $this->userId);
	}

	public function testCreateDateWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			0, $this->validTime, $this->validValue, $this->userId);
	}

	public function testCreateDateInvalid() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->invalidDate, $this->validTime, $this->validValue, $this->userId);
	}

	public function testCreateTimeMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, null, $this->validValue, $this->userId);
	}

	public function testCreateTimeWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, 0, $this->validValue, $this->userId);
	}

	public function testCreateTimeInvalid() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, $this->invalidTime, $this->validValue, $this->userId);
	}

	public function testCreateValueMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, $this->validTime, null, $this->userId);
	}

	public function testCreateValueWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, $this->validTime, $this->userId, $this->userId);
	}

	public function testCreateValueInvalidLow() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, $this->validTime, $this->invaliValueLow, $this->userId);
	}

	public function testCreateValueInvalidHigh() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, $this->validTime, $this->invaliValueHigh, $this->userId);
	}

	public function testCreateUserIdMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, $this->validTime, $this->validValue, null);
	}

	public function testCreateUserIdWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->validDate, $this->validTime, $this->validValue, 0);
	}

	/**
	 * Update function tests
	 */
	public function testUpdateId() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(null, null, $this->validTime, $this->validValue, $this->userId);
	}

	public function testUpdateIdWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			"test", null, $this->validTime, $this->validValue, $this->userId);
	}

	public function testUpdateDateMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, null, $this->validTime, $this->validValue, $this->userId);
	}

	public function testUpdateDateWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, 0, $this->validTime, $this->validValue, $this->userId);
	}

	public function testUpateDateInvalid() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->invalidDate, $this->validTime, $this->validValue, $this->userId);
	}

	public function testUpdateTimeMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			0, $this->validDate, null, $this->validValue, $this->userId);
	}

	public function testUpdateTimeWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->validDate, 0, $this->validValue, $this->userId);
	}

	public function testUpdateTimeInvalid() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->validDate, $this->invalidTime, $this->validValue, $this->userId);
	}

	public function testUpdateValueMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->validDate, $this->validTime, null, $this->userId);
	}

	public function testupdateValueWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->validDate, $this->validTime, "test", $this->userId);
	}

	public function testUpdateValueInvalidLow() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->validDate, $this->validTime, $this->invaliValueLow, $this->userId);
	}

	public function testUpdateValueInvalidHigh() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->validDate, $this->validTime, $this->invaliValueHigh, $this->userId);
	}

	public function testUpdateUserIdMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			0, $this->validDate, $this->validTime, $this->validValue, null);
	}

	public function testUpdateUserIdWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			0, $this->validDate, $this->validTime, $this->validValue, 1);
	}
}
