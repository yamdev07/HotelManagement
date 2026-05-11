<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function store($userData);

    public function showUser($request);

    public function showCustomer($request);
}
