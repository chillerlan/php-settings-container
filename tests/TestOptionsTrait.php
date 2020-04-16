<?php
/**
 * Trait TestOptionsTrait
 *
 * @filesource   TestOptionsTrait.php
 * @created      28.08.2018
 * @package      chillerlan\SettingsTest
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\SettingsTest;

trait TestOptionsTrait{

	protected string $test1 = 'foo';

	protected ?bool $test2 = null;

	protected string $testConstruct;

	protected ?string $test4 = null;

	protected ?string $test5 = null;

	protected ?string $test6 = null;

	protected function TestOptionsTrait(){
		$this->testConstruct = 'success';
	}

	protected function set_test5($value){
		$this->test5 = $value.'_test5';
	}

	protected function get_test6(){
		return $this->test6 === null
			? 'null'
			: sha1($this->test6);
	}
}
