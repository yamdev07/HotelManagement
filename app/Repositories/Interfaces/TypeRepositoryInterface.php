<?php

namespace App\Repositories\Interfaces;

interface TypeRepositoryInterface
{
    public function showAll($request);

    public function getTypesDatatable($request);

    public function store($typeData);

    public function getTypeList($request);
}
