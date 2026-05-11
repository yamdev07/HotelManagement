<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface
{
    public function get($request);

    public function count($request);

    public static function store($request);
}
