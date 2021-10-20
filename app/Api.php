<?php

namespace App;

use RetailCrm\Api\Interfaces\ClientExceptionInterface;
use RetailCrm\Api\Enum\CountryCodeIso3166;
use RetailCrm\Api\Enum\Customers\CustomerType;
use RetailCrm\Api\Factory\SimpleClientFactory;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use RetailCrm\Api\Model\Entity\Orders\Delivery\OrderDeliveryAddress;
use RetailCrm\Api\Model\Entity\Orders\Delivery\SerializedOrderDelivery;
use RetailCrm\Api\Model\Entity\Orders\Items\Offer;
use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct;
use RetailCrm\Api\Model\Entity\Orders\Items\PriceType;
use RetailCrm\Api\Model\Entity\Orders\Items\Unit;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Entity\Orders\Payment;
use RetailCrm\Api\Model\Entity\Orders\SerializedRelationCustomer;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;

class Api 
{
    public function post()
    {
        $client = SimpleClientFactory::createClient('https://superposuda.retailcrm.ru/api/v5/orders/create', 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb');

        $request         = new OrdersCreateRequest();
        $order           = new Order();
        $offer           = new Offer();
        $item            = new OrderProduct();

        $offer->name        = 'Маникюрный набор AZ105R Azalita';
        $offer->displayName = 'Маникюрный набор AZ105R Azalita';

        $item->offer         = $offer;
        $item->priceType     = new PriceType('base');
        $item->quantity      = 1;
        $item->purchasePrice = 60;

        $order->items         = [$item];
        $order->orderType     = 'fizik';
        $order->orderMethod   = 'phone';
        $order->countryIso    = CountryCodeIso3166::RUSSIAN_FEDERATION;
        $order->firstName     = 'Телешов';
        $order->lastName      = 'Захар';
        $order->patronymic    = 'Patronymic';
        $order->phone         = '89003005069';
        $order->email         = 'testuser12345678901@example.com';
        $order->managerId     = 28;
        $order->customer      = SerializedRelationCustomer::withIdAndType(
            4924,
            CustomerType::CUSTOMER
        );
        $order->status        = 'assembling';
        $order->statusComment = 'Assembling order';
        $order->weight        = 1000;
        $order->shipmentStore = 'main12';
        $order->shipped       = false;
        $order->customFields  = [
            "order_number" => 21051996,
            "prim" => 'тестовое задание',
            "comment" => 'https://github.com/zadochek/testApi.git',
        ];

        $request->order = $order;
        $request->site  = 'test';

        try {
            $response = $client->orders->create($request);
        } catch (ApiExceptionInterface | ClientExceptionInterface $exception) {
            echo $exception; // Every ApiExceptionInterface instance should implement __toString() method.
            exit(-1);
        }

        printf(
            'Created order id = %d with the following data: %s',
            $response->id,
            print_r($response->order, true)
        );
    }
}