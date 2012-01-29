<?php
namespace Opentag\Common\Di\Exception;

use Zend\Di\Exception,
    DomainException;

class UndefinedReferenceException extends DomainException implements Exception
{
}
