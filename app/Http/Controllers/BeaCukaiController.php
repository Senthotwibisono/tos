<?php

namespace App\Http\Controllers;

use Auth;
use Config\Services;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Http\Controllers\DataExport;
use App\Models\TpsSppbPib; // check doc number import
use App\Models\TpsSppbBc; // check doc import bc
use App\Models\TpsDokPabean; // check doc lainya untuk import & export
use App\Models\TpsSppbPibCont; // check container
use App\Models\TpsSppbBcCont; // check container bc
use App\Models\TpsDokPabeanCont; // check container pabean untuk import & export
use App\Models\TpsDokPKBE; // check container pabean untuk import & export
use App\Models\KodeDok;

use App\Models\TpsDokNPE; // check doc number && container export


// cari doc number, type & date
// 1. check TpsSppbPib (NO_SPPB)
// 2. kalo ga nemu ke yg TpsSppbBc (NO_SPPB)
// 3. kalo masi ga nemu ke yg TpsDokPabean (NO_DOC_INOUT)

// cari container
// 1. check TpsSppbPibCont (_CONT)
// 2. kalo ga nemu ke yg TpsSppbBcCont (_CONT)
// 3. kalo masi ga nemu ke yg TpsDokPabeanCont (_CONT)

class BeaCukaiController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // START OF BEACUKAI CHECK IMPORT WITHOUT OPTIMIZATION
  public function test2()
  {
    $docNo = "396956/KPU.01/2021";
    $dataDoc = [];

    $uniqueSppbs = TpsSppbPib::where('NO_SPPB', '=', $docNo)
      ->groupBy('NO_SPPB')
      ->selectRaw('NO_SPPB, MAX(TPS_SPPBXML_PK) AS max_id')
      ->get();

    $checkdocIds = $uniqueSppbs->pluck('max_id');

    $checkdoc = TpsSppbPib::whereIn('TPS_SPPBXML_PK', $checkdocIds)
      ->get();
    // CHECK FIRST DOC TO TpsSppbPib
    if ($checkdoc->isNotEmpty()) {
      // CHECK FIRST CONT TO TpsSppbPibCont
      // dd("ada euy TpsSppbPib");
      $dataDoc["docNo"] = $checkdoc[0]->NO_SPPB;
      $dataDoc["docType"] = "DOCTYPESPPB";
      $dataDoc["docDate"] = $checkdoc[0]->TGL_SPPB;
      $dataDoc["docCAR"] = $checkdoc[0]->CAR;
      $dataDoc["docResult"] = "FOUND DOC ON TpsSppbPib";
      // $dataDoc["docCAR"] = "00000000660520210805901498";
      // dd($docNo, $docType, $docCAR);

      $distinctCAR = TpsSppbPibCont::where('CAR', '=', $dataDoc["docCAR"])
        ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
        ->distinct()
        ->get();

      // dd($distinctCAR);
      if ($distinctCAR->isEmpty()) {
        $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsSppbPibCont";
        // CHECK SECOND CONT TO TpsSppbBcCont
        // $dataDoc["docCAR"] = "05092301220520210816108223";
        $distinctCAR = TpsSppbBcCont::where('CAR', '=', $dataDoc["docCAR"])
          ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
          ->distinct()
          ->get();
        if ($distinctCAR->isEmpty()) {
          // CHECK THIRD CONT TO TpsDokPabeanCont
          $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsSppbBcCont";

          // dd("empty cont bc");
          // $dataDoc["docCAR"] = "05091602019420210819002698";
          $distinctCAR = TpsDokPabeanCont::where('CAR', '=', $dataDoc["docCAR"])
            ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
            ->distinct()
            ->get();
          if ($distinctCAR->isEmpty()) {
            // dd("empty cont pabean");
            $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsDokPabeanCont";
          } else if ($distinctCAR->isNotEmpty()) {
            $contArr = [];
            foreach ($distinctCAR as $dataCont) {
              array_push($contArr, $dataCont->NO_CONT);
            }
            // dd($contArr);
            $dataDoc["contResult"] = "FOUND CONTAINER ON TpsDokPabeanCont";
          }
        } else if ($distinctCAR->isNotEmpty()) {
          $contArr = [];
          foreach ($distinctCAR as $dataCont) {
            array_push($contArr, $dataCont->NO_CONT);
          }
          // dd($contArr);
          $dataDoc["contResult"] = "FOUND CONTAINER ON TpsSppbBcCont";
        }
      } else if ($distinctCAR->isNotEmpty()) {
        $contArr = [];
        foreach ($distinctCAR as $dataCont) {
          array_push($contArr, $dataCont->NO_CONT);
        }
        $dataDoc["contResult"] = "FOUND CONTAINER ON TpsSppbPibCont";
      }
    } else if ($checkdoc->isEmpty()) {
      // CHECK SECOND DOC TO TpsSppbBc
      $uniqueSppbs = TpsSppbBc::where('NO_SPPB', '=', $docNo)
        ->groupBy('NO_SPPB')
        ->selectRaw('NO_SPPB, MAX(TPS_SPPBXML_PK) AS max_id')
        ->get();

      $checkdoc = TpsSppbBc::whereIn('TPS_SPPBXML_PK', $checkdocIds)
        ->get();

      if ($checkdoc->isNotEmpty()) {
        // dd("ada euy TpsSppbBc");
        $dataDoc["docNo"] = $checkdoc[0]->NO_SPPB;
        $dataDoc["docType"] = "DOCTYPESPPB";
        $dataDoc["docDate"] = $checkdoc[0]->TGL_SPPB;
        $dataDoc["docCAR"] = $checkdoc[0]->CAR;
        $dataDoc["docResult"] = "FOUND DOC ON TpsSppbPib";
        // $dataDoc["docCAR"] = "00000000660520210805901498";
        // dd($docNo, $docType, $docCAR);

        $distinctCAR = TpsSppbPibCont::where('CAR', '=', $dataDoc["docCAR"])
          ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
          ->distinct()
          ->get();

        // dd($distinctCAR);
        if ($distinctCAR->isEmpty()) {
          $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsSppbPibCont";
          // CHECK SECOND CONT TO TpsSppbBcCont
          // $dataDoc["docCAR"] = "05092301220520210816108223";
          $distinctCAR = TpsSppbBcCont::where('CAR', '=', $dataDoc["docCAR"])
            ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
            ->distinct()
            ->get();
          if ($distinctCAR->isEmpty()) {
            // CHECK THIRD CONT TO TpsDokPabeanCont
            $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsSppbBcCont";

            // dd("empty cont bc");
            // $dataDoc["docCAR"] = "05091602019420210819002698";
            $distinctCAR = TpsDokPabeanCont::where('CAR', '=', $dataDoc["docCAR"])
              ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
              ->distinct()
              ->get();
            if ($distinctCAR->isEmpty()) {
              // dd("empty cont pabean");
              $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsDokPabeanCont";
            } else if ($distinctCAR->isNotEmpty()) {
              $contArr = [];
              foreach ($distinctCAR as $dataCont) {
                array_push($contArr, $dataCont->NO_CONT);
              }
              // dd($contArr);
              $dataDoc["contResult"] = "FOUND CONTAINER ON TpsDokPabeanCont";
            }
          } else if ($distinctCAR->isNotEmpty()) {
            $contArr = [];
            foreach ($distinctCAR as $dataCont) {
              array_push($contArr, $dataCont->NO_CONT);
            }
            // dd($contArr);
            $dataDoc["contResult"] = "FOUND CONTAINER ON TpsSppbBcCont";
          }
        } else if ($distinctCAR->isNotEmpty()) {
          $contArr = [];
          foreach ($distinctCAR as $dataCont) {
            array_push($contArr, $dataCont->NO_CONT);
          }
          $dataDoc["contResult"] = "FOUND CONTAINER ON TpsSppbPibCont";
        }
      } else if ($checkdoc->isEmpty()) {
        // CHECK THIRD DOC TO TpsDokPabean
        $uniqueSppbs = TpsDokPabean::where('NO_DOK_INOUT', '=', $docNo)
          ->groupBy('NO_DOK_INOUT')
          ->selectRaw('NO_DOK_INOUT, MAX(TPS_DOKPABEANXML_PK) AS max_id')
          ->get();
        $checkdoc = TpsDokPabean::whereIn('TPS_DOKPABEANXML_PK', $checkdocIds)
          ->get();
        if ($checkdoc->isNotEmpty()) {
          // dd("ada euy TpsDokPabean");
          $dataDoc["docNo"] = $checkdoc[0]->NO_SPPB;
          $dataDoc["docType"] = "DOCTYPESPPB";
          $dataDoc["docDate"] = $checkdoc[0]->TGL_SPPB;
          $dataDoc["docCAR"] = $checkdoc[0]->CAR;
          $dataDoc["docResult"] = "FOUND DOC ON TpsSppbPib";
          // $dataDoc["docCAR"] = "00000000660520210805901498";
          // dd($docNo, $docType, $docCAR);

          $distinctCAR = TpsSppbPibCont::where('CAR', '=', $dataDoc["docCAR"])
            ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
            ->distinct()
            ->get();

          // dd($distinctCAR);
          if ($distinctCAR->isEmpty()) {
            $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsSppbPibCont";
            // CHECK SECOND CONT TO TpsSppbBcCont
            // $dataDoc["docCAR"] = "05092301220520210816108223";
            $distinctCAR = TpsSppbBcCont::where('CAR', '=', $dataDoc["docCAR"])
              ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
              ->distinct()
              ->get();
            if ($distinctCAR->isEmpty()) {
              // CHECK THIRD CONT TO TpsDokPabeanCont
              $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsSppbBcCont";

              // dd("empty cont bc");
              // $dataDoc["docCAR"] = "05091602019420210819002698";
              $distinctCAR = TpsDokPabeanCont::where('CAR', '=', $dataDoc["docCAR"])
                ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
                ->distinct()
                ->get();
              if ($distinctCAR->isEmpty()) {
                // dd("empty cont pabean");
                $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsDokPabeanCont";
              } else if ($distinctCAR->isNotEmpty()) {
                $contArr = [];
                foreach ($distinctCAR as $dataCont) {
                  array_push($contArr, $dataCont->NO_CONT);
                }
                // dd($contArr);
                $dataDoc["contResult"] = "FOUND CONTAINER ON TpsDokPabeanCont";
              }
            } else if ($distinctCAR->isNotEmpty()) {
              $contArr = [];
              foreach ($distinctCAR as $dataCont) {
                array_push($contArr, $dataCont->NO_CONT);
              }
              // dd($contArr);
              $dataDoc["contResult"] = "FOUND CONTAINER ON TpsSppbBcCont";
            }
          } else if ($distinctCAR->isNotEmpty()) {
            $contArr = [];
            foreach ($distinctCAR as $dataCont) {
              array_push($contArr, $dataCont->NO_CONT);
            }
            $dataDoc["contResult"] = "FOUND CONTAINER ON TpsSppbPibCont";
          }
        } else if ($checkdoc->isEmpty()) {
          // dd("final search is null on pabean");
          $dataDoc["docResult"] = "DIDN'T FOUND ANY DOC ON ANY BEACUKAI";
        }
      }
    }
    dd($dataDoc, $contArr);
  }
  // END OF BEACUKAI CHECK IMPORT WITHOUT OPTIMIZATION

  // START OF BEACUKAI CHECK IMPORT WITH OPTIMIZATION
  public function beacukaiImportCheck(Request $request)
  {

    $docNo = $request->docNumber;
    $dataDoc = [];
    $contArr = [];

    // Define the models to check in a specific order
    $modelsToCheck = [
      TpsSppbPib::class,
      TpsSppbBc::class,
      TpsDokPabean::class,
    ];

    foreach ($modelsToCheck as $modelClass) {
      $model = new $modelClass;
      $modelShortName = class_basename($modelClass); // Get short name of the model

      if ($modelShortName == "TpsDokPabean") {
        $uniqueDocs = $model->where('NO_DOK_INOUT', '=', $docNo)
          ->groupBy('NO_DOK_INOUT')
          ->selectRaw('NO_DOK_INOUT, MAX(TPS_DOKPABEANXML_PK) AS max_id')
          ->get();
        $checkdoc = $model->whereIn('TPS_DOKPABEANXML_PK', $uniqueDocs->pluck('max_id'))
          ->get();
      } else {
        $uniqueDocs = $model->where('NO_SPPB', '=', $docNo)
          ->groupBy('NO_SPPB')
          ->selectRaw('NO_SPPB, MAX(TPS_SPPBXML_PK) AS max_id')
          ->get();
        $checkdoc = $model->whereIn('TPS_SPPBXML_PK', $uniqueDocs->pluck('max_id'))
          ->get();
      }

      if ($checkdoc->isNotEmpty()) {
        if ($modelShortName == "TpsDokPabean") {
          $dataDoc["docNo"] = $checkdoc[0]->NO_DOK_INOUT;
        } else {
          $dataDoc["docNo"] = $checkdoc[0]->NO_SPPB;
          // $dataDoc["kdDok"] = $checkdoc[0]->KD_DOK_INOUT;
        }
        if ($modelShortName == "TpsSppbPib") {
          $dataDoc["docType"] = "SPPBBICBC2.0";
        } else if ($modelShortName == "TpsSppbBc") {
          $dataDoc["docType"] = "SPPBBC2.3";
        } else if ($modelShortName == "TpsDokPabean") {
          $checkKdDoc = KodeDok::where('kode', '=', $checkdoc[0]->KD_DOK_INOUT)->get();

          $dataDoc["docType"] = $checkKdDoc[0]->name ?? "Data Tidak Ditemukan";
        }
        $dataDoc["docDate"] = $checkdoc[0]->TGL_SPPB ?? date("d-m-Y", strtotime($checkdoc[0]->TGL_DOK_INOUT));
        $dataDoc["docCAR"] = $checkdoc[0]->CAR;
        $dataDoc["docResultTrueStatus"] = true;
        $dataDoc["docResultFalseStatus"] = false;

        $contModelClass = $modelClass . 'Cont';
        $distinctCAR = $contModelClass::where('CAR', '=', $dataDoc["docCAR"])
          ->select('CAR', 'NO_CONT')
          ->distinct()
          ->get();

        if ($distinctCAR->isEmpty()) {
          $dataDoc["contResult"] = "NOT FOUND CONTAINER ON " . $modelShortName . "Cont";
          $dataDoc["contResultFalseStatus"] = true;
          $dataDoc["contResultTrueStatus"] = false;
        } else {
          foreach ($distinctCAR as $dataCont) {
            array_push($contArr, $dataCont->NO_CONT);
          }
          $dataDoc["contResult"] = "FOUND CONTAINER ON " . $modelShortName . "Cont";
          $dataDoc["contResultTrueStatus"] = true;
          $dataDoc["contResultFalseStatus"] = false;
        }
        break; // Exit loop once a match is found
      }
    }

    if (empty($dataDoc)) {
      $dataDoc["docResult"] = "DIDN'T FIND ANY DOC ON ANY BEACUKAI";
      $dataDoc["docResultFalseStatus"] = true;
      $dataDoc["docResultTrueStatus"] = false;
    }

    $resBeacukai = [$dataDoc, $contArr];
    $JSONres = json_encode($resBeacukai);
    echo $JSONres;
  }
  // END OF BEACUKAI CHECK IMPORT WITH OPTIMIZATION


  // START OF BEACUKAI CHECK EXPORT WITHOUT OPTIMIZATION
  public function beacukaiExportCheck(Request $request)
  {

    $docNo = $request->docNumber;
    // $docNo = "430730/KPU.1/2023"; //  test TPSDOKNPE
    // $docNo = "002914/WBC.09/KPP.MP.01/2021"; //  test TPSDOKPABEAN
    $dataDoc = [];
    $contArr = [];



    $uniqueSppbs = TpsDokNPE::select('NONPE', 'NO_CONT', 'TGLNPE')
      ->where('NONPE', '=', $docNo)
      ->groupBy('NONPE', 'NO_CONT', 'TGLNPE')
      ->get();
    if ($uniqueSppbs->isNotEmpty()) {
      $groupedData = [];

      foreach ($uniqueSppbs as $sppb) {
        $nonpe = $sppb->NONPE;
        $noCont = $sppb->NO_CONT;

        if (!isset($groupedData[$nonpe])) {
          $groupedData[$nonpe] = [];
        }

        if (!in_array($noCont, $groupedData[$nonpe])) {
          $groupedData[$nonpe][] = $noCont;
        }
      }
      // dd($groupedData, $uniqueSppbs);
      $dataDoc["docNo"] = $uniqueSppbs[0]->NONPE;
      $dataDoc["docDate"] = date("d-m-Y", strtotime($uniqueSppbs[0]->TGLNPE));
      $dataDoc["docType"] = "NPE";
      $dataDoc["docCAR"] = $groupedData[$nonpe];
      $dataDoc["docResultTrueStatus"] = true;
      $dataDoc["docResultFalseStatus"] = false;
      $dataDoc["contResult"] = "FOUND CONTAINER ON TpsDokNPE";
      $dataDoc["contResultTrueStatus"] = true;
      $dataDoc["contResultFalseStatus"] = false;
      foreach ($groupedData[$nonpe] as $dataCont) {
        array_push($contArr, $dataCont);
      }
      // dd($dataDoc);
    } else if ($uniqueSppbs->isEmpty()) {
      $uniqueSppbs = TpsDokPabean::where('NO_DOK_INOUT', '=', $docNo)
        ->groupBy('NO_DOK_INOUT')
        ->selectRaw('NO_DOK_INOUT, MAX(TPS_DOKPABEANXML_PK) AS max_id')
        ->get();
      $checkdocIds = $uniqueSppbs->pluck('max_id');

      $checkdoc = TpsDokPabean::whereIn('TPS_DOKPABEANXML_PK', $checkdocIds)
        ->get();
      // dd($checkdoc[0]->CAR);
      if ($checkdoc->isNotEmpty()) {
        $checkKdDoc = KodeDok::where('kode', '=', $checkdoc[0]->KD_DOK_INOUT)->get();
        $dataDoc["docCAR"] = $checkdoc[0]->CAR;
        $dataDoc["docType"] = $checkKdDoc[0]->name ?? "Data Tidak Ditemukan";
        $dataDoc["docNo"] = $checkdoc[0]->NO_DOK_INOUT;

        $distinctCAR = TpsDokPabeanCont::where('CAR', '=', $dataDoc["docCAR"])
          ->select('CAR', 'NO_CONT') // Replace 'other_column' with the actual column name
          ->distinct()
          ->get();
        $dataDoc["docCAR"] = $checkdoc[0]->CAR;
        $dataDoc["docResultTrueStatus"] = true;
        $dataDoc["docResultFalseStatus"] = false;
        if ($distinctCAR->isEmpty()) {
          $dataDoc["contResult"] = "NOT FOUND CONTAINER ON TpsDokPabeanCont";
          $dataDoc["contResultFalseStatus"] = true;
          $dataDoc["contResultTrueStatus"] = false;
        } else {
          foreach ($distinctCAR as $dataCont) {
            array_push($contArr, $dataCont->NO_CONT);
          }
          $dataDoc["contResult"] = "FOUND CONTAINER ON TpsDokPabeanCont";
          $dataDoc["contResultTrueStatus"] = true;
          $dataDoc["contResultFalseStatus"] = false;
        }
      } else {
        $dataDoc["docResult"] = "DIDN'T FIND ANY DOC ON ANY BEACUKAI";
        $dataDoc["docResultFalseStatus"] = true;
        $dataDoc["docResultTrueStatus"] = false;
      }
      // dd("CAR", $distinctCAR);
    } else {
      $dataDoc["docResult"] = "DIDN'T FIND ANY DOC ON ANY BEACUKAI";
      $dataDoc["docResultFalseStatus"] = true;
      $dataDoc["docResultTrueStatus"] = false;
    }

    // dd($dataDoc, $contArr);
    $resBeacukai = [$dataDoc, $contArr];
    $JSONres = json_encode($resBeacukai);
    echo $JSONres;
    // dd($uniqueSppbs, $groupedData);
  }
  // END OF BEACUKAI CHECK EXPORT WITHOUT OPTIMIZATION

}
