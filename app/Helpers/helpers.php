<?php

use Config\Services;
use GuzzleHttp\Client;

use Symfony\Component\HttpFoundation\Response;



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

function rupiah($nominal = '')
{
    return number_format($nominal, 0, ',', '.');
}

function DateTimeFormat($date)
{
    $new_date = date("d F Y H:i", strtotime('-0 hours', strtotime($date)));
    return $new_date;
}

function DateFormat($date)
{
    $new_date = date("d F Y", strtotime('-0 hours', strtotime($date)));
    return $new_date;
}
