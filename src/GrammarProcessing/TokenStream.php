<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class TokenStream
{

	/** @var int<0, max> */
	public int $currentPosition = 0;



	/**
	 * @param list<Token> $tokens
	 */
	public function __construct(
		public readonly array $tokens,
		public readonly LocationGetter $locationGetter,
	) {}



	public function isConsumed(): bool
	{
		return $this->currentPosition === count($this->tokens);
	}



	public function getCurrentToken(): Token
	{
		return $this->tokens[$this->currentPosition];
	}



	public function consumeEndOfStream(Error $error): bool
	{
		if ($this->isConsumed()) {
			return true;
		}

		$token = $this->tokens[$this->currentPosition];

		$error->recordFail(
			$this->currentPosition,
			$token,
			"Unexpected token '{$token->value}'",
		);

		return false;
	}



	public function consumeTokenWithType(string $type, Error $error): ?Token
	{
		if (isset($this->tokens[$this->currentPosition]) === false) {
			$error->recordFail(
				$this->currentPosition,
				null,
				"Expected token with type '{$type}', got end of stream",
			);

			return null;
		}

		$token = $this->tokens[$this->currentPosition];

		if ($token->type !== $type) {
			$error->recordFail(
				$this->currentPosition,
				$token,
				"Expected token with type '{$type}', got '{$token->type}' ({$token->value}) instead",
			);

			return null;
		}

		$this->currentPosition++;

		return $token;
	}



	public function consumeTokenWithValue(string $value, Error $error): ?Token
	{
		if (isset($this->tokens[$this->currentPosition]) === false) {
			$error->recordFail(
				$this->currentPosition,
				null,
				"Expected token '{$value}', got end of stream",
			);

			return null;
		}

		$token = $this->tokens[$this->currentPosition];

		if ($token->value !== $value) {
			$error->recordFail(
				$this->currentPosition,
				$token,
				"Expected token '{$value}', got '{$token->value}' instead",
			);

			return null;
		}

		$this->currentPosition++;

		return $token;
	}

}
