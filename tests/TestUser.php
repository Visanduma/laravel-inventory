<?php


namespace Visanduma\LaravelInventory\Tests;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TestUser extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use Authenticatable;

    protected $guarded = ['password'];
}
