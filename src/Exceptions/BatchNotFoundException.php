<?php


namespace Visanduma\LaravelInventory\Exceptions;


class BatchNotFoundException extends \Exception
{
    protected $message = "Stock batch not found";
}
