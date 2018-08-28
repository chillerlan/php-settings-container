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

	protected $test1 = 'foo';

	protected $test2;

	protected $testConstruct;

	protected $test4;

	protected function TestOptionsTrait(){
		$this->testConstruct = 'success';
	}

}
