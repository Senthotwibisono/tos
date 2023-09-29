<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BapleiExc;
use App\Models\Item;
use App\Models\VVoyage;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class EdiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
       // $port_master = Port::all();
       // return view('edi.ediarrival');
    }

    public function receiveedi()
    {
        $title= 'Edi Arrival';
        $vessel_voyage = VVoyage::whereDate('etd_date', '>=', now())->get();
        $item = Item::where('ctr_intern_status','01')->orderBy('update_time', 'desc')->get();

        return view('edi.ediarrival', compact('vessel_voyage','item', 'title'));
    }

    public function get_cont(Request $request)
    {
        $ves_id = $request->ves_id;
        $cont = Item::where('ves_id', $ves_id)->where('ctr_intern_status', '=', '01')->get();

        if ($cont) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Post',
                'data'    => $cont,  
            ]); 
        }
    }

    
    public function receiveeditxt_store(Request $request)
    {
        
        if ($request->hasFile('filetxt')) {
            $extension = $request->filetxt->extension();
            if($extension == 'txt'){
                
                $ves_id = $request->ves_id;             
                $sparator_header = array(
                    'TDT+20', 
                );
              
                $sparator_detail = array(
                    //'LOC+147', //Container Location 
                    'MEA+WT', //Gross,
                    'LOC+6', //Load Port,
                    'LOC+12', //Disch Port
                    'LOC+83', //Fdisch Port,
                    'EQD+CN', //container no, ISO, FCL/MTY,
                    'RFF+BM', //container no, ISO, FCL/MTY,
                    'NAD+CA', //ctr_opr
                );

                $resplace_detail = array(
                      //  '|loc', //Container Location 
                        '|gross', //Gross,
                        '|load_port', //Load Port,
                        '|disch_port', //Disch Port
                        '|fdisch_port', //Fdisch Port,
                        '|container_no', //container no, ISO, FCL/MTY,
                        '|bl_no', //container no, ISO, FCL/MTY,
                        '|ctr_opr', //ctr_opr
                );
                
        
                $flat = \File::get($request->filetxt);
                $datas = explode('LOC+147', $flat);
                
                $results = array();
                $dataFinals = array();

                $i = 0;

                foreach ($datas as $data):
                    if($i == 0){
                        $data = str_replace($sparator_header, '|^', $data); 
                    }else{
                        $data = str_replace($sparator_detail, $resplace_detail, $data); 
                        $dataExplode = explode('|',$data);   

                        $results[] = $dataExplode;
                    }
                    $i++;
                endforeach;
                
               

                foreach($results as $value):  
                    $desc = '';
                    $container_no = '';
                    $data = array();
                    $z=0 ;
                    foreach ($value as $line):
                        
                       // If (substr($line, 0, 3) == "loc" )
                       If ($z==0)
                        {
                         $bay_slot= substr($line, 2, 2);
                         $bay_row= substr($line, 4, 2);
                         $bay_tier= substr($line, 6, 2);
                         $data['bay_slot']=$bay_slot;
                         $data['bay_row']=$bay_row;
                         $data['bay_tier']=$bay_tier;
                       
                         
                        }     
    
                        If (substr($line, 0, 5) == "gross" )
                        {
                         $gross= substr($line, 11, 5);
                         $data['gross']=$gross;
                       
                        }     
    
                        If (substr($line, 0, 9) == "load_port" )
                        {
                         $load_port= substr($line, 10, 5);    
                         $data['load_port']=$load_port;
                                     
                        }     
    
                        If (substr($line, 0, 10) == "disch_port" )
                        {
                         $disch_port= substr($line, 11, 5);  
                         $data['disch_port']=$disch_port;
                                      
                        }     
    
                        If (substr($line, 0, 11) == "fdisch_port" )
                        {
                         $fdisch_port= substr($line, 12, 5);  
                         $data['fdisk_port']=$fdisch_port;
                                      
                        }     
                        If (substr($line, 0, 12) == "container_no" )
                        {
                         $container_no= substr($line, 13, 4).substr($line, 18, 7) ; 
                         $isocode=substr($line, 26, 4);   
                         $size = \App\Models\Isocode::where('iso_code', $isocode)->first();
                         if($size){
                            $ctr_size=$size->iso_size;
                            $ctr_type=$size->iso_type;
                          }else{
                            $ctr_size='20';
                            $ctr_type='DRY';
                        }
                         
                         //$ctr_size='20';
                         If (substr($line, 33, 1) == '5' ){
                         $status= 'FCL';}
                         else {$status='MTY';}            
                         
                         $data['container_no']=$container_no;
                         $data['iso_code']=$isocode;
                         $data['ctr_size']=$size;
                         $data['ctr_status']=$status;
                       
                       
                        }   
    
                        If (substr($line, 0, 7) == "ctr_opr" )
                        {
                         $ctr_opr= substr($line, 8, 3);    
                         $data['ctr_opr']=$ctr_opr;
                         

                        }     
                        
                       

                     $z++;
                    endforeach;




                    if($container_no!=''){
                        $checkAvail = Item::where('container_no',$container_no)->where('ctr_intern_status','01')->count();
                          
  
  
                          if($checkAvail>0){
                            //  return back()->with('error', 'Dupicate Data.')->withInput(); 
                          
                          }
                          else
                          {
                   
                           // return back()->with('error', 'Please upload TXT file format.')->withInput();

                           $ves = \App\Models\VVoyage::where('ves_id', $request->ves_id)->first();
                           if($ves){
                            $ves_name=$ves->ves_name;
                            $voy_no=$ves->voy_out;
                            $ves_code=$ves->ves_code;
                           
                           

                            
                           }
                           $itembayplan = Item::create([
                            'ves_id'=> $request->ves_id,
                            'ves_name'=> $ves_name,
                            'ves_code'=> $ves_code,
                            'voy_no'=> $voy_no,
                            'ctr_i_e_t'=> 'I',                          
                            'disc_load_trans_shift'=>'DISC',
                            'container_no'=> $container_no,
                            'iso_code'=> $isocode,
                            'ctr_size'=> $ctr_size,
                            'ctr_type'=> $ctr_type,
                            'ctr_status'=> $status,
                            'ctr_intern_status'=> '01',
                            'gross'=> $gross,
                            'bay_slot'=> $bay_slot,
                            'bay_row'=> $bay_row,
                            'bay_tier'=> $bay_tier,
                            'load_port'=> $load_port,
                            'disch_port'=> $disch_port,
                            'fdisch_port'=> $fdisch_port,   
                            'ctr_opr'=> $ctr_opr,                           
                            'user_id'=> $request->user_id 
                            //'user_id'=> 'admin'
                              
                           ]);        


                       
                          }
                      }else{
  
                        // return back()->with('error', 'Please upload TXT file format.')->withInput();
                      }

                endforeach;
                return redirect('/edi/receiveedi');
//                return $dataFinals;
                
                       
                //return back()->with('success', 'LCL Register has been update.')->withInput();
//                return json_encode($dataFinals);
             
            }
            //return back()->with('error', 'Please upload TXT file format.')->withInput();
            
        }
        
        //return back()->with('error', 'Cannot upload TXT file, please try again.')->withInput();   




     }



     public function delete_itembayplan($container_key)
     {
         Item::where('container_key',$container_key)->delete();
         return back();
     }


     public function edit_itembayplan(Request $request)
     {
          $container_key=$request->container_key; 
         
         $itembayplan = Item::where('container_key',  $container_key)->first();
  
         if (!$itembayplan) {
             // Data tidak ditemukan
             return response()->json([
                 'success' => true,
                 'message' => 'Data Tidak Ditemukan',
                 'data'    => ''  
             ]); 
       } else {
         return response()->json([
             'success' => true,
             'message' => 'Detail Data Post',
             'data'    => $itembayplan  
         ]); 
        }

     } 

     public function upload(Request $request)
{
    
        $file = $request->file('excel');

        $namaFile = $file->getClientOriginalName();
        $file ->move('BapleiXCL', $namaFile);

        $vesselVoyage = VVoyage::where('ves_id', $request->ves_id)->first();
        $ves_name = $vesselVoyage->ves_name;
        $voy_no = $vesselVoyage->voy_out;
        $ves_code = $vesselVoyage->ves_code;
        $import = new BapleiExc($request->ves_id, $request->user_id,
                                $ves_name,
                                $voy_no,
                                $ves_code,);

        Excel::import($import, public_path('/BapleiXCL/' . $namaFile));
        
 
         return back();
   
}





}    