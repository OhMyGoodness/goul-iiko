<?php

namespace App\Http\Controllers;

use App\Models\Corporation;
use App\Models\Product;
use App\Services\IikoActService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ActController extends Controller
{
    public function post(Request $request, IikoActService $iikoActService)
    {
        $company = $request->get('company', 'Красноярск_1 78я');
        $productId = $request->get('product_id', '50fa8c48-3113-46ca-9a80-bcf14ac17781');
        $discountSum = $request->get('discount_sum', 10);
        $sum = $request->get('sum', 200);
        $amount = $request->get('amount', 1.0);
        $dateIncoming = $request->get('date_incoming', Carbon::now()->format('d.m.Y'));

        $corporation = Corporation::query()
            ->with('stores', function ($query) {
                $query->where('name', 'LIKE', '%сырьё%');
                $query->orderBy('code', 'ASC');
            })
            ->where('name', $company)
            ->first();
        $store = $corporation->stores->first();
        $product = Product::where('_id', $productId)->first();


        $iikoActService->setDateIncoming($dateIncoming);
//        $iikoActService->setDocumentNumber("TEST-API-{$dateIncoming}");
        $iikoActService->itemAppend([
            'discountSum' => $discountSum,
            'sum' => $sum,
            'amount' => $amount,
            'productId' => $product->_id,
            'productArticle' => $product->code,
            'storeId' => $store->_id,
        ]);

        dd($product, $iikoActService->post());
    }
}
