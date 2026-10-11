<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class Literal implements Symbol
{

	public function __construct(
		public readonly string $literal,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return preg_quote($this->literal, '~');
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): ?GrammarProcessing\TokenNode
	{
		$token = $tokenStream->consumeTokenWithValue($this->literal, $error);

		if ($token === null) {
			return null;
		}

		return new GrammarProcessing\TokenNode($token);
	}



	public function visit(callable $visitor): Symbol
	{
		return $visitor($this);
	}

}
