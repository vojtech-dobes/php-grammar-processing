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
	): GrammarProcessing\TokenNode|GrammarProcessing\NonterminalNode
	{
		return isset($nonterminals[$this->nonterminal])
			? new GrammarProcessing\NonterminalNode($this->nonterminal, $nonterminals[$this->nonterminal]->acceptNode($error, $tokenStream, $nonterminals))
			: new GrammarProcessing\TokenNode($tokenStream->consumeTokenWithType($this->nonterminal));
	}

}
