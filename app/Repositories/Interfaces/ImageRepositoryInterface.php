<?php

namespace App\Repositories\Interfaces;

interface ImageRepositoryInterface
{
    public function uploadImage($path, $file);

    public function destroy($dir);
}
