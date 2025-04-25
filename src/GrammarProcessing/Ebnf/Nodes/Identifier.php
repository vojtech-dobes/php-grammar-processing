<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use Vojtechdobes\GrammarProcessing;


final class Identifier implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		return new GrammarProcessing\Vocabulary\Nonterminal(yield $node->value);
	}

}
