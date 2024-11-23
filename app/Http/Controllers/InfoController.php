<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Token;
use App\Http\Resources\InfoResource;
use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfoController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware(Token::class);//->except('readOnly1');
    }

    public function index(Request $request)
    {
        try {
            if ($request['StoreCode']){
                $info = Info::orderBy('id')->where('StoreCode',$request['StoreCode'])
                    ->orderBy('PartCode');
                if ($request['PartCode']){
                    $info = Info::orderBy('id')->where('PartCode',$request['StoreCode']);
                }
                  $info = $info->get();
            }else{
                return \response('لطفا کد انبار را وارد کنید', 422);
            }
            return \response(InfoResource::collection($info), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }
public function index2(Request $request)
    {
        try {
            if ($request['StoreCode']){
                $info = Info::orderBy('id')->where('StoreCode',$request['StoreCode'])
                    ->orderBy('PartCode')->get()->count();
            }else{
                return \response('لطفا کد انبار را وارد کنید', 422);
            }
            return \response($info, 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function store(Request $request)
    {
        try {
            $info = Info::create($request->all());
            return \response(new InfoResource($info), 201);

        } catch (\Exception $exception) {
            return $exception;
        }
    }
    public function sku()
    {
        $dat2 = DB::connection('sqlsrv')->table('LGS3.Part')->orderBy('Code')
            ->select('Code','Name')
           ->where('State',1)
            ->get();
        return $dat2;
    }
    public function show(string $id)
    {
        try {
            $info = Info::find($id);
            return \response(new InfoResource($info), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $info = Info::find($id);
            $info->update($request->all());
            return \response(new InfoResource($info), 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function destroy(string $id)
    {
        try {
            $info = Info::find($id);
            $info->delete();
            return \response('deleted successfully', 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }



    public function fix(){
        $dat = DB::connection('sqlsrv')->table('DBO.MS_VWStorePartFactorRemainQuantity')
            ->orderBy('StoreCode')->orderBy('PartCode')->paginate(100);
        return $dat;
    }
    public function cache()
    {
        try {
            Info::query()->truncate();
            $dat = DB::connection('sqlsrv')->table('DBO.MS_VWStorePartFactorRemainQuantity')
                ->get();

            foreach ($dat as $item) {
                $d = Info::where('StoreCode', $item->StoreCode)->where('PartCode', $item->PartCode)->first();
                if ($d && (integer)$item->Quantity != (integer)$d->Quantity) {
                    $d->update([
                        'Factor' => $item->Factor,//??
                        'Quantity' => (integer)$item->Quantity
                    ]);
                }
                Info::create([
                    'StoreCode' => $item->StoreCode,
                    'StoreName' => $item->StoreName,
                    'PartCode' => $item->PartCode,
                    'PartName' => $item->PartName,
                    'Unit' => $item->Unit,
                    'Factor' => $item->Factor,
                    'Quantity' => (integer)$item->Quantity
                ]);
            }

            $datetime = new \DateTime("now", new \DateTimeZone("Asia/Tehran"));
            $nowTime = $datetime->format('Y-m-d G:i');
            echo $nowTime . ' - Tehran Time: cache is ok
';
        } catch (\Exception $exception) {
            $datetime = new \DateTime("now", new \DateTimeZone("Asia/Tehran"));
            $nowTime = $datetime->format('Y-m-d G:i');
            echo $nowTime . ' - Tehran Time: ' . $exception->getMessage() . '
';
        }
    }
}
