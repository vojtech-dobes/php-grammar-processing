<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class Subtract implements Symbol
{

	public function __construct(
		private readonly Symbol $baseSymbol,
		private readonly Symbol $subtractedSymbol,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return sprintf(
			'(?:(?=%s)(?!%s)[\S\s])',
			$this->baseSymbol->getPattern($nonterminals),
			$this->subtractedSymbol->getPattern($nonterminals),
		);
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\Node
	{
		$tokenStreamCopy = clone $tokenStream;

		$node = $this->baseSymbol->acceptNode($error, $tokenStreamCopy, $nonterminals);

		try {
			$this->subtractedSymbol->acceptNode(
				new GrammarProcessing\Error(),
				clone $tokenStream,
				$nonterminals,
			);
		} catch (GrammarProcessing\CannotConsumeTokenException) {
			$pass = true;
		}

		if (isset($pass) === false) {
			throw new GrammarProcessing\CannotConsumeTokenException('', $tokenStream->getCurrentPosition(), null);
		}

		$tokenStream->advanceTo($tokenStreamCopy);

		return $node;
	}



	public function visit(callable $visitor): Symbol
	{
		$visitedBaseSymbol = $this->baseSymbol->visit($visitor);
		$visitedSubtractedSymbol = $this->subtractedSymbol->visit($visitor);

		$isDifferent = $visitedBaseSymbol !== $this->baseSymbol || $visitedSubtractedSymbol !== $this->subtractedSymbol;

		return $visitor(
			$isDifferent ? $this : new self(
				$visitedBaseSymbol,
				$visitedSubtractedSymbol,
			),
		);
	}

}
