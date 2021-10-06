<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodosRequest;
use App\Http\Requests\UpdateTodosRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;


class TodoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return TodoResource
     */
    public function index()
    {
        return  new TodoResource(Todo::where('user_id',auth()->user()->id)->paginate(15));
        //getting all users Todos and paginating them
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TodoResource
     */
    public function store(StoreTodosRequest $request)
    {
        return new TodoResource(auth()->user()->todos()->create($request->all()));
        // creating a Todos from the relationship user
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return Todo
     */
    public function show(Todo $todo)
    {
        $this->authorize('view',$todo);
        // checking if user is authorised to see todos model
          return $todo;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return bool
     */
    public function update(UpdateTodosRequest $request, Todo $todo)
    {
        return $todo->update($request->all());
        // nothing about unauthorised users being able to edit except not updating the owner of the todos
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Todo $todo)
    {
        $this->authorize('delete',$todo); // checking if authorised
            return response()->json([
                'success' => !!$todo->delete()
            ]);


    }
}
