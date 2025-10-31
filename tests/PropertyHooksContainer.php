<?php
/**
 * Class PropertyHooksContainer
 *
 * @created      30.10.2025
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2025 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace chillerlan\SettingsTest;

use chillerlan\Settings\SettingsContainerAbstract;
use function md5;
use function strtolower;
use function strtoupper;

class PropertyHooksContainer extends SettingsContainerAbstract{

	// note that the magic get/set will only be called if the property is protected
	protected string $test1 = '' {
		set => strtolower($value); // phpcs:ignore
	}

	protected string $test2 = '' {
		get => strtoupper($this->test2); // phpcs:ignore
	}

	protected string $test3 = '' {
		set => md5($value); // phpcs:ignore
		get => strtoupper($this->test3); // phpcs:ignore
	}

	protected string $test4 = '';

	// an associated custom method will not be called from the magic get/set when a property hook exists
	protected function set_test1(string $value):void{
		$this->test1 = 'nope';
	}

	protected function set_test4(string $value):void{
		$this->test4 = md5($value);
	}

	protected function get_test4():string{
		return strtoupper($this->test4);
	}

}
