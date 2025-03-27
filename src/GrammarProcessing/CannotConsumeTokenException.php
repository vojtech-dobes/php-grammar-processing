<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class CannotConsumeTokenException extends UnexpectedTokenException
{

	/**
	 * @param int<0, max> $tokenPosition
	 */
	public function __construct(
		string $message,
		public readonly int $tokenPosition,
		?Location $location,
	)
	{
		parent::__construct($message, $location);
	}

}
