<?php

namespace App\Http\Controllers;

use App\Http\Resources\InfoResource;
use App\Models\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function index()
    {
        try {
            $info = Info::orderBy('id','desc')->paginate(50);
            return \response(InfoResource::collection($info),200);

        }catch (\Exception $exception){ return $exception; }
    }

    public function store(Request $request)
    {
        try {
            $info = Info::create($request->all());
            return \response(new InfoResource($info),201);

        }catch (\Exception $exception){ return $exception; }
    }

    public function show(string $id)
    {
        try {
            $info = Info::find($id);
            return \response(new InfoResource($info),200);

        }catch (\Exception $exception){ return $exception; }
    }
    public function update(Request $request, string $id)
    {
        try {
            $info = Info::find($id);
            $info->update($request->all());
            return \response(new InfoResource($info),200);

        }catch (\Exception $exception){ return $exception; }
    }

    public function destroy(string $id)
    {
        try {
            $info = Info::find($id);
            $info->delete();
            return \response('deleted successfully',200);

        }catch (\Exception $exception){ return $exception; }
    }
}
