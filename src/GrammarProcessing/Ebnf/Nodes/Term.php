<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use LogicException;
use Vojtechdobes\GrammarProcessing;


final class Term implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		/** @var GrammarProcessing\SelectedNode $node */

		return match ($node->index) {
			0 => yield $node->value[2],
			1 => new GrammarProcessing\Vocabulary\Repeat(
				yield $node->value[2],
				0,
				1,
			),
			2 => new GrammarProcessing\Vocabulary\Repeat(
				yield $node->value[2],
				0,
				null,
			),
			3 => yield $node->value,
			4 => yield $node->value,
			default => throw new LogicException("This can't happen"),
		};
	}

}
