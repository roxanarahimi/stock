<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Token;

use App\Http\Resources\FormResource;
use App\Models\Form;
use App\Models\FormRecord;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware(Token::class);//->except('readOnly1');
    }

    public function index(Request $request)
    {
        try {
            if ($request['StoreCode']) {
                $form = Form::orderByDesc('id')->where('StoreCode', $request['StoreCode'])->get();
            } else {
                return \response('لطفا کد انبار را وارد کنید', 422);
            }
            return \response(FormResource::collection($form), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function last(Request $request)
    {
        try {
            if ($request['StoreCode']) {
                $form = Form::orderByDesc('id')
                    ->where('StoreCode', $request['StoreCode'])
                    ->where('End', null)
                    ->first();
                if (isset($form)){
                    return \response(new FormResource($form), 200);
                }else{
                    return \response($form, 200);
                }

            } else {
                return \response('لطفا کد انبار را وارد کنید', 422);
            }


        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function store(Request $request)
    {
        try {
            $lastForm = Form::orderByDesc('id')->where('StoreCode',$request['StoreCode'])->first();
            if($lastForm && $lastForm['End'] === null){
                $lastForm->update([
                    'End' => now()
                ]);
            }
            $form = Form::create([
                'StoreCode' => $request['StoreCode'],
                'Start' => now()
            ]);

            return \response(new FormResource($form), 201);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function show($id)
    {
        try {
            $form = Form::find($id);
            return \response(new FormResource($form), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $form = Form::find($id);
            $form->update($request->all());
            return \response(new FormResource($form), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function end(Request $request, $id)
    {
        try {
            $form = Form::find($id);
            $form->update([
                'End' => now()
            ]);
            return \response(new FormResource($form), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function destroy($id)
    {
        try {
            $form = Form::find($id);
            $records = FormRecord::where('form_id', $form['id'])->get();
            foreach ($records as $item) {
                $item->delete();
            }
            $form->delete();
            return \response('form was deleted successfully.', 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
