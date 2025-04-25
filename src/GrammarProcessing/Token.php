<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class Token
{

	public function __construct(
		public readonly string $type,
		public readonly string $value,
		public readonly int $offset,
	) {}

}
