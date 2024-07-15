<?php
/**
 * Trait TestOptionsTrait
 *
 * @created      28.08.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace chillerlan\SettingsTest;

use function sha1;

/**
 * @property string      $test1
 * @property bool|null   $test2
 * @property string      $testConstruct
 * @property string|null $test4
 * @property string      $test5
 * @property string|null $test6
 */
trait TestOptionsTrait{

	protected string $test1 = 'foo';

	protected bool|null $test2 = null;

	protected string $testConstruct;

	protected string|null $test4 = null;

	protected string $test5 = '';

	protected string|null $test6 = null;

	protected function TestOptionsTrait():void{
		$this->testConstruct = 'success';
	}

	protected function set_test5(string $value):void{
		$this->test5 = $value.'_test5';
	}

	protected function get_test6():string{
		return $this->test6 === null
			? 'null'
			: sha1($this->test6);
	}
}
