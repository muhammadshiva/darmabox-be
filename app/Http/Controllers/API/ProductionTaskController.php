<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Production\StoreProductionTaskRequest;
use App\Http\Requests\Production\UpdateProductionTaskRequest;
use App\Http\Resources\ProductionTaskResource;
use App\Models\ProductionTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductionTaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = ProductionTask::query()
            ->with(['order.customer', 'assignee', 'materials', 'teamMembers.user'])
            ->when($request->get('status'), fn($q, $status) => $q->where('status', $status))
            ->when($request->get('order_id'), fn($q, $orderId) => $q->where('order_id', $orderId))
            ->orderByDesc('created_at')
            ->paginate(20);

        return ProductionTaskResource::collection($tasks);
    }

    public function show(ProductionTask $productionTask)
    {
        $productionTask->load(['order.customer', 'assignee', 'materials', 'teamMembers.user', 'attachments']);
        return new ProductionTaskResource($productionTask);
    }

    public function store(StoreProductionTaskRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $task = ProductionTask::create(array_merge($request->validated(), [
                'created_by' => $request->user()->id ?? null,
            ]));

            if ($request->has('materials')) {
                $task->materials()->createMany($request->input('materials'));
            }

            if ($request->has('team')) {
                $task->teamMembers()->createMany($request->input('team'));
            }

            return new ProductionTaskResource($task->load(['materials', 'teamMembers.user']));
        });
    }

    public function update(UpdateProductionTaskRequest $request, ProductionTask $productionTask)
    {
        return DB::transaction(function () use ($request, $productionTask) {
            $productionTask->update($request->validated());

            if ($request->has('materials')) {
                $productionTask->materials()->delete();
                $productionTask->materials()->createMany($request->input('materials'));
            }

            if ($request->has('team')) {
                $productionTask->teamMembers()->delete();
                $productionTask->teamMembers()->createMany($request->input('team'));
            }

            return new ProductionTaskResource($productionTask->load(['materials', 'teamMembers.user', 'attachments']));
        });
    }

    public function destroy(ProductionTask $productionTask)
    {
        $productionTask->delete();
        return response()->json(['message' => 'deleted']);
    }

    public function uploadAttachment(Request $request, ProductionTask $productionTask)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'label' => ['nullable', 'string']
        ]);

        $path = $request->file('file')->store('production/task-attachments', 'public');
        $attachment = $productionTask->attachments()->create([
            'path' => $path,
            'label' => $request->input('label'),
        ]);

        return response()->json($attachment, 201);
    }

    public function deleteAttachment(ProductionTask $productionTask, int $attachmentId)
    {
        $attachment = $productionTask->attachments()->findOrFail($attachmentId);
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();
        return response()->json(['message' => 'deleted']);
    }
}
