<?php

namespace App\Http\Controllers\Api\Payment;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Payment\TokenMandiri as Token;
use App\Models\Payment\RefNumber as VA;
use App\Models\Payment\RefDetail;

use App\Http\Controllers\customer\TransactionController;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;
class PaymentController extends Controller
{
    protected $userid;
    protected $key;
    public function __construct() {
        $this->user = 'MANDIRI0234';
        $this->key = '65547a478d66f0cf7b33c6bcf3654d214b3db295';
    }

    public function billPaymentRequest(Request $request)
    {
        $type = $request->type;
        if (!$type) {
            return response()->json([
                'success' => false,
                'status' => '30',
                'message' => 'Type not defined',
            ]);
        }

        if ($type == 'token') {
            return $this->createToken($request);
        }else {
           $headers = $request->headers->all();
           $response = $this->checkTokenAvailable($headers);
           if ($response) {
                return $response; // hentikan proses kalau token tidak valid
            }
            if ($type == 'inquiry') {
                return $this->inquiryService($request);
            }

            if ($type == 'payment') {
                return $this->paymentService($request);
            }
           
        }

        return response()->json([
            'success' => false,
            'status' => '30',
            'message' => 'Type not defined',
        ]);
    }

    private function createToken($request)
    {

        if ($request->userId != $this->user || $request->key != $this->key) {
            return response()->json([
                'success' => false,
                'status' => '28',
                'message' => 'Invalid User/Key for Request Token', 
            ]);
        }

        $expired = Carbon::now()->addHours(6);
        try {
            $token = DB::transaction(function() use($request, $expired) {
                return Token::create([
                    'user_id' => $request->userId,
                    'key' => $request->key,
                    'token'=> $this->generateToken($request),
                    'expired' => $expired,
                ]);      
            });
            return response()->json([
                'type' => 'response_token',
                'success' => true,
                'status' => '00',
                'message' => 'Token Berhasil dibuat',
                'token' => $token->token,
                'expired'=> Carbon::parse($token->expired)->format('YmdHis'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'type' => 'response_token',
                'success' => false,
                'status' => '30',
                'message' => $th->getMessage(),
            ]);
        }
    }

    private function generateToken($request)
    {
        do {
            $generatedToken = substr(hash('sha256', $request->userId . now() . Str::random(10)), 0, 50);
        } while (Token::where('token', $generatedToken)->exists());

        return $generatedToken;
    }

    private function checkTokenAvailable($headers)
    {
        $tokenHeader = $headers['authorization'][0] ?? null;    
        if (Str::startsWith($tokenHeader, 'Bearer ')) {
            $tokenHeader = Str::replaceFirst('Bearer ', '', $tokenHeader);
        }   
        $token = Token::where('token', $tokenHeader)->first();  

        if (!$token) {
            return response()->json([
                'type' => 'response_token',
                'success' => false,
                'status' => '40',
                'message' => 'Invalid Token',
            ], 401);
        }   
        if (Carbon::now()->greaterThan($token->expired)) {
            return response()->json([
                'type' => 'response_token',
                'success' => false,
                'status' => '40',
                'message' => 'Token Expired',
            ], 401);
        }   

        return null;
    }

    private function inquiryService($request)
{
    // Logger khusus inquiry service
    $logger = Log::build([
        'driver' => 'single',
        'path' => storage_path('logs/inquiry-service.log'),
        'level' => 'debug',
    ]);

    $trxid = $request->trxid;

    $context = [
        'trxid' => $trxid,
        'va' => $request->va,
        'bankid' => $request->bankid,
        'merchant_type' => $request->merchant_type,
        'terminal_id' => $request->terminal_id,
        'ip' => $request->ip(),
    ];

    $logger->info('Inquiry dimulai', $context);

    try {
        // Cari virtual account
        $va = VA::where('virtual_account', $request->va)->first();

        if (!$va) {
            $logger->warning('Inquiry gagal: VA tidak ditemukan', $context);

            return response()->json([
                'type' => 'response_inquiry',
                'success' => false,
                'status' => '10',
                'message' => 'VA Tidak ditemukan',
                'trxid' => $trxid,
            ]);
        }

        $context['va_id'] = $va->id;
        $context['va_status'] = $va->status;
        $context['expired_va'] = $va->expired_va;

        $logger->info('VA ditemukan', $context);

        // Periksa masa berlaku VA
        if (!Carbon::parse($va->expired_va)->greaterThan(Carbon::now())) {
            $logger->warning('Inquiry gagal: VA expired', $context);

            return response()->json([
                'type' => 'response_inquiry',
                'success' => false,
                'status' => '10',
                'message' => 'VA Expired',
                'trxid' => $trxid,
            ]);
        }

        // Periksa status VA
        if ($va->status != 'N') {
            $message = ($va->status == 'C')
                ? 'VA Status : Canceled'
                : 'VA Status Paid';

            $logger->warning('Inquiry gagal: status VA bukan N', array_merge(
                $context,
                ['message' => $message]
            ));

            return response()->json([
                'type' => 'response_inquiry',
                'success' => false,
                'status' => 88,
                'message' => $message,
                'trxid' => $trxid,
            ]);
        }

        // Ambil detail proforma
        $detail = RefDetail::where('va_id', $va->id)->get();
        $proforma = $detail->pluck('proforma_no')->implode(', ');

        $logger->info('Detail proforma berhasil diambil', array_merge(
            $context,
            [
                'detail_count' => $detail->count(),
                'proforma' => $proforma,
            ]
        ));

        // Simpan data inquiry
        $inquiryTime = Carbon::parse($request->datetime);

        $va->update([
            'bank_id' => $request->bankid,
            'merchant_type' => $request->merchant_type,
            'terminal_id' => $request->terminal_id,
            'trx_id' => $trxid,
            'inquiry_time' => $inquiryTime,
        ]);

        $logger->info('Inquiry berhasil', array_merge(
            $context,
            [
                'inquiry_time' => $inquiryTime->toDateTimeString(),
                'billing_amount' => $va->billing_amount,
                'detail_count' => $detail->count(),
            ]
        ));

        return response()->json([
            'type' => 'response_inquiry',
            'success' => true,
            'status' => '00',
            'message' => 'Success',
            'trxid' => $trxid,
            'va' => $va->virtual_account,
            'name' => $va->customer_name,
            'customerid' => $va->customer_id,
            'description' => $proforma,
            'billing_amount' => ceil($va->billing_amount),
            'type_trx' => 'c',
        ]);

    } catch (Throwable $e) {
        $logger->error('Inquiry mengalami exception', array_merge(
            $context,
            [
                'exception' => get_class($e),
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]
        ));

        return response()->json([
            'type' => 'response_inquiry',
            'success' => false,
            'status' => '96',
            'message' => 'Internal Server Error',
            'trxid' => $trxid,
        ], 500);
    }
}

    public function paymentService(Request $request)
    {
        // var_dump($request->all());
        // die();
        $va = VA::where('virtual_account', $request->va)->first();
        if ($va) {
            if (Carbon::parse($va->expired_va)->greaterThan(Carbon::now())) {
                if ($va->status != 'N') {
                    return response()->json([
                        'type' => 'response_payment',
                        'success' => false,
                        'status' => 88,
                        'message' => ($va->status == 'C') ? 'VA Status : Canceled' : 'VA Status Paid',
                        'trxid' => $request->trxid,
                    ]);
                }

                $amount = ceil($va->billing_amount);
                if ($amount != $request->payment_amount) {
                    return response()->json([
                        'type' => 'response_payment',
                        'success' => false,
                        'status' => '30',
                        'message' => 'Jumlah Payment tidak valid',
                        'trxid' => $request->trxid,
                    ]);
                }

                $servicePay = app(TransactionController::class)->paymentSuccess($va);
                if ($servicePay == false) {
                    return response()->json([
                        'type' => 'response_payment',
                        'success' => false,
                        'status' => '30',
                        'message' => 'Database ERROR',
                        'trxid' => $request->trxid,
                    ]);
                }

                $va->update([
                    'payment_amount' => $request->payment_amount,
                    'status' => 'Y',
                    'lunas_time' => Carbon::parse($request->datetime),
                ]);
               
                return response()->json([
                    'type' => 'response_payment',
                    'success' => true,
                    'status' => '00',
                    'message' => 'Success',
                    'trxid' => $request->trxid,
                    'reff_no' => 'P' . str_pad($va->id, 10, '0', STR_PAD_LEFT),
                ]);
            } else {
                return response()->json([
                    'type' => 'response_payment',
                    'success' => false,
                    'status' => '10',
                    'message' => 'VA Expired',
                    'trxid' => $request->trxid,
                ]);
            }
        } else {
            return response()->json([
                'type' => 'response_payment',
                'success' => false,
                'status' => '10',
                'message' => 'VA Tidak ditemukan',
                'trxid' => $request->trxid,
            ]);
        }
    }
}
