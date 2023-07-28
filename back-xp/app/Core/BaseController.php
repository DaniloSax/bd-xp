<?php

namespace App\Core;

use App\Core\Contracts\ControllerContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

abstract class BaseController implements ControllerContract
{
    public BaseService $service;
    public string|JsonResource $resource;
    public ?FormRequest $formRequest = null;

    public function index(?Request $request): JsonResponse
    {
        $content = $this->service->index($request->all());

        if ($this->resource) {

            $content = $this->resource::collection($content);
        }

        return response()->json($content, Response::HTTP_OK);
    }

    public function show(string|int $id): JsonResponse
    {
        $content = $this->service->show($id);

        if ($this->resource) {

            $content = new $this->resource($content);
        }

        return response()->json($content, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validator($request)?->validate();

        $content = $this->service->store($request->all());

        if ($this->resource) {

            $content = new $this->resource($content);
        }

        return response()->json($content, Response::HTTP_OK);
    }

    public function update(string|int $id, Request $request): JsonResponse
    {
        $this->validator($request)?->validate();

        $content = $this->service->update($id, $request->all());

        if ($this->resource) {

            $content = new $this->resource($content);
        }

        return response()->json($content, Response::HTTP_OK);
    }

    public function destroy(string|int $id): JsonResponse
    {
        $content = $this->service->destroy($id);

        return response()->json($content, Response::HTTP_OK);
    }

    public function setDependences(string $service, ?string $resource = null, ?string $formRequest = null)
    {
        $isBaseService = is_subclass_of($service, BaseService::class);

        throw_if(!$isBaseService, NotFoundHttpException::class);

        $this->service = new $service();

        if (isset($resource)) {
            $isResource = is_subclass_of($resource, JsonResource::class);

            throw_if(!$isResource, new NotFoundHttpException("$resource is not found."));

            $this->resource = $resource;
        }

        if (isset($formRequest)) {
            $isFormRequest = is_subclass_of($formRequest, FormRequest::class);

            throw_if(!$isFormRequest, NotFoundHttpException::class);

            $this->formRequest = new $formRequest();
        }
    }

    protected function validator(Request $request): \Illuminate\Validation\Validator|null
    {
        if ($this->formRequest) {

            $validator = Validator::make(
                $request->all(),
                $this->formRequest?->rules() ?? [],
                $this->formRequest?->messages() ?? [],
                $this->formRequest?->attributes() ?? [],
            );

            return $validator;
        }

        return null;
    }
}
