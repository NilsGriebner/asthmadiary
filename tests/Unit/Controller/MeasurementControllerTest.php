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

namespace OCA\AsthmaDiary\Tests\Unit\Controller;

use OCA\AsthmaDiary\Controller\MeasurementController;
use OCA\AsthmaDiary\Db\Measurement;
use PHPUnit_Framework_TestCase;


class MeasurementControllerTest extends PHPUnit_Framework_TestCase {
	protected $request;
	protected $controller;
	protected $service;
	protected $userId = 'john';
	protected $testDate = "1970-01-01";
	protected $expectedValue;

	public function setUp() {
		$this->request = $this->getMockBuilder('OCP\IRequest')->getMock();
		$this->service = $this->getMockBuilder('OCA\AsthmaDiary\Service\MeasurementService')
			->disableOriginalConstructor()
			->getMock();
		$this->controller = new MeasurementController(
			'asthmadiary', $this->request, $this->service, $this->userId
		);

		$this->expectedValue = new Measurement();
		$this->expectedValue->setDate($this->testDate);
	}

	public function testIndex() {
		$this->service->expects($this->once())
			->method('findAll')
			->with($this->equalTo($this->testDate),
				$this->equalTo($this->testDate),
				$this->equalTo($this->userId))
			->will($this->returnValue($this->expectedValue));

		$result = $this->controller->index($this->testDate, $this->testDate);
		$this->assertEquals($this->expectedValue, $result->getData());
	}

	public function testShow() {
		$this->service->expects($this->once())
			->method('find')
			->with($this->equalTo(3),
				$this->equalTo($this->userId))
			->will($this->returnValue($this->expectedValue));

		$result = $this->controller->show(3);
		$this->assertEquals($this->expectedValue, $result->getData());
	}

	public function testCreate() {
		$this->service->expects($this->once())
			->method('create')
			->with($this->equalTo($this->userId),
				$this->equalTo($this->testDate),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""))
			->will($this->returnValue($this->expectedValue));

		$result = $this->controller->create(
			$this->testDate, "", "", "", "", "", "", "", "", "", "", "", "");
		$this->assertEquals($this->expectedValue, $result->getData());
	}

	public function testUpdate() {
		$this->service->expects($this->once())
			->method('update')
			->with($this->equalTo(3),
				$this->equalTo($this->userId),
				$this->equalTo($this->testDate),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""),
				$this->equalTo(""))
			->will($this->returnValue($this->expectedValue));

		$result = $this->controller->update(
			3, $this->testDate, "", "", "", "", "", "", "", "", "", "", "", "");
		$this->assertEquals($this->expectedValue, $result->getData());
	}

	public function testDelete() {
		$this->service->expects($this->once())
			->method('delete')
			->with($this->equalTo(3),
				$this->equalTo($this->userId))
			->will($this->returnValue($this->expectedValue));

		$result = $this->controller->destroy(3, $this->userId);
		$this->assertEquals($this->expectedValue, $result->getData());
	}

}