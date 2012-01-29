<?php
namespace Opentag\Common\Di\Exception;

use Zend\Di\Exception;

class InvalidParamNameException 
    extends InvalidArgumentException 
    implements Exception
{
}
