<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Vocabulary;

use Vojtechdobes\GrammarProcessing;


final class Repeat implements Symbol
{

	public function __construct(
		private readonly Symbol $symbol,
		private readonly int $min,
		private readonly ?int $max,
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

		while (TRUE) {
			if ($this->max !== NULL && count($result) >= $this->max) {
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

}
