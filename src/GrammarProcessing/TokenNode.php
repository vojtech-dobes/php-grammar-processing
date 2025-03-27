<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class TokenNode implements Node
{

	public string $name {

		get {
			return $this->value->type;
		}

	}



	public function __construct(
		public readonly Token $value,
	) {}

}
