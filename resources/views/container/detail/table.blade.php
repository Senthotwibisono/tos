<table class="dataTable-wrapper dataTable-selector dataTable-loading no-footer sortable searchable fixed-columns" id="table1">
                        <thead>
                            <tr>
                                <th>Container No</th>
                                <th>Import/Export</th>
                                <th>Size</th>
                                <th>Type</th>
                                <th>Gross</th>
                                <th>Status</th>
                                <th>Intern Status</th>
                                <th>Operator(MLO)</th>
                                <th>Is Damage</th>
                                <th>POL</th>
                                <th>POD</th>
                                <th>Seal</th>
                                <th>Stowage</th>
                                <th>Discharge/Load Date</th>
                                <th>Yard Position</th>
                                <th>Placement Date</th>
                                <th>Truck No</th>
                                <th>Gate In Delivery</th>
                                <th>Gate Out Delivery</th>
                                <th>Gate In Reciving</th>
                                <th>Gate Out Reciving</th>
                                <th>Customer</th>
                                <th>History</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($containers as $item)
                            <tr>
                                <td>{{$item->container_no}}</td>
                                <td>
                                    @if($item->ctr_i_e_t == "I")    
                                        Import
                                    @else
                                        Export
                                    @endif
                                </td>
                                <td>{{$item->ctr_size}}</td>
                                <td>{{$item->ctr_type}}</td>
                                <td>{{$item->gross}}</td>
                                <td>{{$item->ctr_status}}</td>
                                <td>{{$item->ctr_intern_status}}</td>
                                <td>{{$item->ctr_opr}}</td>
                                <td>
                                    @if($item->is_damage == "Y")    
                                        Damaged
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>{{$item->disch_port}}</td>
                                <td>{{$item->load_port}}</td>
                                <td>{{$item->seal_no}}</td>
                                <td>{{$item->bay_slot}}{{$item->bay_row}}{{$item->bay_tier}}</td>
                                <td>
                                    @if($item->ctr_i_e_t == "I")    
                                        {{$item->disc_date}}
                                    @else
                                        {{$item->load_date}}
                                    @endif
                                </td>
                                <td>{{$item->yard_block}}{{$item->yard_slot}}{{$item->yard_row}}{{$item->yard_tier}}</td>
                                <td>{{$item->PLC->created_at ?? '' }}</td>
                                <td>{{$item->truck_no}}</td>
                                @if($item->ctr_i_e_t == "I")
                                <td>{{$item->truck_in_date}}</td>
                                <td>{{$item->truck_out_date}}</td>
                                @else
                                <td> </td>
                                <td> </td>
                                @endif
                                @if($item->ctr_i_e_t == "E")
                                <td>{{$item->truck_in_date}}</td>
                                <td>{{$item->truck_out_date}}</td>
                                @else
                                <td> </td>
                                <td> </td>
                                @endif
                                <td>{{$item->CtrInv->Form->customer->name ?? ''}}</td>
                                <td>
                                    <a href="javascript:void(0)" onclick="openWindow('/container/history/{{$item->container_key}}')" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                </td>
                                <td>
                                    <a href="/container/edit-container/{{$item->container_key}}" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>