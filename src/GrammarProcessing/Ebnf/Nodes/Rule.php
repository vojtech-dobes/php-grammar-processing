<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use Vojtechdobes\GrammarProcessing;


final class Rule implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		$identifier = yield $node->getValue()[0];
		$production = yield $node->getValue()[4];

		return [$identifier, $production];
	}

}
