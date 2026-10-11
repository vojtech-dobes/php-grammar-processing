<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


enum ErrorType
{

	case ExpectedEndOfStream;
	case ExpectedTokenWithType;
	case ExpectedTokenWithValue;

}
