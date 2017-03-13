<?php


use Illuminate\Database\Eloquent\Model;
use Napso\Lunytags\TaggableTrait;

class PageStub extends Model
{
    protected $connection = 'testbench';

    public $table = 'pages';

    use TaggableTrait;



}

