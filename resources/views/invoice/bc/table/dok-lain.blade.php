<div class="col-12 border">
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
                            <td></td>                         
                        </tr>
                     @endforeach
                    </tbody>
                  </table>
                            </div>
                            </div>