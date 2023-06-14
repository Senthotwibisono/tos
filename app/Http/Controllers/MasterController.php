<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Port;
use App\Models\VMaster;
use App\Models\Berth;
use App\Models\VService;
use App\Models\Isocode;
use App\Models\Block;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class MasterController extends Controller
{
    
       
    public function index()
    {
       // $port_master = Port::all();
       // return view('master.port', compact('port_master'));
    }

    //Port Master 
    
    public function port()
        {
            $port_master = Port::all();
            return view('master.port', compact('port_master'));
        }

    public function port_store(request $request){
            $request->validate([
                'port' => 'required|max:5'
              ],
            [
                'port.max' => 'Kolom Port ID tidak boleh lebih dari 5 karakter.'
               
            ]);

            
            $port_master = Port::create([
               'port' => $request->port,
               'un_port' => $request->un_port,
               'un_country' => $request->un_country,
               'country_name' => $request->country_name,
               'descr' => $request->descr,
               'user_id' => $request->user_id
               
            ]);              

      
            return redirect('/master/port');
        }
        
        public function port_edit_store(request $request){
            $request->validate([
                'port' => 'required|max:5'
              ],
            [
                'port.max' => 'Kolom Port ID tidak boleh lebih dari 5 karakter.'
               
            ]);

            
            
            $port_master = Port::where('port',$request->port)->update([
               'port' => $request->port,
               'un_port' => $request->un_port,
               'un_country' => $request->un_country,
               'country_name' => $request->country_name,
               'descr' => $request->descr,
               'user_id' => $request->user_id
               
            ]);              

      
            return redirect('/master/port');
        }


        public function delete_port($port)
        {
            Port::where('port',$port)->delete();
            return back();
        }
          
        public function edit_port(Request $request)
        {
             $port=$request->port; 
            
            $port_master = Port::where('port',$port)->first();
            
            if (!$port_master) {
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
                'data'    => $port_master  
            ]); 
           }

        } 
    
       //Vessel Master
        public function vessel()
        {
            $vessel_master = VMaster::all();
            return view('master.vessel', compact('vessel_master'));
        }


        public function vessel_store(request $request){
            $request->validate([
                'ves_code' => 'required|max:4'
              ],
            [
                'ves_code.max' => 'Kolom vesel code tidak boleh lebih dari 4 karakter.'
               
            ]);

            
            $vessel_master = VMaster::create([
               'ves_code' => $request->ves_code,
               'ves_name' => $request->ves_name,
               'agent' => $request->agent,
               'liner' => $request->liner,
               'liner_name' => $request->liner_name,
               'reg_flag' => $request->flag,
               'ocean_interisland' => $request->ves_service,
               'g_r_t' => $request->grt,
               'b_r_t' => $request->brt,
               'l_o_a' => $request->loa,
               'd_w_t' => $request->dwt,
               'n_r_t' => $request->nrt,
               'draft' => $request->draft,
               'ves_length' => $request->vlenght,
               'year_made' => $request->ymade,
               'country_made' => $request->cmade,
               'call_sign' => $request->callsign,
               'lloyds_no' => $request->lno,
               'isps_code' => $request->ispscode,
               'Remark' => $request->Remarks,
               'isps_date' => $request->ispsdate,
               'user_id' => $request->user_id
               
            ]);              

      
            return redirect('/master/vessel');
        }

        public function delete_vessel($vessel)
        {
            VMaster::where('ves_code',$vessel)->delete();
            return back();
        }
        
        public function edit_vessel(Request $request)
        {
             $ves_code=$request->ves_code; 
            
            $vessel_master = VMaster::where('ves_code', $ves_code)->first();
     
            if (!$vessel_master) {
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
                'data'    => $vessel_master  
            ]); 
           }

        } 



        public function vessel_edit_store(request $request){
            $request->validate([
                'ves_code' => 'required|max:4'
              ],
            [
                'port.max' => 'Kolom Port ID tidak boleh lebih dari 4 karakter.'
               
            ]);

            
            
            $vessel_master = VMaster::where('ves_code',$request->ves_code)->update([
                'ves_code' => $request->ves_code,
                'ves_name' => $request->ves_name,
                'agent' => $request->agent,
                'liner' => $request->liner,
                'liner_name' => $request->liner_name,
                'reg_flag' => $request->flag,
                'ocean_interisland' => $request->ves_service,
                'g_r_t' => $request->grt,
                'b_r_t' => $request->brt,
                'l_o_a' => $request->loa,
                'd_w_t' => $request->dwt,
                'n_r_t' => $request->nrt,
                'draft' => $request->draft,
                'ves_length' => $request->vlenght,
                'year_made' => $request->ymade,
                'country_made' => $request->cmade,
                'call_sign' => $request->callsign,
                'lloyds_no' => $request->lno,
                'isps_code' => $request->ispscode,
                'Remark' => $request->Remarks,
                'isps_date' => $request->ispsdate,
                'user_id' => $request->user_id
               
            ]);              

      
            return redirect('/master/vessel');
        }


        //Master Berth 
         public function berth()
         {
             $berth = Berth::all();
             return view('master.berth', compact('berth'));
         }
   

         public function berth_store(request $request){
            $request->validate([
                'berth_no' => 'required|max:5'
              ],
            [
                'berth_no.max' => 'Kolom Port ID tidak boleh lebih dari 5 karakter.'
               
            ]);

            
            $berth_master = berth::create([
               'berth_no' => $request->berth_no,
               'berth_code' => $request->bcode,
               'consturct_type' => $request->ctype,
               'from_length' => $request->flength,
               'to_length' => $request->tlength,
             'ocean_interisland' => $request->ves_service,
               'user_id' => $request->user_id
               
            ]);              

      
            return redirect('/master/berth');
        }
       
        public function delete_berth($berth_no)
        {
            Berth::where('berth_no',$berth_no)->delete();
            return back();
        }

        public function edit_berth(Request $request)
        {
             $berth_no=$request->berth_no; 
            
            $berth = Berth::where('berth_no', $berth_no)->first();
     
            if (!$berth) {
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
                'data'    => $berth  
            ]); 
           }

        } 



        public function berth_edit_store(request $request){
            $request->validate([
                'berth_no' => 'required|max:4'
              ],
            [
                'berth_no.max' => 'Kolom Port ID tidak boleh lebih dari 4 karakter.'
               
            ]);

            
            
            $berth = Berth::where('berth_no',$request->berth_no)->update([
                'berth_no' => $request->berth_no,
                'berth_code' => $request->bcode,
                'consturct_type' => $request->ctype,
                'from_length' => $request->flength,
                'to_length' => $request->tlength,
              'ocean_interisland' => $request->ves_service,
                'user_id' => $request->user_id
                
            ]);              

      
            return redirect('/master/berth');
        }


        

         //Master Vessel Service 
         public function service()
         {
             $vessel_service = VService::all();
             $port_master = Port::all();
             return view('master.service', compact('vessel_service','port_master'));
         }
 

         public function service_store(request $request){
            $request->validate([
                'service_code' => 'required|max:5'
              ],
            [
                'service_code.max' => 'Kolom Port ID tidak boleh lebih dari 5 karakter.'
               
            ]);

            
            $vessel_service = Vservice::create([
               'service' => $request->service_code,
               'disch_port' => $request->disch_port,
               'user_id' => $request->user_id
               
            ]);              

      
            return redirect('/master/service');
        }
       
        public function delete_service($service_id)
        {
            Vservice::where('service_id',$service_id)->delete();
            return back();
        }

        public function edit_service(Request $request)
        {
             $service_id=$request->service_id; 
            
            $vessel_service = Vservice::where('service_id', $service_id)->first();
     
            if (!$vessel_service) {
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
                'data'    => $vessel_service  
            ]); 
           }

        } 



        public function service_edit_store(request $request){
            $request->validate([
                'service_code' => 'required|max:4'
              ],
            [
                'service_code.max' => 'Kolom Port ID tidak boleh lebih dari 4 karakter.'
               
            ]);

            
            $service_id=$request->service_id; 
            $vessel_service = VService::where('service_id',$service_id)->update([
                'service' => $request->service_code,
                'disch_port' => $request->disch_port,
                'user_id' => $request->user_id
                
            ]);              

      
            return redirect('/master/service');
        }



