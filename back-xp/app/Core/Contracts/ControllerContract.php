<?php

namespace App\Core\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ControllerContract
{
    public function index(?Request $request): JsonResponse;
    public function show(string|int $id): JsonResponse;
    public function store(Request $request): JsonResponse;
    public function update(string|int $id, Request $request): JsonResponse;
    public function destroy(string|int $id): JsonResponse;
}
