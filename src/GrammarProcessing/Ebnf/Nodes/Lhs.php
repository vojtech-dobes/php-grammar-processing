<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use Vojtechdobes\GrammarProcessing;


final class Lhs implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		return yield $node;
	}

}
