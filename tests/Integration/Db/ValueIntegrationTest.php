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

namespace OCA\AsthmaDiary\Tests\Integration\Db;

use OCA\AsthmaDiary\Db\Measurement;
use OCA\AsthmaDiary\Db\Value;
use OCP\AppFramework\App;
use OCP\AppFramework\Db\DoesNotExistException;
use PHPUnit\Framework\TestCase;

/**
 * @group DB
 */
class ValueIntegrationTest extends TestCase {

	private $controller;
	private $measurementMapper;
	private $measurement;
	private $valueMapper;
	private $userId = 'john';
	private $date = '2012-04-04';
	private $time = '22:00:00';
	private $value = 300;

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

		$this->valueMapper = $container->query(
			'OCA\AsthmaDiary\Db\ValueMapper'
		);

		$this->measurementMapper = $container->query(
			'OCA\AsthmaDiary\Db\MeasurementMapper'
		);

		/**
		 * First create measurement to allow value inserts
		 */
		$this->measurement = new Measurement();
		$this->measurement->setDate($this->date);
		$this->measurement->setUserId($this->userId);

		$this->measurementMapper->insert($this->measurement);
	}

	public function tearDown() {
		parent::tearDown();
		return $this->measurementMapper->delete($this->measurement);
	}

	public function testUpdate() {
		$value = new Value();
		$value->setDate($this->date);
		$value->setTime($this->time);
		$value->setValue($this->value);
		$value->setUserId($this->userId);

		$id = $this->valueMapper->insert($value)->getId();

		$updatedValue = Value::fromRow([
			'id' => $id,
			'user_id' => $this->userId,
			'date' => $this->date,
			'time' => $this->time
		]);
		$updatedValue->setValue(300);

		$result = $this->controller
			->update($id, $this->date, $this->time, 300);

		$this->assertEquals($updatedValue, $result->getData());

		$this->valueMapper->delete($result->getData());
	}

	public function testDelete() {
		$value = new Value();
		$value->setDate($this->date);
		$value->setTime($this->time);
		$value->setValue($this->value);
		$value->setUserId($this->userId);

		$insertedValue = $this->valueMapper->insert($value);
		$id = $insertedValue->getId();

		$this->valueMapper->delete($insertedValue);

		$this->expectException(DoesNotExistException::class);
		$this->valueMapper->find($id, $this->userId);
	}

}
