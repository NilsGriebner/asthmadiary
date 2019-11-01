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

use PHPUnit\Framework\TestCase;

use OCP\AppFramework\Http\RedirectResponse;
use OCP\IURLGenerator;

use OCA\AsthmaDiary\Controller\PageController;


class PageControllerTest extends TestCase {
	private $controller;
	private $userId = 'john';

	public function setUp() {
		$request = $this->getMockBuilder('OCP\IRequest')->getMock();
		$urlGenerator = $this->createMock(IURLGenerator::class);

		$this->controller = new PageController(
			'asthmadiary', $request, $urlGenerator, $this->userId
		);
	}

	public function testIndex() {
		$result = $this->controller->index();

		$this->assertTrue($result instanceof RedirectResponse);
	}

}
