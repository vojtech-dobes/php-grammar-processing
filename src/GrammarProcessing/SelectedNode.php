<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


final class SelectedNode implements Node
{

	public function __construct(
		public readonly int $index,
		public readonly Node $subnode,
	) {}



	public function getName(): never
	{
		throw new LogicException(
			self::class . " must be parsed manually",
		);
	}



	public function getValue(): mixed
	{
		return $this->subnode instanceof ListNode
			? $this->subnode->value
			: $this->subnode;
	}

}
