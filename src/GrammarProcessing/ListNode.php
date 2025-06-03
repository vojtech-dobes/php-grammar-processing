<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


final class ListNode implements Node
{

	/**
	 * @param list<Node> $value
	 */
	public function __construct(
		public readonly array $value,
	) {}



	public function getName(): never
	{
		throw new LogicException(
			self::class . " must be parsed manually",
		);
	}



	/**
	 * @return list<Node>
	 */
	public function getValue(): array
	{
		return $this->value;
	}

}
