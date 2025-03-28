<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use LogicException;
use Vojtechdobes\GrammarProcessing;


final class Regexp implements Symbol
{

	public function __construct(
		private readonly string $pattern,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return $this->pattern;
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\Node
	{
		throw new LogicException(
			sprintf(
				"%s isn't supported, try using %s instead",
				self::class,
				Nonterminal::class,
			),
		);
	}

}
