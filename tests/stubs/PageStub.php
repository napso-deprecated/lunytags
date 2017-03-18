<?php


use Illuminate\Database\Eloquent\Model;
use Napso\Lunytags\TaggableTrait;

class PageStub extends Model
{
    use TaggableTrait;

    protected $connection = 'testbench';

    public $table = 'pages';




}

