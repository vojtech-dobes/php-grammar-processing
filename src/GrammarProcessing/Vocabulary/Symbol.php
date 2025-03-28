<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


interface Symbol
{

	/**
	 * @param array<string, Symbol> $nonterminals
	 */
	function getPattern(array $nonterminals): string;



	/**
	 * @param array<string, Symbol> $nonterminals
	 */
	function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\Node;

}
