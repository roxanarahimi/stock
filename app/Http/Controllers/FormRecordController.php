<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Token;
use App\Http\Resources\FormRecordResource;
use App\Models\FormRecord;
use Illuminate\Http\Request;

class FormRecordController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware(Token::class);
    }

    public function index(Request $request)
    {
        try {
            if ($request['form_id']) {
                $formRecord = FormRecord::orderByDesc('id')->where('form_id', $request['form_id'])->get();
            } else {
                return \response('لطفا form_id را وارد کنید', 422);
            }
            return \response(FormRecordResource::collection($formRecord), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function store(Request $request)
    {
        try {

            $formRecord = FormRecord::create([
                'form_id' => $request['form_id'],
                'info_id' => $request['info_id'],
                'Counted' => $request['Counted'],
                'Wastage' => $request['Wastage'],
                'Conflict' => $request['Conflict'],
            ]);

            return \response(new FormRecordResource($formRecord), 201);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function show(FormRecord $formRecord)
    {
        try {
            return \response(new FormRecordResource($formRecord), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function update(Request $request, FormRecord $formRecord)
    {
        try {
            $formRecord->update($request->all());
            return \response(new FormRecordResource($formRecord), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function destroy(FormRecord $formRecord)
    {
        try {
            $formRecord->delete();
            return \response('record was deleted successfully.', 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
