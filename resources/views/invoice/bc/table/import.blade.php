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
                                  <td>{{ $container_SPPB[$pib->CAR]}}</td>
                                  <td></td>                         
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
                                  <td></td>                         
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
                                  <td></td>                         
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