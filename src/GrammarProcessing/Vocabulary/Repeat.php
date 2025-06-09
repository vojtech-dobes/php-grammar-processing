<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class Repeat implements Symbol
{

	public function __construct(
		public readonly Symbol $symbol,
		public readonly int $min,
		public readonly ?int $max,
	) {}



	public function getPattern(array $nonterminals): string
	{
		return sprintf(
			'%s{%s,%s}',
			$this->symbol->getPattern($nonterminals),
			$this->min,
			$this->max ?? '',
		);
	}



	public function acceptNode(
		GrammarProcessing\Error $error,
		GrammarProcessing\TokenStream $tokenStream,
		array $nonterminals,
	): GrammarProcessing\ListNode
	{
		$result = [];

		if ($this->min > 0) {
			$tokenStreamCopy = clone $tokenStream;

			for ($i = 0; $i < $this->min; $i++) {
				$result[] = $this->symbol->acceptNode($error, $tokenStreamCopy, $nonterminals);
			}

			$tokenStream->advanceTo($tokenStreamCopy);
		}

		while (true) {
			if ($this->max !== null && count($result) >= $this->max) {
				break;
			}

			if ($tokenStream->isConsumed()) {
				break;
			}

			$tokenStreamCopy = clone $tokenStream;

			try {
				$result[] = $this->symbol->acceptNode($error, $tokenStreamCopy, $nonterminals);
			} catch (GrammarProcessing\CannotConsumeTokenException $e) {
				break;
			}

			$tokenStream->advanceTo($tokenStreamCopy);
		}

		return new GrammarProcessing\ListNode($result);
	}



	public function visit(callable $visitor): Symbol
	{
		$visitedSymbol = $this->symbol->visit($visitor);

		return $visitor(
			$visitedSymbol === $this->symbol ? $this : new self(
				$visitedSymbol,
				$this->min,
				$this->max,
			),
		);
	}

}
