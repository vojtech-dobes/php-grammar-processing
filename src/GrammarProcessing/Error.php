<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


final class Error
{

	private ?CannotConsumeTokenException $error = null;



	public function setError(?CannotConsumeTokenException $error): void
	{
		if ($error === null) {
			throw new LogicException("Most specific error can't be unset");
		}

		if ($this->error === null || $this->error->tokenPosition <= $error->tokenPosition) {
			$this->error = $error;
		}
	}



	/**
	 * @throws CannotConsumeTokenException
	 */
	public function throw(): never
	{
		if ($this->error === null) {
			throw new LogicException('Most specific error being null indicates error in prior logic');
		}

		throw $this->error;
	}

}
