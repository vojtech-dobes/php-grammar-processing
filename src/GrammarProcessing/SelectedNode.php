<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


// phpcs:ignore SlevomatCodingStandard.Classes.DisallowMultiPropertyDefinition.DisallowedMultiPropertyDefinition -- until hooks are properly supported
final class SelectedNode implements Node
{

	public string $name { // phpcs:disable SlevomatCodingStandard.Classes.DisallowMultiPropertyDefinition.DisallowedMultiPropertyDefinition

		get {
			throw new LogicException(
				self::class . ' must be parsed manually',
			);
		}

	} // phpcs:enable SlevomatCodingStandard.Classes.DisallowMultiPropertyDefinition.DisallowedMultiPropertyDefinition

	public mixed $value {

		get {
			return $this->subnode instanceof ListNode
				? $this->subnode->value
				: $this->subnode;
		}

	}



	public function __construct(
		public readonly int $index,
		public readonly Node $subnode,
	) {}

}