//Master ISO Code 
public function isocode()
{
    $isocode = Isocode::all();
    
    return view('master.isocode', compact('isocode'));
}


public function isocode_store(request $request){
   $request->validate([
       'iso_code' => 'required|max:5'
     ],
   [
       'iso_code.max' => 'Kolom Port ID tidak boleh lebih dari 5 karakter.'
      
   ]);

   
   $isocode = Isocode::create([
    'iso_code'=> $request->iso_code,
    'iso_size'=> $request->iso_size,
    'iso_type'=> $request->iso_type,
    'iso_weight'=> $request->iso_weight,
    'iso_height'=> $request->iso_height,
    'iso_desc'=> $request->descr,
    'user_id'=> $request->user_id 
      
   ]);              


   return redirect('/master/isocode');
    }

    public function delete_isocode($iso_code)
    {
      Isocode::where('iso_code',$iso_code)->delete();
      return back();
    }

    public function edit_isocode(Request $request)
   {
       $iso_code=$request->iso_code; 
   
      $isocode = Isocode::where('iso_code', $iso_code)->first();

   if (!$isocode) {
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
       'data'    => $isocode  
   ]); 
  }

} 



public function isocode_edit_store(request $request){
   $request->validate([
       'iso_code' => 'required|max:4'
     ],
   [
       'iso_code.max' => 'Kolom Port ID tidak boleh lebih dari 4 karakter.'
      
   ]);

   
   $iso_code=$request->iso_code; 
   $isocode = Isocode::where('iso_code',$iso_code)->update([
    'iso_code'=> $request->iso_code,
    'iso_size'=> $request->iso_size,
    'iso_type'=> $request->iso_type,
    'iso_weight'=> $request->iso_weight,
    'iso_height'=> $request->iso_height,
    'iso_desc'=> $request->descr,
    'user_id'=> $request->user_id
       
   ]);              


   return redirect('/master/isocode');
}

        

//Master Yard Block 
public function block()
{
    $block = Block::all();
    
    return view('master.yard_block', compact('block'));
}


public function block_store(request $request){
   $request->validate([
       'yard_block' => 'required|max:5'
     ],
   [
       'yard_block.max' => 'Kolom Port ID tidak boleh lebih dari 5 karakter.'
      
   ]);

  $i=0;
   for($i = 1; $i <= $request->yard_slot; $i++)
   {
      $r=0;  
     for($r = 1; $r <= $request->yard_row; $r++)
        {     
            $t=0;
            for($t = 1; $t <= $request->yard_tier; $t++)
            {                  
             $block = Block::create([
                'yard_block'=> $request->yard_block,
                'yard_slot'=> $i,
                'yard_row'=> $r,
                'yard_tier'=> $t,
                'user_id'=> $request->user_id 
                  
               ]);
            }        
            
        }
    
    }
   

    return redirect('/master/block');
    }

    public function edit_block(Request $request)
    {
        $yard_block=$request->yard_block; 
    
       $block = Block::where('iso_code', $yard_block)->first();
 
    if (!$block) {
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
        'data'    => $block  
    ]); 
   }
 
 } 
 

}
