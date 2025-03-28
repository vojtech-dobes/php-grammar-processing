<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class Interpretation
{

	/**
	 * @param array<string, NodeInterpretation> $nodeInterpretations
	 */
	public function __construct(
		public readonly array $nodeInterpretations,
	) {}

}
