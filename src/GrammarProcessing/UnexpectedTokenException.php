<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use RuntimeException;


class UnexpectedTokenException extends RuntimeException
{

	public function __construct(
		string $message,
		public readonly ?Location $location,
	)
	{
		if ($this->location !== null) {
			$message = sprintf(
				'%s (line %s, col %s)',
				$message,
				$this->location->line,
				$this->location->column,
			);
		}

		parent::__construct($message);
	}

}
