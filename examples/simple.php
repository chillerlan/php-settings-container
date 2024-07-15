<?php
/**
 * @created      28.08.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

use chillerlan\Settings\SettingsContainerAbstract;

require_once __DIR__.'/../vendor/autoload.php';

/**
 * @property string $foo
 * @property string $bar
 */
class MyContainer extends SettingsContainerAbstract{
	protected string $foo;
	protected string $bar;
}

$container = new MyContainer(['foo' => 'what']);
$container->bar = 'foo';

var_dump($container->toJSON()); // -> {"foo":"what","bar":"foo"}

// non-existing properties will be ignored:
/** @phpstan-ignore-next-line */
$container->nope = 'what';

var_dump($container->nope); // -> NULL
