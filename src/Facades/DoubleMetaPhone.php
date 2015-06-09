<?php
/**
 * Created by RAPIDE Internet.
 * User: heppi_000
 * Date: 10-3-2015
 * Time: 17:49
 */

namespace Searchable\Facades;
use Illuminate\Support\Facades\Facade;

class DoubleMetaPhone extends Facade {
    public static function getFacadeAccessor() {
        return '\Searchable\DoubleMetaPhone';
    }
}