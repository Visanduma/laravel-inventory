<?php


namespace Visanduma\LaravelInventory\Exceptions;


class SkuExistsException extends \Exception
{
    protected $message = "SKU already exists";
}
