<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use Generator;


interface NodeInterpretation
{

	/**
	 * @return Generator<mixed, Node|Token|null, mixed, mixed>
	 */
	function interpret(Node $node): Generator;

}
