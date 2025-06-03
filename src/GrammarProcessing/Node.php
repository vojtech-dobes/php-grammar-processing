<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


interface Node
{

	function getName(): string;



	function getValue(): mixed;

}
