<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class Sequence implements Symbol
{

	/**
	 * @param list<Symbol> $symbols
	 */
	public function __construct(
		public readonly array $symbols,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return '(?:' . implode('', array_map(
			static fn ($symbol) => $symbol->getPattern($nonterminals),
			$this->symbols,
		)) . ')';
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\ListNode
	{
		$result = [];

		foreach ($this->symbols as $symbol) {
			$result[] = $symbol->acceptNode($error, $tokenStream, $nonterminals);
		}

		return new GrammarProcessing\ListNode($result);
	}



	public function visit(callable $visitor): Symbol
	{
		$visitedSymbols = [];

		foreach ($this->symbols as $symbol) {
			$visitedSymbols[] = $symbol->visit($visitor);
		}

		return $visitor(
			$visitedSymbols === $this->symbols
				? $this
				: new self($visitedSymbols),
		);
	}

}
