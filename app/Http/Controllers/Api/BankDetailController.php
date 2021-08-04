<?php

namespace App\Http\Controllers\Api;

use App\BankDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Response;

class BankDetailController extends Controller
{
    //
    public $respond;

    public function __construct(ResponseHelper $response)
    {
        $this->respond = $response;
    }

    /**
     *
     * @param Illuminate\Http\Request $request
     * @return App\Helpers\ResponseHelper $response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'accountName' => 'required|min:3',
            'bankName' => 'required|min:3',
            'accountNumber' => 'required|number|min:5'
        ]);

        $bankDetail = BankDetail::create([
            'accountName' => $request->accountName,
            'bankName' => $request->bankName,
            'accountNumber' => $request->accountNumber
        ]);

        return $this->respond->withSuccess(Response::HTTP_CREATED, $data = $bankDetail);
    }

    public function index()
    {
        $bankDetail = BankDetail::all()->first();
        return $this->respond->withSuccess(Response::HTTP_OK, $data = $bankDetail);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'accountName' => 'required|min:3',
            'bankName' => 'required|min:3',
            'accountNumber' => 'required|numeric|min:5'
        ]);

        $bankDetail = BankDetail::find($request->bankDetailsId);
        $bankDetail->accountName = $request->accountName;
        $bankDetail->bankName = $request->bankName;
        $bankDetail->accountNumber = $request->accountNumber;
        $bankDetail->save();

        return $this->respond->withSuccess(Response::HTTP_OK, $data = $bankDetail);

    }
}
