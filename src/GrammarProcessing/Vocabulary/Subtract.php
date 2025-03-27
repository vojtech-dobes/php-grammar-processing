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
			$pass = TRUE;
		}

		if (isset($pass) === FALSE) {
			throw new GrammarProcessing\CannotConsumeTokenException("", $tokenStream->getCurrentPosition(), NULL);
		}

		$tokenStream->advanceTo($tokenStreamCopy);

		return $node;
	}

}
