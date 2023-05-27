<?php

use Config\Services;
use GuzzleHttp\Client;

function singleContainer($id)
{
    $client = new Client();

    $url = 'localhost:3012/container-service/single/' . $id;
    $req = $client->get($url);
    $response = $req->getBody()->getContents();
    $result = json_decode($response);

    // dd($result);

    if ($result->code == 400) {
        return "user not found";
    } else {
        return $result->data;
    }
}

function singleCustomer($id)
{
    $client = new Client();

    $url = 'localhost:3013/delivery-service/customer/single/' . $id;
    $req = $client->get($url);
    $response = $req->getBody()->getContents();
    $result = json_decode($response);

    // dd($result);

    if ($result->code == 400) {
        return "user not found";
    } else {
        return $result->data;
    }
}
