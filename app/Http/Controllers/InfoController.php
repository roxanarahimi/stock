<?php

namespace App\Http\Controllers;

use App\Http\Resources\InfoResource;
use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfoController extends Controller
{
    public function index()
    {
        try {
            $info = Info::orderBy('id', 'desc')->paginate(50);
            return \response(InfoResource::collection($info), 200);

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
            ->get();
        return $dat;
    }
    public function cache()
    {
        try {
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
