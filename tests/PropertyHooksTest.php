<?php
/**
 * Class PropertyHooksTest
 *
 * @created      30.10.2025
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2025 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace chillerlan\SettingsTest;

use chillerlan\SettingsTest\Subjects\PropertyHooksContainer;
use PHPUnit\Framework\Attributes\{RequiresPhp, Test};
use PHPUnit\Framework\TestCase;
use function serialize, unserialize;

/**
 * Tests to ensure that properties with hooks (PHP 8.4+) produce the same results as the custom get/set methods
 */
#[RequiresPhp('>= 8.4')]
final class PropertyHooksTest extends TestCase{

	#[Test]
	public function propertyHooks():void{
		$container = new PropertyHooksContainer;
		$container->test1 = 'FOO';
		$container->test2 = 'bar';
		$container->test3 = 'foobar'; // via property hook
		$container->test4 = 'foobar'; // via custom setter

		$this->assertSame('foo', $container->test1);
		$this->assertSame('BAR', $container->test2);
		$this->assertSame('3858F62230AC3C915F300C664312C63F', $container->test3);
		$this->assertSame('3858F62230AC3C915F300C664312C63F', $container->test4);
	}

	#[Test]
	public function serializable():void{
		$container = new PropertyHooksContainer([
			'test1' => 'FOO',
			'test2' => 'bar',
			'test3' => 'foobar',
			'test4' => 'foobar',
		]);

		// the object state should be retained, bypassing existing property hooks and magic get/set
		$expected = 'O:55:"chillerlan\SettingsTest\Subjects\PropertyHooksContainer":4:{s:5:"test1";'.
		            's:3:"foo";s:5:"test2";s:3:"bar";s:5:"test3";s:32:"3858f62230ac3c915f300c664312c63f";'.
		            's:5:"test4";s:32:"3858f62230ac3c915f300c664312c63f";}';

		$serialized = serialize($container);

		$this::assertSame($expected, $serialized);

		/** @var \chillerlan\SettingsTest\Subjects\PropertyHooksContainer $container */
		$container = unserialize($serialized); // object should remain in the same state

		$this::assertSame($expected, $container->serialize());

		$container = new PropertyHooksContainer;
		$container->unserialize($expected);

		$this->assertSame('foo', $container->test1);
		$this->assertSame('BAR', $container->test2);
		$this->assertSame('3858F62230AC3C915F300C664312C63F', $container->test3);
		$this->assertSame('3858F62230AC3C915F300C664312C63F', $container->test4);
	}

	#[Test]
	public function toJSON():void{
		$json = '{"test1":"FOO","test2":"bar","test3":"foobar","test4":"foobar"}';

		$container = (new PropertyHooksContainer)->fromJSON($json);

		$expected = '{"test1":"foo","test2":"BAR","test3":"3858F62230AC3C915F300C664312C63F",'.
		            '"test4":"3858F62230AC3C915F300C664312C63F"}';

		$this->assertSame($expected, $container->toJSON());
		$this->assertSame('foo', $container->test1);
		$this->assertSame('BAR', $container->test2);
		$this->assertSame('3858F62230AC3C915F300C664312C63F', $container->test3);
		$this->assertSame('3858F62230AC3C915F300C664312C63F', $container->test4);

		$container = (new PropertyHooksContainer)->fromJSON($container->toJSON());

		// the values ran through the setter/property hooks again
		$this->assertSame('9D7EB71F4781D934EEC4A8EC23762363', $container->test3);
		$this->assertSame('9D7EB71F4781D934EEC4A8EC23762363', $container->test4);
	}

}
