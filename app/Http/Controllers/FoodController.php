<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Food};

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $foods = Food::query();
        if (!empty($request->name)) $foods = $foods->where(\DB::raw('lower(name)'), 'like', '%' . strtolower($request->name) . '%');
        
        if (!empty($request->description)) $foods = $foods->where(\DB::raw('lower(description)'), 'like', '%' . strtolower($request->description) . '%');
        
        if (!empty($request->time)) $foods = $foods->where('time', $request->time);

        if (!empty($request->qty_per_cook)) $foods = $foods->where('qty_per_cook', $request->qty_per_cook);

        return $this->jsonSuccess($foods->get());
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:food,name',
            'description' => 'required',
            'time' => 'required|numeric|min:1',
            'qty_per_cook' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) return $this->jsonValidate(['errors' => $validator->errors()->getMessages()]);

        $food = new Food();
        $food->name = $request->name;
        $food->description = $request->description . PHP_EOL;
        $food->time = $request->time;
        $food->qty_per_cook = $request->qty_per_cook;
        $save = $food->save();
        if (!$save) return $this->jsonGeneralError('Something went wrong when adding food. Please try again...');

        return $this->jsonSuccess(['message' => 'Food added successfully', 'food' => $food]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = Food::find($id);
        if (!$food) return $this->jsonNotFound('Invalid food id provided');

        return $this->jsonSuccess(['message' => 'Food viewed successfully', 'food' => $food]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     *  the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $food = Food::find($id);
        if (!$food) return $this->jsonNotFound('Invalid food id provided');

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:191',
            'description' => '',
            'time' => 'numeric|min:1',
            'qty_per_cook' => 'numeric|min:1',
        ]);

        if ($validator->fails()) return $validator;

        if (!empty($request->name)) {
            $checkName = Food::where('name', $request->name)->where('id', '!=', $id)->first();
            if ($checkName) return $this->jsonValidate('Name already owned by another food');
            $food->name = $request->name;
        }

        if (!empty($request->description)) $food->description = $request->description . PHP_EOL;

        if (!empty($request->time)) $food->time = $request->time;

        if (!empty($request->qty_per_cook)) $food->qty_per_cook = $request->qty_per_cook;

        $update = $food->update();
        if (!$update) return $this->jsonGeneralClientError('Something went wrong when updating food. Please try again...');

        return $this->jsonSuccess(['message' => 'Food updated successfully', 'food' => $food]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);
        if (!$food) return $this->jsonNotFound('Invalid food id provided');

        $deleted = $food->delete();
        if (!$deleted) return $this->jsonGeneralError('Something went wrong when deleting food. Please try again...');

        return $this->jsonSuccess('Food deleted successfully');
    }
}
