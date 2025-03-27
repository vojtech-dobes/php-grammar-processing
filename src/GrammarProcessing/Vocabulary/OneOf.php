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
	): GrammarProcessing\Node
	{
		$attempts = [];
		$localError = new GrammarProcessing\Error();

		foreach ($this->symbols as $symbol) {
			$tokenStreamCopy = clone $tokenStream;

			try {
				$node = $symbol->acceptNode($error, $tokenStreamCopy, $nonterminals);

				$attempts[] = [
					'node' => $node,
					'tokenStream' => $tokenStreamCopy,
				];
			} catch (GrammarProcessing\CannotConsumeTokenException $e) {
				$error->setError($e);
				$localError->setError($e);
			}
		}

		if ($attempts === []) {
			throw $localError->throw();
		}

		usort(
			$attempts,
			static fn ($a1, $a2) => $a1['tokenStream']->getCurrentPosition() <=> $a2['tokenStream']->getCurrentPosition(),
		);

		$result = array_pop($attempts);

		$tokenStream->advanceTo($result['tokenStream']);
		return $result['node'];
	}

}
