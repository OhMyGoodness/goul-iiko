<?php

namespace App\Services;

use App\Models\Corporation;
use App\Models\Product;
use App\Models\Store;
use App\Models\System;
use Carbon\Carbon;
use GuzzleHttp\Client;

class IikoService
{
    public function getToken(): ?string
    {
        $tokenLifetime = System::where('key', 'token_lifetime')->first()->value;
        $token = System::where('key', 'token')->first()->value;


        if (Carbon::parse($tokenLifetime) > Carbon::now()) {
            return $token;
        } else {
            $client = new Client();
            $request = $client->get(config('iiko.url.base') . config('iiko.url.auth'), [
                'query' => [
                    'login' => config('iiko.login'),
                    'pass' => config('iiko.password'),
                ],
            ]);

            $token = $request->getBody()->getContents();

            System::where('key', 'token')->update(['value' => $token]);
            System::where('key', 'token_lifetime')->update([
                'value' => Carbon::now()->addHour()->format('Y-m-d H:i:s'),
            ]);

            return $token;
        }
    }

    public function getCorporations(): ?array
    {
        $client = new Client();
        $request = $client->get(config('iiko.url.base') . config('iiko.url.corporation.departments'), [
            'query' => [
                'key' => $this->getToken(),
            ],
        ]);

        $result = [];
        $response = simplexml_load_string($request->getBody()->getContents());

        foreach ($response->corporateItemDto as $v) {
            if (in_array(mb_strtoupper($v->type), ['DEPARTMENT', 'STORE'])) {
                $result[] = [
                    '_id' => (string)$v->id,
                    'name' => (string)$v->name,
                    'type' => (string)$v->type,
                ];
            }
        }

        return $result;
    }

    public function updateCorporations(): void
    {
        foreach ($this->getCorporations() as $v) {
            Corporation::updateOrCreate(['_id' => $v['_id']], $v);
        }
    }

    public function getProducts(): ?array
    {
        $client = new Client();
        $request = $client->get(config('iiko.url.base') . config('iiko.url.entities.products.list'), [
            'query' => [
                'key' => $this->getToken(),
                'includeDeleted' => false,
            ],
        ]);

        $response = json_decode($request->getBody()->getContents(), false);
        $result = [];

        foreach ($response as $v) {
            $result[] = [
                '_id' => (string)$v->id,
                'name' => (string)$v->name,
                'num' => (string)$v->num,
                'code' => (int)$v->code,
                'main_unit' => (string)$v->mainUnit,
                'type' => (string)$v->type,
            ];
        }

        return $result;
    }

    public function updateProducts(): void
    {
        foreach ($this->getProducts() as $v) {
            Product::updateOrCreate(['_id' => $v['_id']], $v);
        }
    }

    public function getStores(): ?array
    {
        $client = new Client();
        $request = $client->get(config('iiko.url.base') . config('iiko.url.corporation.stores'), [
            'query' => [
                'key' => $this->getToken(),
            ],
        ]);

        $result = [];
        $response = simplexml_load_string($request->getBody()->getContents());

        foreach ($response->corporateItemDto as $v) {
            $result[] = [
                '_id' => (string)$v->id,
                'parent_id' => (string)$v->parentId,
                'code' => (string)$v->code,
                'name' => (string)$v->name,
                'type' => (string)$v->type,
            ];
        }

        return $result;
    }

    public function updateStores(): void
    {
        foreach ($this->getStores() as $v) {
            Store::updateOrCreate(['_id' => $v['_id']], $v);
        }
    }
}
