<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Token;
use App\Http\Resources\FormResource;
use App\Http\Resources\InfoResource;
use App\Models\Form;
use App\Models\FormRecord;
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
            if ($request['StoreCode']) {
                $info = Info::orderByDesc('id')->where('StoreCode', $request['StoreCode'])
                    ->orderBy('PartCode');
                if ($request['PartCode']) {
                    $info = $info->where('PartCode', $request['PartCode']);
                }
                $info = $info->get();
            } else {
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
            if ($request['StoreCode']) {
                $info = Info::orderBy('id')->where('StoreCode', $request['StoreCode'])
                    ->orderBy('PartCode')->get()->count();
            } else {
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
//            $exist = Info::where('StoreCode', $request['StoreCode'])
//                ->where('PartCode', $request['PartCode'])
//                ->where('Factor', $request['Factor'])
//                ->first();
//            if(!$exist){
                $info = Info::create($request->all());
                $form = Form::orderByDesc('id')
                    ->where('StoreCode', $request['StoreCode'])
                    ->where('End', null)
                    ->first();
                if (isset($form)) {
                    $formRecord = FormRecord::create([
                        'form_id' => $form['id'],
                        'PartCode' => $request['PartCode'],
                        'PartName' => $request['PartName'],
                        'Unit' => $request['Unit'],
                        'Factor' => $request['Factor'],
                        'Quantity' => $request['Quantity'],
                    ]);
                }
                return \response(new InfoResource($info), 201);
//            }else{
//                return \response(['message','محصول با قیمت مورد نظر شما در انبار موجود است.'], 422);
//            }


        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function factor(Request $request)
    {
        if ($request['PartCode']) {

//            return  \response(['12000','15000','20000','22000','30000','31000','31500'], 200);
            $dat2 = DB::connection('sqlsrv')
                ->table('LGS3.InventoryVoucherItemTrackingFactor')
                ->join('LGS3.InventoryVoucherItem', 'LGS3.InventoryVoucherItem.InventoryVoucherItemID', '=', 'InventoryVoucherItemTrackingFactor.InventoryVoucherItemRef')
                ->join('LGS3.Part', 'LGS3.Part.PartID', '=', 'LGS3.InventoryVoucherItem.PartRef')
                ->orderBy('LGS3.InventoryVoucherItemTrackingFactor.TrackingFactor1')
//                ->select('LGS3.Part.Code', 'LGS3.Part.Name', 'LGS3.InventoryVoucherItemTrackingFactor.TrackingFactor1 as Factor')
                ->where('LGS3.Part.code', $request['PartCode'])
                ->where('LGS3.Part.State', 1)
                ->pluck('LGS3.InventoryVoucherItemTrackingFactor.TrackingFactor1')->unique()->toArray();

            return array_values($dat2);
        } else {
            return \response('لطفا PartCode را وارد کنید', 422);
        }


//        $dat2 = DB::connection('sqlsrv')->table('LGS3.PartTrackingFactor')->orderBy('PartCode')
//            ->select('Code','Name')
//            ->where('State',1)
//            ->get();
//        return $dat2;
    }

    public function sku()
    {
        $dat2 = DB::connection('sqlsrv')->table('LGS3.Part')->orderBy('Code')
            ->select('Code', 'Name')
            ->where('State', 1)
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
            if ($request['Quantity'] && $request['Quantity'] != '') {
                InfoQuantityLog::create([
                    'info_id' => $id,
                    'Quantity' => $info['Quantity']
                ]);
            }

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


    public function fix()
    {
        $dat = DB::connection('sqlsrv')->table('DBO.MS_VWStorePartFactorRemainQuantity')
            ->orderBy('StoreCode')->orderBy('PartCode')->paginate(100);
        return $dat;
    }

    public function cache()
    {
        try {
//            Info::query()->truncate();
            $dat = DB::connection('sqlsrv')->table('DBO.MS_VWStorePartFactorRemainQuantity')
                ->get();

            foreach ($dat as $item) {
                $d = Info::where('StoreCode', $item->StoreCode)
                    ->where('PartCode', $item->PartCode)
                    ->where('Factor', $item->Factor)
                    ->first();
                if (!$d) {
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
                if ($d && (integer)$item->Quantity != (integer)$d->Quantity) {
                    $d->update([
                        'StoreName' => $item->StoreName,
                        'Quantity' => (integer)$item->Quantity
                    ]);
                }

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
