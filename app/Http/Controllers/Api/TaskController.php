<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskListResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $searchTerm = $request->input('search');
        $ordering = $request->input('order_by');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $isSelf = $request->input('is_self');
        $userId = auth()->user()->id;

        $tasks = Task::with(['parent', 'children', 'user'])
            ->when($isSelf, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->search($searchTerm);
            })
            ->when(!$searchTerm, function ($query) use ($searchTerm) {
                $query->doesntHave('parent');
            })
            ->when(is_array($ordering), function ($query) use ($ordering) {
                foreach ($ordering as $attribute => $order) {
                    $query->orderBy($attribute, $order);
                }
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($priority, function ($query) use ($priority) {
                $query->where('priority', $priority);
            })
            ->get();

        return TaskListResource::collection($tasks);
    }

    /**
     * @param Task $task
     * @return TaskListResource
     */
    public function show(Task $task): TaskListResource
    {
        $task->load(['parent', 'children']);

        return new TaskListResource($task);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->safe();

        try {
            $data->user_id = auth()->user()->id;
            $task = new Task($data->all());
            $task->save();

            return response()->json('Ok');
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json('Internal Server Error', 500);
        }
    }

    public function update(Task $task, UpdateTaskRequest $request): JsonResponse
    {
        $data = $request->safe();

        try {
            if (isset($data->status)) {
                if ($data->status == TaskStatusEnum::Done->value) {
                    if (!$task->children()->whereNot('status', TaskStatusEnum::Done->value)->exists()) {
                        $data->completed_at = Carbon::now();
                    } else {
                        return response()->json('You can`t do that. The task has unsolved subtasks!',422);
                    }
                } else {
                    $data->completed_at = null;
                }
            }
            $task->update($data->all());

            return response()->json('Ok');
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json('Internal Server Error', 500);
        }
    }

    public function destroy(Task $task): JsonResponse
    {
        try {
            if ($task->children()->whereNot('status', TaskStatusEnum::Done->value)->exists()) {
                return response()->json('You can`t do that. The task has unsolved subtasks!', 422);
            }
            if ($task->status !== TaskStatusEnum::Done->value) {
                return response()->json('You can`t do that. The task has already been done', 422);
            }
            $task->delete();

            return response()->json('Ok');
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json('Internal Server Error', 500);
        }
    }
}
