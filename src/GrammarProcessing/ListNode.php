<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


final class ListNode implements Node
{

	public string $name {

		get {
			throw new LogicException(
				self::class . " must be parsed manually",
			);
		}

	}



	/**
	 * @param list<Node> $value
	 */
	public function __construct(
		public readonly array $value,
	) {}

}
