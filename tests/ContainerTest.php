<?php
/**
 * Class ContainerTraitTest
 *
 * @filesource   ContainerTraitTest.php
 * @created      28.08.2018
 * @package      chillerlan\SettingsTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\SettingsTest;

use PHPUnit\Framework\TestCase;

class ContainerTraitTest extends TestCase{

	public function testConstruct(){
		$container = new TestContainer([
			'test1' => 'test1',
			'test2' => 'test2',
			'test3' => 'test3',
			'test4' => 'test4',
		]);

		$this->assertSame('test1', $container->test1);
		$this->assertSame('test2', $container->test2);
		$this->assertNull($container->test3);
		$this->assertSame('test4', $container->test4);

		$this->assertSame('success', $container->testConstruct);
	}

	public function testGet(){
		$container = new TestContainer;

		$this->assertSame('foo', $container->test1);
		$this->assertNull($container->test2);
		$this->assertNull($container->test3);
		$this->assertNull($container->test4);
		$this->assertNull($container->foo);

		// isset test
		$this->assertTrue(isset($container->test1));
		$this->assertFalse(isset($container->test2));
		$this->assertFalse(isset($container->test3));
		$this->assertFalse(isset($container->test4));
		$this->assertFalse(isset($container->foo));
	}

	public function testSet(){
		$container = new TestContainer;
		$container->test1 = 'bar';
		$container->test2 = 'what';
		$container->test3 = 'nope';

		$this->assertSame('bar', $container->test1);
		$this->assertSame('what', $container->test2);
		$this->assertNull($container->test3);

		// unset
		unset($container->test1);
		$this->assertFalse(isset($container->test1));
	}

	public function testToArray(){
		$container = new TestContainer(['test1' => 'no', 'test2' => true, 'testConstruct' => 'success']);

		$this->assertSame(['test1' => 'no', 'test2' => true, 'testConstruct' => 'success', 'test4' => null], $container->toArray());
	}

	public function testToJSON(){
		$container = (new TestContainer)->fromJSON('{"test1":"no","test2":true,"testConstruct":"success"}');

		$expected  = '{"test1":"no","test2":true,"testConstruct":"success","test4":null}';

		$this->assertSame($expected, $container->toJSON());
		$this->assertSame($expected, (string)$container);
	}

}
