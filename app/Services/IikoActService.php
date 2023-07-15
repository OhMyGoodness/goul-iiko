<?php

namespace App\Services;

use GuzzleHttp\Client;
use SimpleXMLElement;

class IikoActService
{
    protected array $items = [];
    protected string $dateIncoming = '';
    protected string $status = 'NEW';
    protected string $accountToCode = '2.1.1';
    protected string $revenueAccountCode = '1.1.1';
    protected string $documentNumber = '';

    public function itemAppend($item): void
    {
        $this->items[] = (object)$item;
    }

    public function post()
    {
        $iikoService = new IikoService();
        $xml = new SimpleXMLElement('<document/>');
        $xml->addChild('status', $this->status);
        $xml->addChild('accountToCode', $this->accountToCode);
        $xml->addChild('revenueAccountCode', $this->revenueAccountCode);
        $xml->addChild('dateIncoming', $this->dateIncoming);

        if ($this->documentNumber) {
            $xml->addChild('documentNumber', $this->documentNumber);
        }

        $items = $xml->addChild('items');

        foreach ($this->items as $v) {
            $item = $items->addChild('item');
            $item->addChild('discountSum', $v->discountSum);
            $item->addChild('sum', $v->sum);
            $item->addChild('amount', $v->amount);
            $item->addChild('productId', $v->productId);
            $item->addChild('productArticle', $v->productArticle);
            $item->addChild('storeId', $v->storeId);
        }

        $client = new Client();
        $request = $client->post(config('iiko.url.base') . config('iiko.url.documents.import.salesDocument'), [
            'headers' => [
                'Content-Type' => 'application/xml',
            ],
            'query' => [
                'key' => $iikoService->getToken(),
            ],
            'body' => $xml->asXML(),
        ]);

        echo ($request->getBody()->getContents()); exit;
    }

    public function getDateIncoming(): string
    {
        return $this->dateIncoming;
    }

    public function setDateIncoming(string $dateIncoming): void
    {
        $this->dateIncoming = $dateIncoming;
    }

    public function getAccountToCode(): string
    {
        return $this->accountToCode;
    }

    public function setAccountToCode(string $accountToCode): void
    {
        $this->accountToCode = $accountToCode;
    }

    public function getRevenueAccountCode(): string
    {
        return $this->revenueAccountCode;
    }

    public function setRevenueAccountCode(string $revenueAccountCode): void
    {
        $this->revenueAccountCode = $revenueAccountCode;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(string $documentNumber): void
    {
        $this->documentNumber = $documentNumber;
    }
}
