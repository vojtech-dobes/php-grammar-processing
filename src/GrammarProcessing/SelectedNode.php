<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


final class SelectedNode implements Node
{

	public string $name {

		get {
			throw new LogicException(
				self::class . " must be parsed manually",
			);
		}

	}

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
