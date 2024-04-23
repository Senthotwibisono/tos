<ul class="menu">
    @if(Auth::user() && !Auth::user()->hasRole('BeaCukai'))
    <li class="sidebar-title">Menu</li>

    <li class="sidebar-item @if(Request::is('dashboard') || Request::is('/')) active @endif">
        <a href="/dashboard" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li>


    <!-- planning -->

    <li class="sidebar-item  has-sub @if(Request::is('planning/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="fa-solid fa-ship"></i>
            <span>Planning</span>
        </a>
        <ul class="submenu @if(Request::is('planning/*')) active @endif">
            <li class="submenu-item @if(Request::is('planning/vessel-schedule') || Request::is('planning/create-schedule') || Request::is('planning/schedule_schedule=*')) active @endif">
                <a href="/planning/vessel-schedule">Vessel Schedule</a>
            </li>
            <!-- <li class="submenu-item ">
                        <a href="form-element-select.html">Vessel Service / Route</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-radio.html">Yard Allocation Filter</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Load Planning</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Load Planning - Bigger Vessel</a>
                    </li> -->
            <!-- update  -->
            <li class="submenu-item @if(Request::is('planning/bayplan_import')) active @endif">
                <a href="/planning/bayplan_import">Bay Plan Import</a>
            </li>
            <li class="submenu-item @if(Request::is('planning/ship_planning')) active @endif">
                <a href="/planning/ship_planning">Ship Planning</a>
            </li>
            <li class="submenu-item @if(Request::is('planning/shifting/main')) active @endif">
                <a href="{{route('index-shifting')}}">shifting</a>
            </li>
            </li>
            <li class="submenu-item @if(Request::is('planning/profile-kapal')) active @endif">
                <a href="/planning/profile-kapal">Ship Profile</a>
            </li>
            <hr>
            <li class="submenu-item @if(Request::is('planning/report')) active @endif">
                <a href="/planning/report">Discharge List</a>
            </li>
            <li class="submenu-item @if(Request::is('planning/realisasi-bongkar')) active @endif">
                <a href="/planning/realisasi-bongkar">Realisasi Bongkar</a>
            </li>
            <!-- <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Bay Plan Export</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Print Layout Bayplan All Bay</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Layout Yard Export</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Profile Kapal</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Yard Block</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Realisasi Export</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Print Discharging List</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Print Import Card</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Realisasi Bongkar</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Insert / Update B/L</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Vessel Schedule</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Vessel Schedule (Est vs Act)</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Vessel Master</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">VIP Container Export</a>
                    </li> -->
        </ul>
    </li>






    <!-- disch/load -->

    <li class="sidebar-item  has-sub @if(Request::is('disch/*') || Request::is('load/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-collection-fill"></i>
            <span>Disch/Load</span>
        </a>
        <ul class="submenu @if(Request::is('disch/*') || Request::is('load/*')) active @endif">
            <!-- <li class="submenu-item ">
                        <a href="extra-component-avatar.html">Loading Confirm With Plan</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="extra-component-sweetalert.html">Loading Confirm Without Plan</a>
                    </li> -->
            <li class="submenu-item @if(Request::is('disch/confirm_disch')) active @endif">
                <a href="/disch/confirm_disch">Discharge Confirm</a>
            </li>
            <li class="submenu-item @if(Request::is('disch/view-vessel')) active @endif">
                <a href="/disch/view-vessel">Discharge View</a>
            </li>
            <hr>
            <li class="submenu-item @if(Request::is('load/confirm_load')) active @endif">
                <a href="/load/confirm_load">Load Confirm</a>
            </li>
            <li class="submenu-item @if(Request::is('load/container_loading')) active @endif">
                <a href="/load/container_loading">Container On Loading</a>
            </li>
            <!-- <li class="submenu-item ">
                        <a href="extra-component-rating.html">Entry Hatch Move</a>
                    </li>
                    <br>
                    <li class="submenu-item ">
                        <a href="extra-component-divider.html">Load/Disc Activity Rekap</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="extra-component-divider.html">Load/Disc Activity Rekap - SPLIT</a>
                    </li>

                    <br>

                    <li class="submenu-item ">
                        <a href="extra-component-divider.html">Laporan Rekap RBM Split - Total</a>
                    </li>

                    <br>

                    <li class="submenu-item ">
                         <a href="extra-component-divider.html">Correction Stowage Pos. after load</a>
                    </li>
                    <li class="submenu-item ">
                         <a href="extra-component-divider.html">Unloading ( Change status 56 to 50)</a>
                    </li>
                    <li class="submenu-item ">
                         <a href="extra-component-divider.html">Statement or Facts</a>
                    </li>      -->
        </ul>
    </li>

    <!-- yard -->

    <li class="sidebar-item  has-sub @if(Request::is('yard/*') || Request::is('stripping') || Request::is('stuffing') || Request::is('batal-muat') ||  Request::is('batal-muat/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="fa-solid fa-landmark-flag"></i>
            <span>Yard</span>
        </a>
        <ul class="submenu  @if(Request::is('yard/*') || Request::is('stripping') || Request::is('stuffing') ||  Request::is('batal-muat') || Request::is('batal-muat/*')) active @endif">
            <!-- <li class="submenu-item ">
                        <a href="form-element-input.html">Yard Operation Control</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-input-group.html">Behandle Control</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-select.html">List Behandle Control Not Relocation</a>
                    </li>
                    <hr>
                    <li class="submenu-item ">
                        <a href="form-element-radio.html">Karantina Control</a>
                    </li>
                    <hr> -->
            <li class="submenu-item  @if(Request::is('yard/placement')) active @endif">
                <a href="/yard/placement">Placement Container</a>
            </li>
            <!-- <li class="submenu-item ">
                        <a href="form-element-textarea.html">Yard Display</a>
                    </li>  -->
            <li class="submenu-item  @if(Request::is('yard/rowtier')) active @endif">
                <a href="/yard/rowtier">Yard View</a>
            </li>

            <hr>

            <li class="submenu-item @if(Request::is('stripping')) active @endif">
                <a href="/stripping">Stripping</a>
            </li>
            <li class="submenu-item @if(Request::is('stuffing')) active @endif">
                <a href="/stuffing">Stuffing</a>
            </li>
            <hr>
            <li class="submenu-item @if(Request::is('yard/trucking')) active @endif">
                <a href="/yard/trucking">Trucking</a>
            </li>
            <!-- <li class="submenu-item ">
                        <a href="form-element-textarea.html">Reefer Monitoring</a>
                    </li> <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Reefer Export Control</a>
                    </li>
                    <hr>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Yard Planning Gate In Reciving</a>
                    </li> <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Monitoring Gate In Reciving</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Re-print Job Order With Barcode</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Re-print Per Job Order With Barcode</a>
                    </li>

                    <hr>

                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Behandle Confirm</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Behandle Confirm Status 09</a>
                    </li> 
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Stuffing Confirm</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Stripping Confirm</a>
                    </li> <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Re-print Job Behandle</a>
                    </li>

                    <hr>

                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Placement</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Laporan UnPlacement</a>
                    </li> 

                    <hr>

                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Job Slip Behandle</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Laporan Job Slip Stripping</a>
                    </li> 
                    <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Job Slip Stuffing</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-textarea.html">Laporan Job Slip Scanning</a>
                    </li> <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Job Slip Gerakan Extra</a>
                    </li> -->

            <hr>

            <li class="submenu-item @if(Request::is('batal-muat') || Request::is('batal-muat/*')) active @endif">
                <a href="/batal-muat">Batal Muat</a>
            </li>
            <!-- </li> <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Behandle Confirm</a>
                    </li> -->
        </ul>
    </li>


    <!-- gate -->

    <li class="sidebar-item  has-sub @if(Request::is('delivery/*') || Request::is('reciving/*') || Request::is('stuffing/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="fa-solid fa-torii-gate"></i>
            <span>Gate</span>
        </a>
        <ul class="submenu @if(Request::is('delivery/*') || Request::is('reciving/*') || Request::is('stuffing/*')) active @endif">
            <li class="submenu-item @if(Request::is('delivery/gate-in')) active @endif">
                <a href="/delivery/gate-in">Get in Delivery</a>
            </li>
            <li class="submenu-item @if(Request::is('delivery/gate-out')) active @endif">
                <a href="/delivery/gate-out">Gate Out Delivery</a>
            </li>
            <hr>
            <li class="submenu-item @if(Request::is('delivery/balik-relokasi')) active @endif">
                <a href="/delivery/balik-relokasi">Gate MT Balik IKS & Relokasi Pelindo</a>
            </li>
            <hr>

            <li class="submenu-item @if(Request::is('reciving/gate-in')) active @endif">
                <a href="/reciving/gate-in">Get in Reciving</a>
            </li>
            <li class="submenu-item @if(Request::is('reciving/gate-out')) active @endif">
                <a href="/reciving/gate-out">Gate Out Reciving</a>
            </li>

            <hr>

            <li class="submenu-item @if(Request::is('stuffing/gate-in')) active @endif">
                <a href="/stuffing/gate-in">Get in Stuffing</a>
            </li>

            <li class="submenu-item @if(Request::is('stuffing/gate-out')) active @endif">
                <a href="/stuffing/gate-out">Get out Stuffing</a>
            </li>

            <!-- <li class="submenu-item ">
                        <a href="form-element-select.html">Gate In Receiving</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-radio.html">Gate Out Receiving</a>
                    </li> -->
        </ul>
    </li>




    <!-- EDI -->

    <li class="sidebar-item  has-sub @if(Request::is('edi/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="fa-solid fa-file"></i>
            <span>EDI</span>
        </a>
        <ul class="submenu @if(Request::is('edi/*')) active @endif">
            <li class="submenu-item @if(Request::is('edi/receiveed1')) active @endif">
                <a href="/edi/receiveedi">Baplie Arrival</a>
            </li>
            <li class="submenu-item @if(Request::is('edi/coparn')) active @endif">
                <a href="/edi/coparn">Coparn</a>
            </li>
            <!-- <li class="submenu-item ">
                        <a href="form-element-input-group.html">Baplie  Deparature</a>
                    </li> -->
        </ul>
    </li>

    <!-- <li class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                <i class="fa-solid fa-torii-gate"></i>
                    <span>Gate</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="form-element-input.html">Gate In Delivery</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="">Gate Out Delivery</a>
                    </li>

                    <hr>

                    
                    <li class="submenu-item ">
                        <a href="form-element-select.html">Gate In Receiving</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-radio.html">Gate Out Receiving</a>
                    </li>
                </ul>
            </li> -->

    <!-- Android Menu -->

    <li class="sidebar-item  has-sub">
        <a href="#" class='sidebar-link'>
            <i class="fa-solid fa-mobile"></i>
            <span>Android</span>
        </a>
        <ul class="submenu">
            <li class="submenu-item ">
                <a href="/disch/confirm_disch">Confirm Disch</a>
            </li>
            <li class="submenu-item ">
                <a href="/yard/placement">Placement Container</a>
            </li>
            <li class="submenu-item">
                <a href="/stripping">Stripping</a>
            </li>

            <hr>


            <li class="submenu-item ">
                <a href="/delivery/gate-in">Gate In Delivery</a>
            </li>
            <li class="submenu-item ">
                <a href="/delivery/gate-out">Gate Out Delivery</a>
            </li>
        </ul>
    </li>

    <!-- Doc -->
    <li class="sidebar-item  has-sub @if(Request::is('docs/*')) active @endif">
        <a href="#" class='sidebar-link'>
        <i class="fa-solid fa-file-invoice"></i>
            <span>Docs & Inventory</span>
        </a>
        <ul class="submenu @if(Request::is('docs/*')) active @endif">
            <li class="submenu-item @if(Request::is('docs/dokumen/ro')) active @endif">
                <a href="/docs/dokumen/ro">Dokumen R.O</a>
            </li>

            <hr>

            <li class="submenu-item @if(Request::is('docs/inventory/items')) active @endif">
                <a href="{{ route('inventory.items') }}">Container in Progress</a>
            </li>
        </ul>
    </li>


    <!-- Report -->

    <li class="sidebar-item has-sub @if(Request::is('reports/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="fa-solid fa-circle-info"></i>
            <span>Report and Information</span>
        </a>
        <ul class="submenu @if(Request::is('reports/*')) active @endif">
            <li class="submenu-item @if(Request::is('reports/hist')) active @endif">
                <a href="/reports/hist">History Container</a>
            </li>
            <li class="submenu-item @if(Request::is('reports/equipment')) active @endif">
                <a href="/reports/equipment">Equipment Report</a>
            </li>
            <li class="submenu-item @if(Request::is('reports/operator')) active @endif">
                <a href="{{ route('op-rep')}}">Operator Report</a>
            </li>
            <hr>
            <li class="submenu-item @if(Request::is('reports/disch')) active @endif">
                <a href="/reports/disch">Disch Reports</a>
            </li>
            <li class="submenu-item @if(Request::is('reports/plc')) active @endif">
                <a href="/reports/plc">PLC Reports</a>
            </li>
            <li class="submenu-item @if(Request::is('reports/str')) active @endif">
                <a href="/reports/str">Srtipping Reports</a>
            </li>
            <li class="submenu-item @if(Request::is('reports/gati-del')) active @endif">
                <a href="/reports/gati-del">Gati-Del Reports</a>
            </li>
            <li class="submenu-item @if(Request::is('reports/gato-del')) active @endif">
                <a href="/reports/gato-del">Gato-Del Reports</a>
            </li>
            <hr>
            <li class="submenu-item @if(Request::is('reports/export')) active @endif">
                <a href="/reports/export">Realisasi Export</a>
            </li>
            <li class="submenu-item @if(Request::is('reports/batal-muat')) active @endif">
                <a href="{{ route('indexReport-batal-muat')}}">Batal Muat</a>
            </li>
        </ul>
    </li>


    <!-- CTR -->

    <!-- <li
                class="sidebar-item has-sub">
                <a href="#" class='sidebar-link'>
                <i class="fa-solid fa-toolbox"></i>
                    <span>CTR Maintenance</span>
                </a>
                <ul class="submenu">
                    <li class="submenu-item ">
                        <a href="layout-default.html">Default Layout</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-vertical-1-column.html">1 Column</a>
                    </li>
                    <li class="submenu-item">
                        <a href="layout-vertical-navbar.html">Vertical Navbar</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-rtl.html">RTL Layout</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-horizontal.html">Horizontal Menu</a>
                    </li>
                </ul>
            </li> -->

    <!-- System -->

    <li class="sidebar-item has-sub @if(Request::is('system/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="fa-solid fa-wrench"></i>
            <span>System</span>
        </a>
        <ul class="submenu @if(Request::is('system/*')) active @endif">
            <li class="submenu-item @if(Request::is('system/user') || Request::is('system/user/create_user') ||  Request::is('system/edit_user=*')) active @endif">
                <a href="/system/user">User</a>
            </li>
            <li class="submenu-item @if(Request::is('system/role') || Request::is('system/role/addrole') || Request::is('system/edit_role=*')) active @endif">
                <a href="/system/role">Role</a>
            </li>
        </ul>
    </li>





    <!-- invoice -->

    <li class="sidebar-item">
        <a href="/invoice/menu" class='sidebar-link'>
            <i class="bi bi-currency-exchange"></i>
            <span>Invoice</span>
        </a>
    </li>


    <!-- Master -->
    <li class="sidebar-item  has-sub @if(Request::is('master/*')) active @endif">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-stack"></i>
            <span>Master</span>
        </a>
        <ul class="submenu @if(Request::is('master/*')) active @endif">
            <li class="submenu-item @if(Request::is('master/port')) active @endif">
                <a href="/master/port">Port Maintenance</a>
            </li>
            <li class="submenu-item @if(Request::is('master/berth')) active @endif">
                <a href="/master/berth">Berth Maintenance</a>
            </li>
            <li class="submenu-item @if(Request::is('master/vessel')) active @endif">
                <a href="/master/vessel">Vessel Maintenance</a>
            </li>
            <li class="submenu-item @if(Request::is('master/service')) active @endif">
                <a href="/master/service">Vessel Service Maintenance</a>
            </li>
            <li class="submenu-item @if(Request::is('master/block')) active @endif">
                <a href="/master/block">Yard</a>
            </li>
            <li class="submenu-item @if(Request::is('master/isocode')) active @endif">
                <a href="/master/isocode">ISO Code Maintenance</a>
            </li>
            <li class="submenu-item @if(Request::is('master/alat')) active @endif">
                <a href="/master/alat">Equipment</a>
            </li>
            <li class="submenu-item @if(Request::is('master/operator')) active @endif">
                <a href="{{route('operator')}}">Operator</a>
            </li>
        </ul>
    </li>
    @else
    
    <li class="sidebar-item @if(Request::is('bea-cukai-sevice')) active @endif">
        <a href="/bea-cukai-sevice" class='sidebar-link'>
            <span>Home</span>
        </a>
    </li>
    <li class="sidebar-item @if(Request::is('bea-cukai-sevice/container-hold')) active @endif">
        <a href="/bea-cukai-sevice/container-hold" class='sidebar-link'>
            <span>Container in Hold</span>
        </a>
    </li>
    <li class="sidebar-item @if(Request::is('bea-cukai-sevice/container-hold-p2')) active @endif">
        <a href="/bea-cukai-sevice/container-hold-p2" class='sidebar-link'>
            <span>Holding Proccess P2</span>
        </a>
    </li>
    <li class="sidebar-item @if(Request::is('bea-cukai-sevice/container-proses-release-p2 ')) active @endif">
        <a href="/bea-cukai-sevice/container-proses-release-p2" class='sidebar-link'>
            <span>Release Proccess P2</span>
        </a>
    </li>
    @endif
    </ul>




    <!-- <li class="sidebar-title">Planning &amp; Yard</li> -->



    <!-- <li
                class="sidebar-item  ">
                <a href="form-layout.html" class='sidebar-link'>
                    <i class="bi bi-file-earmark-medical-fill"></i>
                    <span>Yard</span>
                </a>
            </li> -->

    <!-- <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-journal-check"></i>
                    <span>Form Validation</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="form-validation-parsley.html">Parsley</a>
                    </li>
                </ul>
            </li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-pen-fill"></i>
                    <span>Form Editor</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="form-editor-quill.html">Quill</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-editor-ckeditor.html">CKEditor</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-editor-summernote.html">Summernote</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-editor-tinymce.html">TinyMCE</a>
                    </li>
                </ul>
            </li>
            
            <li
                class="sidebar-item  ">
                <a href="table.html" class='sidebar-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Table</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                    <span>Datatables</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="table-datatable.html">Datatable</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="table-datatable-jquery.html">Datatable (jQuery)</a>
                    </li>
                </ul>
            </li> -->

    <!-- <li class="sidebar-title">Extra UI</li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-pentagon-fill"></i>
                    <span>Widgets</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="ui-widgets-chatbox.html">Chatbox</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="ui-widgets-pricing.html">Pricing</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="ui-widgets-todolist.html">To-do List</a>
                    </li>
                </ul>
            </li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-egg-fill"></i>
                    <span>Icons</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="ui-icons-bootstrap-icons.html">Bootstrap Icons </a>
                    </li>
                    <li class="submenu-item ">
                        <a href="ui-icons-fontawesome.html">Fontawesome</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="ui-icons-dripicons.html">Dripicons</a>
                    </li>
                </ul>
            </li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Charts</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="ui-chart-chartjs.html">ChartJS</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="ui-chart-apexcharts.html">Apexcharts</a>
                    </li>
                </ul>
            </li>
            
            <li
                class="sidebar-item  ">
                <a href="ui-file-uploader.html" class='sidebar-link'>
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                    <span>File Uploader</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-map-fill"></i>
                    <span>Maps</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="ui-map-google-map.html">Google Map</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="ui-map-jsvectormap.html">JS Vector Map</a>
                    </li>
                </ul>
            </li>
            
            <li class="sidebar-title">Pages</li>
            
            <li
                class="sidebar-item  ">
                <a href="application-email.html" class='sidebar-link'>
                    <i class="bi bi-envelope-fill"></i>
                    <span>Email Application</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  ">
                <a href="application-chat.html" class='sidebar-link'>
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Chat Application</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  ">
                <a href="application-gallery.html" class='sidebar-link'>
                    <i class="bi bi-image-fill"></i>
                    <span>Photo Gallery</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  ">
                <a href="application-checkout.html" class='sidebar-link'>
                    <i class="bi bi-basket-fill"></i>
                    <span>Checkout Page</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Authentication</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="auth-login.html">Login</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="auth-register.html">Register</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="auth-forgot-password.html">Forgot Password</a>
                    </li>
                </ul>
            </li>
            
            <li
                class="sidebar-item  has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-x-octagon-fill"></i>
                    <span>Errors</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item ">
                        <a href="error-403.html">403</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="error-404.html">404</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="error-500.html">500</a>
                    </li>
                </ul>
            </li>
            
            <li class="sidebar-title">Raise Support</li>
            
            <li
                class="sidebar-item  ">
                <a href="https://zuramai.github.io/mazer/docs" class='sidebar-link'>
                    <i class="bi bi-life-preserver"></i>
                    <span>Documentation</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  ">
                <a href="https://github.com/zuramai/mazer/blob/main/CONTRIBUTING.md" class='sidebar-link'>
                    <i class="bi bi-puzzle"></i>
                    <span>Contribute</span>
                </a>
            </li>
            
            <li
                class="sidebar-item  ">
                <a href="https://github.com/zuramai/mazer#donation" class='sidebar-link'>
                    <i class="bi bi-cash"></i>
                    <span>Donate</span>
                </a>
            </li>
            
        </ul> -->