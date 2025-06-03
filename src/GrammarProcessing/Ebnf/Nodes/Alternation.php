<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use Vojtechdobes\GrammarProcessing;


final class Alternation implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		$result = [
			yield $node->getValue()[1],
		];

		foreach ($node->getValue()[3]->getValue() as $item) {
			$result[] = yield $item->getValue()[2];
		}

		if (count($result) === 1) {
			return $result[0];
		}

		return new GrammarProcessing\Vocabulary\OneOf($result);
	}

}
