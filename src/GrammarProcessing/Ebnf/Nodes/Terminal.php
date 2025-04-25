<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use Vojtechdobes\GrammarProcessing;


final class Terminal implements GrammarProcessing\NodeInterpretation
{

	public function interpret(GrammarProcessing\Node $node): Generator
	{
		$value = yield $node->value;

		if (str_starts_with($value, '"')) {
			$value = trim($value, '"');
		} elseif (str_starts_with($value, "'")) {
			$value = trim($value, "'");
		}

		if (in_array($value, [
			'\n',
			'\t',
			'\r',
			'\f',
			'\b',
		], true)) {
			return new GrammarProcessing\Vocabulary\Regexp($value);
		}

		return new GrammarProcessing\Vocabulary\Literal($value);
	}

}
