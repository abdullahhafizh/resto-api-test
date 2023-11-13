<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{HowTo};

class HowToController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $howTo = HowTo::query();

        return $this->jsonSuccess($howTo->get());
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
            'howTo_id' => 'required|exists:howTo,id',
            'how_to_cook_list' => 'required|array',
        ]);

        if ($validator->fails()) return $this->jsonValidate(['errors' => $validator->errors()->getMessages()]);

        $howTo = new HowTo();
        $howTo->howTo_id = $request->howTo_id;
        $howTo->how_to_cook_list = json_encode($request->how_to_cook_list, JSON_UNESCAPED_SLASHES) . PHP_EOL;
        $save = $howTo->save();
        if (!$save) return $this->jsonGeneralError('Something went wrong when adding how to. Please try again...');

        return $this->jsonSuccess(['message' => 'how to added successfully', 'how-to' => $howTo]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $howTo = HowTo::find($id);
        if (!$howTo) return $this->jsonNotFound('Invalid how to id provided');

        return $this->jsonSuccess(['message' => 'How to viewed successfully', 'how-to' => $howTo]);
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
        $howTo = HowTo::find($id);
        if (!$howTo) return $this->jsonNotFound('Invalid how to id provided');

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:191',
            'description' => '',
            'time' => 'numeric|min:1',
            'qty_per_cook' => 'numeric|min:1',
        ]);

        if ($validator->fails()) return $validator;

        if (!empty($request->name)) {
            $checkName = HowTo::where('name', $request->name)->where('id', '!=', $id)->first();
            if ($checkName) return $this->jsonValidate('Name already owned by another howTo');
            $howTo->name = $request->name;
        }

        if (!empty($request->description)) $howTo->description = $request->description . PHP_EOL;

        if (!empty($request->time)) $howTo->time = $request->time;

        if (!empty($request->qty_per_cook)) $howTo->qty_per_cook = $request->qty_per_cook;

        $update = $howTo->update();
        if (!$update) return $this->jsonGeneralClientError('Something went wrong when updating howTo. Please try again...');

        return $this->jsonSuccess(['message' => 'howTo updated successfully', 'howTo' => $howTo]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $howTo = HowTo::find($id);
        if (!$howTo) return $this->jsonNotFound('Invalid howTo id provided');

        $deleted = $howTo->delete();
        if (!$deleted) return $this->jsonGeneralError('Something went wrong when deleting howTo. Please try again...');

        return $this->jsonSuccess('howTo deleted successfully');
    }
}
