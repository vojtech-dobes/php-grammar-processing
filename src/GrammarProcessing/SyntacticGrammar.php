<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


/**
 * @template TSymbol of string
 */
final class SyntacticGrammar
{

	/**
	 * @param array<TSymbol, Vocabulary\Symbol> $syntacticSymbols
	 */
	public function __construct(
		public readonly array $syntacticSymbols,
	) {}



	/**
	 * @template TRootSymbol of TSymbol
	 * @param TRootSymbol $rootSymbol
	 * @return AbstractSyntaxTree<TRootSymbol>
	 * @throws CannotConsumeTokenException
	 */
	public function parseLexicalTokens(TokenStream $tokenStream, string $rootSymbol): AbstractSyntaxTree
	{
		$error = new Error();

		$result = $this->syntacticSymbols[$rootSymbol]->acceptNode(
			$error,
			$tokenStream,
			$this->syntacticSymbols,
		);

		if ($result !== null) {
			if ($tokenStream->consumeEndOfStream($error) === false) {
				$result = null;
			}
		}

		if ($result === null) {
			$error->throw($tokenStream->locationGetter);
		}

		return new AbstractSyntaxTree($rootSymbol, $result);
	}

}
