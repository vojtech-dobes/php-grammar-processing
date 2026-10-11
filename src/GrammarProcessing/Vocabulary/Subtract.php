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
			'(?:(?!%s)(?:%s))',
			$this->subtractedSymbol->getPattern($nonterminals),
			$this->baseSymbol->getPattern($nonterminals),
		);
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): ?GrammarProcessing\Node
	{
		$position = $tokenStream->currentPosition;

		$node = $this->baseSymbol->acceptNode($error, $tokenStream, $nonterminals);

		if ($node === null) {
			$tokenStream->currentPosition = $position;

			return null;
		}

		$afterBasePosition = $tokenStream->currentPosition;
		$tokenStream->currentPosition = $position;

		$subtractNode = $this->subtractedSymbol->acceptNode(
			new GrammarProcessing\Error(),
			$tokenStream,
			$nonterminals,
		);

		if ($subtractNode !== null) {
			$tokenStream->currentPosition = $position;
			$token = $tokenStream->getCurrentToken();

			$error->recordFail(
				$position,
				$token,
				"Unexpected token '{$token->value}'",
			);

			return null;
		}

		$tokenStream->currentPosition = $afterBasePosition;

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
