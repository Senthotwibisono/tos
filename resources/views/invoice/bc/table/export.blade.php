<!-- <div class="col-12 border">
  <div class="card-header">
        Dokumen Export Beacukai
      </div>
  <div class="card-body">
        <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
          <a class="list-group-item list-group-item-action active" id="list-exp-list" data-bs-toggle="list" href="#exp" role="tab">Export</a>
          <a class="list-group-item list-group-item-action" id="list-peaExp-list" data-bs-toggle="list" href="#peaExp" role="tab">Dok Pabean Export</a>
        </div>
      <div class="tab-content text-justify" id="load_ini">   
                  <div class="tab-pane fade show active" id="exp" role="tabpanel" aria-labelledby="list-exp-list">
                    <div class="col-12 border">
                        <div class="card-header">
                            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg> Download NPE DOK
                          </button>   
                        </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Dok NPE</th>
                            <th>Tgl Daftar</th>
                            <th>Kode Kantor</th>
                            <th>Nama Eksportir</th>
                            <th>NPWP</th>
                            <th>Jumlah Container</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($details as $np)
                        <tr>
                            <td>{{$loop->iteration}}</td>                            
                            <td>{{$np->NONPE}}</td>
                            <td>{{$np->TGL_DAFTAR}}</td>
                            <td>{{$np->KD_KANTOR}}</td>
                            <td>{{$np->NAMA_EKS}}</td>
                            <td>{{$np->NPWP_EKS}}</td>
                            <td>{{ $container[$np->NONPE]}}</td>
                           
                            <td></td>                         
                        </tr>
                     @endforeach
                    </tbody>
                  </table>
            </div>
          
          </div>
        </div>
        <div class="tab-pane fade" id="peaExp" role="tabpanel" aria-labelledby="list-peaExp-list">
                    <div class="col-12 border">
                        <div class="card-header">
                        @include('invoice.bc.button.peaBNexp')   
                        </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2Pea">
                        <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No Pabean</th>
                                  <th>Tgl Dokumen</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach($pabean_EXP as $bean)
                              <tr>
                                  <td>{{$loop->iteration}}</td>                            
                                  <td>{{$bean->KD_DOK_INOUT}}</td>
                                  <td>{{$bean->TGL_DOK_INOUT}}</td>
                                  <td>{{$bean->NAMA_IMP}}</td>
                                  <td>{{$bean->NPWP_PPJK}}</td>
                                  <td>{{$bean->NM_ANGKUT}}</td>
                                  <td>{{$bean->JML_CONT}}</td>
                                  <td></td>                         
                              </tr>
                           @endforeach
                          </tbody>
                  </table>
            </div>
          </div>
        </div>
      </div>
    </div> -->


    <div class="col-12 border">
    <div class="card-header">
    Dokumen Export Beacukai
    </div>
    <div class="card-body">
                <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-exp-list" data-bs-toggle="list" href="#exp" role="tab">Export</a>
                    <a class="list-group-item list-group-item-action" id="list-peaExp-list" data-bs-toggle="list" href="#peaExp" role="tab">Dok Pabean Export</a>
                </div>
        <div class="tab-content text-justify" id="load_ini">   
            <div class="tab-pane fade show active" id="exp" role="tabpanel" aria-labelledby="list-exp-list">
                <div class="col-12 border">
                    <div class="card-header">
                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg> Download NPE DOK
                          </button>      
                    </div>
                    <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Dok NPE</th>
                            <th>Tgl Daftar</th>
                            <th>Kode Kantor</th>
                            <th>Nama Eksportir</th>
                            <th>NPWP</th>
                            <th>Jumlah Container</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($details as $np)
                        <tr>
                            <td>{{$loop->iteration}}</td>                            
                            <td>{{$np->NONPE}}</td>
                            <td>{{$np->TGL_DAFTAR}}</td>
                            <td>{{$np->KD_KANTOR}}</td>
                            <td>{{$np->NAMA_EKS}}</td>
                            <td>{{$np->NPWP_EKS}}</td>
                            <td>{{ $container[$np->NONPE]}}</td>
                            <td>
                            <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont-exp" data-id="{{$np->NO_DAFTAR}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Detail</a>
                            </td>                         
                        </tr>
                     @endforeach
                    </tbody>
                  </table>
                    </div>

                </div>
            </div>
            <div class="tab-pane fade" id="peaExp" role="tabpanel" aria-labelledby="list-peaExp-list">
                <div class="col-12 border">
                    <div class="card-header">
                    @include('invoice.bc.button.peaBNexp')  
                    </div>
                    <div class="card-body">
                    <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table2Pea">
                        <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No Pabean</th>
                                  <th>Tgl Dokumen</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach($pabean_EXP as $bean)
                              <tr>
                                  <td>{{$loop->iteration}}</td>                            
                                  <td>{{$bean->KD_DOK_INOUT}}</td>
                                  <td>{{$bean->TGL_DOK_INOUT}}</td>
                                  <td>{{$bean->NAMA_IMP}}</td>
                                  <td>{{$bean->NPWP_PPJK}}</td>
                                  <td>{{$bean->NM_ANGKUT}}</td>
                                  <td>{{$bean->JML_CONT}}</td>
                                  <td>
                                  <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont" data-id="{{$bean->CAR}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Detail</a>
                                  </td>                         
                              </tr>
                           @endforeach
                          </tbody>
                  </table>
                    </div>

                </div>
            </div>
           
        </div>          
    </div>
</div>
