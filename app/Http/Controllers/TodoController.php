<?php

namespace App\Http\Controllers;


use App\Models\Todo;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $todo = Todo::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')->paginate(5);
        if ($todo->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'message' => $todo,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Todo Not Found",
            ], 404);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Todo $todo, Category $category)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|numeric',
            'title' => 'required|string|max:20',
            'description' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        }

        // Check or create category
        $category = Category::firstOrCreate(
            ['user_id' => Auth::id(), 'name' => $request->input('name')]
        );
        // Create the todo item
        $todo = $todo->create([
            'user_id' => Auth::id(),
            'category_id' => $category->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => 'Pending',
        ]);

        // Check if the todo was created successfully
        if ($todo) {
            return response()->json([
                'status' => 200,
                'message' => 'Todo Created Successfully',
              
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
            ], 500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
           //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): JsonResponse
    {
       $todo = Todo::with('category')->find($id);
        if ($todo) {
            return response()->json([
                'status' => 200,
                'message' => $todo,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Todo not found',
            ], 404);
        }
        ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|numeric',
            'title' => 'required|string|max:20',
            'description' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        }

        // Update or create category
        $category = Category::firstOrCreate(
            ['user_id' => Auth::id(), 'name' => $request->input('name')]
        );


        // Create the todo item
        $todo = $todo->update([
            'category_id' => $category->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => 'Pending',
        ]);

        // Check if the todo was created successfully
        if ($todo) {
            // Attach the todo to the authenticated user
            // $todo->users()->attach(Auth::id());    

            return response()->json([
                'status' => 200,
                'message' => 'Todo Updated Successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo): JsonResponse
    {
        // Check if the authenticated user is the owner of the Todo
        if (Auth::id() !== $todo->user_id) {
            return response()->json([
                'status' => 403, // Forbidden, as the user is not authorized to delete this todo
                'message' => 'You are not authorized to delete this todo.',
            ], 403);
        }

        // Proceed to delete the todo
        if ($todo) {
            $todo->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Todo Deleted Successfully',
            ], 200);
        }

        // If the todo was not found
        return response()->json([
            'status' => 404,
            'message' => 'Todo not Found',
        ], 404);
    }

    public function todoStatus(Todo $todo): JsonResponse
    {
        $newStatus = $todo->where('user_id', Auth::id());

        $newStatus = $todo->status === 'completed' ? 'pending' : 'completed';

        $todo->update(['status' => $newStatus]);

        return response()->json([
            'status' => 200,
            'message' => "Todo status updated to {$newStatus}.",
        ], 200);
    }
    public function deletedTodos(Todo $todo): JsonResponse
    {
        $todo = $todo->onlyTrashed()->where('user_id', Auth::id())->paginate(5);

        if (!$todo->isEmpty()) {
            return response()->json([
                'status' => 200,
                'message' => $todo,
            ], 200);

        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Deleted Todos Found",
            ], 404);
        }

    }
    public function deleteForever($id): JsonResponse
    {
        $todo = Todo::onlyTrashed()->where('user_id', Auth::id())->find($id);

        if (!$todo) {
            return response()->json([
                'status' => 404,
                'message' => 'Todo Not Found',
            ], 404);
        }

        $todo->forceDelete();

        return response()->json([
            'status' => 200,
            'message' => 'Todo Deleted Permanently',
        ], 200);
    }
    public function restoreTodos($id): JsonResponse
    {
        $todo = Todo::onlyTrashed()->where('user_id', Auth::id())->find($id);

        if (!$todo) {
            return response()->json([
                'status' => 404,
                'message' => 'Todo Not Found',
            ], 404);
        }

        $todo->restore();

        return response()->json([
            'status' => 200,
            'message' => 'Todo Restored Successfully',
        ], 200);
    }
}
