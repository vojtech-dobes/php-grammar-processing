<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class NegativeLookahead implements Symbol
{

	public function __construct(
		private readonly Symbol $symbol,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return sprintf(
			'(?!%s)',
			$this->symbol->getPattern($nonterminals),
		);
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\EmptyNode
	{
		return new GrammarProcessing\EmptyNode('NegativeLookahead');
	}

}
