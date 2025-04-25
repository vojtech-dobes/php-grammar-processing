<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use Vojtechdobes\GrammarProcessing;


final class Concatenation implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		$result = [
			yield $node->value[1],
		];

		foreach ($node->value[3]->value as $item) {
			$result[] = yield $item->value[2];
		}

		if (count($result) === 1) {
			return $result[0];
		}

		return new GrammarProcessing\Vocabulary\Sequence($result);
	}

}
