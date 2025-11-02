<?php
/**
 * Class ExceptionTestContainer
 *
 * @created      01/11/2025
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2025 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace chillerlan\SettingsTest\Subjects;

use chillerlan\Settings\Attributes\ThrowOnInvalidProperty;
use chillerlan\Settings\SettingsContainerAbstract;

#[ThrowOnInvalidProperty(true)]
class ExceptionTestContainer extends SettingsContainerAbstract{

	protected string $accessible = 'yay';

	private string $inaccessible = 'nay';

}
