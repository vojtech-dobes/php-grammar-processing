<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class Location
{

	/**
	 * @param int<0, max> $line
	 * @param int<0, max> $column
	 */
	public function __construct(
		public readonly int $line,
		public readonly int $column,
	) {}

}
