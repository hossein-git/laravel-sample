<?php

namespace Modules\Base\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

interface BaseServiceInterface
{
    public function index(): JsonResource;

    public function store(array $input): Model;

    public function update(array $input, int $id);

    public function show(int $id): JsonResource;

    public function destroy(int $id): ?bool;
}
