<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


interface Node
{

	public string $name { get; }
	public mixed $value { get; }

}
