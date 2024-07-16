<?php
/**
 * Class ContainerTraitTest
 *
 * @created      28.08.2018
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace chillerlan\SettingsTest;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException, JsonException, TypeError;
use function json_encode, serialize, sha1, unserialize;

class ContainerTest extends TestCase{

	public function testConstruct():void{
		$container = new TestContainer([
			'test1' => 'test1',
			'test2' => true,
			'test3' => 'test3',
			'test4' => 'test4',
		]);

		$this::assertSame('test1', $container->test1);
		$this::assertSame(true, $container->test2);
		$this::assertNull($container->test3);
		$this::assertSame('test4', $container->test4);

		$this::assertSame('success', $container->testConstruct);
	}

	public function testGet():void{
		$container = new TestContainer;

		$this::assertSame('foo', $container->test1);
		$this::assertNull($container->test2);
		$this::assertNull($container->test3);
		$this::assertNull($container->test4);
		$this::assertNull($container->foo);

		// isset test
		$this::assertTrue(isset($container->test1));
		$this::assertFalse(isset($container->test2));
		$this::assertFalse(isset($container->test3));
		$this::assertFalse(isset($container->test4));
		$this::assertFalse(isset($container->foo));

		// custom getter
		$container->test6 = 'foo';
		$this::assertSame(sha1('foo'), $container->test6);
		// nullable/isset test
		$container->test6 = null;
		$this::assertFalse(isset($container->test6));
		$this::assertSame('null', $container->test6);
	}

	public function testSet():void{
		$container = new TestContainer;
		$container->test1 = 'bar';
		$container->test2 = false;
		$container->test3 = 'nope';

		$this::assertSame('bar', $container->test1);
		$this::assertSame(false, $container->test2);
		$this::assertNull($container->test3);

		// unset
		unset($container->test1);
		$this::assertFalse(isset($container->test1));

		// custom setter
		$container->test5 = 'bar';
		$this::assertSame('bar_test5', $container->test5);
	}

	public function testToArray():void{
		$container = new TestContainer([
			'test1'         => 'no',
			'test2'         => true,
			'testConstruct' => 'success',
		]);

		$this::assertSame([
			'test1'         => 'no',
			'test2'         => true,
			'testConstruct' => 'success',
			'test4'         => null,
			'test5'         => '',
			'test6'         => 'null', // value ran through the getter
		], $container->toArray());

		$container->fromIterable($container->toArray());

		$this::assertSame('_test5', $container->test5); // value ran through the setter
	}

	public function testToJSON():void{
		$container = (new TestContainer)->fromJSON('{"test1":"no","test2":true,"testConstruct":"success"}');

		$expected  = '{"test1":"no","test2":true,"testConstruct":"success","test4":null,"test5":"","test6":"null"}';

		$this::assertSame($expected, $container->toJSON());
		$this::assertSame($expected, (string)$container);
		$this::assertSame($expected, json_encode($container)); // JsonSerializable

		$container->fromJSON($expected);

		$this::assertSame('_test5', $container->test5);
	}

	public function testFromJsonException():void{
		$this->expectException(JsonException::class);
		(new TestContainer)->fromJSON('-');

	}

	public function testFromJsonTypeError():void{
		$this->expectException(TypeError::class);
		(new TestContainer)->fromJSON('2');
	}

	public function testSerializable():void{
		$container = new TestContainer([
			'test1'         => 'no',
			'test2'         => true,
			'testConstruct' => 'success',
		]);

		/** @var \chillerlan\SettingsTest\TestContainer $container */
		$container = unserialize(serialize($container)); // object should remain in the same state

		// serialize will return the object in its current state including private properties
		$expected = 'O:37:"chillerlan\SettingsTest\TestContainer":7:{s:5:"test3";s:4:"what";s:5:"test1";s:2:"no";'.
		            's:5:"test2";b:1;s:13:"testConstruct";s:7:"success";s:5:"test4";N;s:5:"test5";s:0:"";s:5:"test6";N;}';

		$this::assertSame($expected, $container->serialize());
		$this::assertSame($expected, serialize($container));

		$container->unserialize($expected);

		$this::assertSame('', $container->test5);
	}

	public function testUnserializeInvalidDataException():void{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('The given serialized string is invalid');

		(new TestContainer)->unserialize('foo');
	}

	public function testUnserializeInvalidObjectException():void{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('The unserialized object does not match the class of this container');

		(new TestContainer)->unserialize('O:8:"stdClass":0:{}');
	}
}
