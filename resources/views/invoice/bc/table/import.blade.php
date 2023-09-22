<div class="col-12 border">
    <div class="card-header">
       Dokumen Import Bea Cukai
    </div>
    <div class="card-body">
                <div class="list-group list-group-horizontal-sm mb-1 text-center" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-pib-list" data-bs-toggle="list" href="#pib" role="tab">SPPB-PIB 2.O</a>
                    <a class="list-group-item list-group-item-action" id="list-bc-list" data-bs-toggle="list" href="#bc" role="tab">SPPB-BC 2.3</a>
                    <a class="list-group-item list-group-item-action" id="list-pea-list" data-bs-toggle="list" href="#pea" role="tab">Dok Pabean Import</a>
                </div>
        <div class="tab-content text-justify" id="load_ini">   
            <div class="tab-pane fade show active" id="pib" role="tabpanel" aria-labelledby="list-pib-list">
                <div class="col-12 border">
                    <div class="card-header">
                    @include('invoice.bc.button.import')   
                    </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No SPPB</th>
                                  <th>Tgl SPPB</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach($sppb as $pib)
                              <tr>
                                  <td>{{$loop->iteration}}</td>                            
                                  <td>{{$pib->NO_SPPB}}</td>
                                  <td>{{$pib->TGL_SPPB}}</td>
                                  <td>{{$pib->NAMA_IMP}}</td>
                                  <td>{{$pib->NPWP_IMP}}</td>
                                  <td>{{$pib->NM_ANGKUT}}</td>
                                  <td>{{$container_SPPB[$pib->CAR]}}</td>
                                  <td>
                                        <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont" data-id="{{$pib->CAR}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
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
            <div class="tab-pane fade" id="bc" role="tabpanel" aria-labelledby="list-bc-list">
                <div class="col-12 border">
                    <div class="card-header">
                    @include('invoice.bc.button.import')   
                    </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1bc">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>No SPPB</th>
                                  <th>Tgl SPPB</th>
                                  <th>Importir</th>
                                  <th>NPWP</th>
                                  <th>Nama Angkut</th>
                                  <th>Jumlah Container</th>
                                  <th>Detail</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach($sppb_bc as $bc)
                              <tr>
                                  <td>{{$loop->iteration}}</td>                            
                                  <td>{{$bc->NO_SPPB}}</td>
                                  <td>{{$bc->TGL_SPPB}}</td>
                                  <td>{{$bc->NAMA_IMP}}</td>
                                  <td>{{$bc->NPWP_IMP}}</td>
                                  <td>{{$bc->NM_ANGKUT}}</td>
                                  <td>{{$bc->JML_CONT}}</td>
                                  <td>
                                  <a href="javascript:void(0)"class="btn icon icon-left btn-outline-info detail-cont" data-id="{{$bc->CAR}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
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
            <div class="tab-pane fade" id="pea" role="tabpanel" aria-labelledby="list-pea-list">
                <div class="col-12 border">
                    <div class="card-header">
                    @include('invoice.bc.button.peaBN')   
                    </div>
                    <div class="card-body">
                        <table class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns" id="table1pea">
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
                            @foreach($pabean_import as $bean)
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
