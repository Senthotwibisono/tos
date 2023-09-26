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
function DateOnly($date)
{
    $new_date = date("Y-m-d", strtotime('-0 hours', strtotime($date)));
    return $new_date;
}

function terbilang($angka)
{
    $bilne = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

    if ($angka < 12) {
        return $bilne[$angka];
    } else if ($angka < 20) {
        return terbilang($angka - 10) . " Belas";
    } else if ($angka < 100) {
        return terbilang(floor($angka / 10)) . " Puluh " . terbilang($angka % 10);
    } else if ($angka < 200) {
        return "Seratus " . terbilang($angka - 100);
    } else if ($angka < 1000) {
        return terbilang(floor($angka / 100)) . " Ratus " . terbilang($angka % 100);
    } else if ($angka < 2000) {
        return "Seribu " . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        return terbilang(floor($angka / 1000)) . " Ribu " . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        return terbilang(floor($angka / 1000000)) . " Juta " . terbilang($angka % 1000000);
    } else if ($angka < 1000000000000) {
        return terbilang(floor($angka / 1000000000)) . " Milyar " . terbilang($angka % 1000000000);
    } else if ($angka < 1000000000000000) {
        return terbilang(floor($angka / 1000000000000)) . " Trilyun " . terbilang($angka % 1000000000000);
    }
}
