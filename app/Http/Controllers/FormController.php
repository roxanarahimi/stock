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
                $form = Form::orderByDesc('id')->where('StoreCode', $request['StoreCode'])->first();
            } else {
                return \response('لطفا کد انبار را وارد کنید', 422);
            }
            return \response(new FormResource($form), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function store(Request $request)
    {
        try {
            $form = Form::create([
                'StoreCode'=>$request['StoreCode'],
                'Start'=> now()
            ]);

            return \response(new FormResource($form), 201);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function show(Form $form)
    {
        try {
            return \response(new FormResource($form), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function update(Request $request, Form $form)
    {
        try {
            $form->update($request->all());
            return \response(new FormResource($form), 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function destroy(Form $form)
    {
        try {
            $records = FormRecord::where('form_id',$form['id'])->get();
            foreach ($records as $item){
                $item->delete();
            }
            $form->delete();
            return \response('form was deleted successfully.', 200);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
