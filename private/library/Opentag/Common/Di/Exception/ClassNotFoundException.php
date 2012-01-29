<?php
namespace Opentag\Common\Di\Exception;

use Zend\Di\Exception,
    DomainException;

class ClassNotFoundException extends DomainException implements Exception
{
}
