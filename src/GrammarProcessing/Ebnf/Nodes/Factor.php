<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use LogicException;
use Vojtechdobes\GrammarProcessing;


final class Factor implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		/** @var GrammarProcessing\SelectedNode $node */

		return match ($node->index) {
			0 => new GrammarProcessing\Vocabulary\Repeat(
				yield $node->value[0],
				0,
				1,
			),
			1 => new GrammarProcessing\Vocabulary\Repeat(
				yield $node->value[0],
				0,
				null,
			),
			2 => new GrammarProcessing\Vocabulary\Repeat(
				yield $node->value[0],
				1,
				null,
			),
			3 => new GrammarProcessing\Vocabulary\Subtract(
				yield $node->value[0],
				yield $node->value[4],
			),
			4 => yield $node->value[0],
			default => throw new LogicException("This can't happen"),
		};
	}

}
