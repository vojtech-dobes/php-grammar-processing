<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class OneOf implements Symbol
{

	/**
	 * @param list<Symbol> $symbols
	 */
	public function __construct(
		private readonly array $symbols,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return '(?:' . implode('|', array_map(
			static fn ($symbol) => $symbol->getPattern($nonterminals),
			$this->symbols,
		)) . ')';
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): ?GrammarProcessing\Node
	{
		$position = $tokenStream->currentPosition;

		$attempts = [];

		foreach ($this->symbols as $i => $symbol) {
			$node = $symbol->acceptNode($error, $tokenStream, $nonterminals);

			if ($node !== null) {
				$attempts[] = [
					'index' => $i,
					'node' => $node,
					'position' => $tokenStream->currentPosition,
				];
			}

			$tokenStream->currentPosition = $position;
		}

		if ($attempts === []) {
			return null;
		}

		usort($attempts, static fn ($a1, $a2) => $a1['position'] <=> $a2['position']);

		$result = array_pop($attempts);

		$tokenStream->currentPosition = $result['position'];

		return new GrammarProcessing\SelectedNode(
			$result['index'],
			$result['node'],
		);
	}



	public function visit(callable $visitor): Symbol
	{
		$result = [];

		foreach ($this->symbols as $symbol) {
			$result[] = $symbol->visit($visitor);
		}

		return $visitor(
			$result === $this->symbols
				? $this
				: new self($result),
		);
	}

}
