<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait BaseCrudController
{
    use BaseController;


    function __construct()
    {
        $this->service = new $this->service();
    }

    public function index(): JsonResponse
    {
        $data = $this->service->paginate();
        return $this->success(data: $data['data'], meta: $data['meta']);
    }

    public function store(Request $request): JsonResponse
    {
        $form = new $this->form();
        $data = $request->validate($form->rules());
        return $this->success(data: $this->service->create($data));
    }

    public function show(string $id): JsonResponse
    {
        return $this->success(data: $this->service->get($id));
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $form = new $this->updateForm();
        $data = $request->validate($form->rules());

        $data = $this->service->update($id, $data);
        return $this->success(data: $data);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->success(__("delete:success"));
    }
}
