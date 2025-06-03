<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class TokenNode implements Node
{

	public function __construct(
		public readonly Token $value,
	) {}



	public function getName(): string
	{
		return $this->value->type;
	}



	public function getValue(): Token
	{
		return $this->value;
	}

}
