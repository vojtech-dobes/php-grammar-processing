<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class Nonterminal implements Symbol
{

	public function __construct(
		public readonly string $nonterminal,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return $nonterminals[$this->nonterminal]->getPattern($nonterminals);
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\TokenNode|GrammarProcessing\NonterminalNode|null
	{
		if (isset($nonterminals[$this->nonterminal])) {
			$node = $nonterminals[$this->nonterminal]->acceptNode($error, $tokenStream, $nonterminals);

			if ($node === null) {
				return null;
			}

			return new GrammarProcessing\NonterminalNode($this->nonterminal, $node);
		} else {
			$token = $tokenStream->consumeTokenWithType($this->nonterminal, $error);

			if ($token === null) {
				return null;
			}

			return new GrammarProcessing\TokenNode($token);
		}
	}



	public function visit(callable $visitor): Symbol
	{
		return $visitor($this);
	}

}
