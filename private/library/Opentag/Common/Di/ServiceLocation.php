<?php
namespace Opentag\Common\Di;

interface ServiceLocation extends Locator
{
    public function set($name, $service);
}
