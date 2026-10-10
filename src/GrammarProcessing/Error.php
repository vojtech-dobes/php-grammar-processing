<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


final class Error
{

	private int $position = -1;
	private ?Token $token = null;
	private string $message = '';



	public function recordFail(int $position, ?Token $token, string $message): void
	{
		if ($this->position <= $position) {
			$this->position = $position;
			$this->token = $token;
			$this->message = $message;
		}
	}



	/**
	 * @throws CannotConsumeTokenException
	 */
	public function throw(LocationGetter $locationGetter): never
	{
		if ($this->position > -1) {
			throw new CannotConsumeTokenException(
				$this->message,
				$this->position,
				$this->token !== null ? $locationGetter->getLocation($this->token->offset) : null,
			);
		}

		throw new LogicException("This shouldn't happen");
	}

}
