<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class TokenStream
{

	/** @var int<0, max> */
	private int $currentToken = 0;



	/**
	 * @param list<Token> $tokens
	 */
	public function __construct(
		public readonly array $tokens,
		private readonly LocationGetter $locationGetter,
	) {}



	public function advanceTo(self $tokenStream): void
	{
		$this->currentToken = $tokenStream->currentToken;
	}



	public function isConsumed(): bool
	{
		return $this->currentToken === count($this->tokens);
	}



	/**
	 * @return int<0, max>
	 */
	public function getCurrentPosition(): int
	{
		return $this->currentToken;
	}



	public function getCurrentToken(): Token
	{
		return $this->tokens[$this->currentToken];
	}



	/**
	 * @throws CannotConsumeTokenException
	 */
	public function consumeEndOfStream(): void
	{
		if ($this->isConsumed()) {
			return;
		}

		$token = $this->tokens[$this->currentToken];

		throw new CannotConsumeTokenException(
			"Unexpected token '{$token->value}'",
			$this->currentToken,
			$this->locationGetter->getLocation($token->offset),
		);
	}



	/**
	 * @throws CannotConsumeTokenException
	 */
	public function consumeTokenWithType(string $type): Token
	{
		if (isset($this->tokens[$this->currentToken]) === false) {
			throw new CannotConsumeTokenException(
				"Expected token with type '{$type}', got end of stream",
				$this->currentToken,
				null,
			);
		}

		$token = $this->tokens[$this->currentToken];

		if ($token->type !== $type) {
			throw new CannotConsumeTokenException(
				"Expected token with type '{$type}', got '{$token->type}' ({$token->value}) instead",
				$this->currentToken,
				$this->locationGetter->getLocation($token->offset),
			);
		}

		$this->currentToken++;

		return $token;
	}



	/**
	 * @throws CannotConsumeTokenException
	 */
	public function consumeTokenWithValue(string $value): Token
	{
		if (isset($this->tokens[$this->currentToken]) === false) {
			throw new CannotConsumeTokenException(
				"Expected token '{$value}', got end of stream",
				$this->currentToken,
				null,
			);
		}

		$token = $this->tokens[$this->currentToken];

		if ($token->value !== $value) {
			throw new CannotConsumeTokenException(
				"Expected token '{$value}', got '{$token->value}' instead",
				$this->currentToken,
				$this->locationGetter->getLocation($token->offset),
			);
		}

		$this->currentToken++;

		return $token;
	}

}
