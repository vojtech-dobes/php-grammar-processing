<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class Literal implements Symbol
{

	public function __construct(
		private readonly string $literal,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return preg_quote($this->literal, '~');
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\TokenNode
	{
		try {
			$token = $tokenStream->consumeTokenWithValue($this->literal);
		} catch (GrammarProcessing\CannotConsumeTokenException $e) {
			$error->setError($e);
			throw $e;
		}

		return new GrammarProcessing\TokenNode($token);
	}

}
