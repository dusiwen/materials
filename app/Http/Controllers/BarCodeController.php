<?php

namespace App\Http\Controllers;

use App\Model\EntireInstance;
use Milon\Barcode\DNS1D;

class BarCodeController extends Controller
{
    public function show($entireInstanceIdentityCode)
    {
        $entireInstance = EntireInstance::where('identity_code', $entireInstanceIdentityCode)->firstOrFail();
        $barcode = new DNS1D();
        return view($this->view())
            ->with('serialNumber', date('Y-m') . $entireInstance->entire_model_unique_code)
            ->with('entireInstanceIdentityCode', $entireInstanceIdentityCode)
            ->with('entireInstance', $entireInstance)
            ->with('barcode', $barcode);
    }

    private function view($viewName = null)
    {
        $viewName = $viewName ?: request()->route()->getActionMethod();
        return "BarCode.{$viewName}";
    }
}
