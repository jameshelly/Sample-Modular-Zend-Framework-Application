<?php
namespace Opentag\Common\Di\Exception;

use Zend\Di\Exception,
    DomainException;

class CircularDependencyException extends DomainException implements Exception
{
}
