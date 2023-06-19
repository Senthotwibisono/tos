<ul class="menu">
  <li class="sidebar-title">Menu</li>

  <li class="sidebar-item <?= $active == "Dashboard" ? "active" : "" ?>">
    <a href="/dashboard" class='sidebar-link'>
      <i class="bi bi-grid-fill"></i>
      <span>Dashboard</span>
    </a>
  </li>


  <!-- planning -->

  <li class="sidebar-item  has-sub <?= $active == "planning" ? "active" : "" ?>">
    <a href="#" class='sidebar-link'>
      <i class="fa-solid fa-ship"></i>
      <span>Planning</span>
    </a>
    <ul class="submenu <?= $active == "planning" ? "active" : "" ?>">
      <li class="submenu-item ">
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
      <li class="submenu-item <?= $subactive == "bayplanimport" ? "active" : "" ?>">
        <a href="/planning/bayplan_import">Bay Plan Import</a>
      </li>
      <hr>
      <li class="submenu-item ">
        <a href="/planning/report">Discharge List</a>
      </li>
      <li class="submenu-item ">
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

  <li class="sidebar-item  has-sub <?= $active == "discharge" ? "active" : "" ?>">
    <a href="#" class='sidebar-link'>
      <i class="bi bi-collection-fill"></i>
      <span>Disch/Load</span>
    </a>
    <ul class="submenu <?= $active == "discharge" ? "active" : "" ?>">
      <!-- <li class="submenu-item ">
                        <a href="extra-component-avatar.html">Loading Confirm With Plan</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="extra-component-sweetalert.html">Loading Confirm Without Plan</a>
                    </li> -->
      <li class="submenu-item <?= $subactive == "confirm" ? "active" : "" ?>">
        <a href="/disch/confrim_disch ">Discharge Confirm</a>
      </li>
      <li class="submenu-item">
        <a href="/disch-view-vessel">Discharge View</a>
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

  <li class="sidebar-item  has-sub <?= $active == "yard" ? "active" : "" ?>">
    <a href="#" class='sidebar-link'>
      <i class="fa-solid fa-landmark-flag"></i>
      <span>Yard</span>
    </a>
    <ul class="submenu <?= $active == "yard" ? "active" : "" ?>">
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
      <li class="submenu-item <?= $subactive == "placement" ? "active" : "" ?>">
        <a href="/yard/placement">Placement Container</a>
      </li>
      <!-- <li class="submenu-item ">
                        <a href="form-element-textarea.html">Yard Display</a>
                    </li>  -->
      <li class="submenu-item ">
        <a href="/yard/rowtier">Yard View</a>
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

      <!-- </li> <li class="submenu-item ">
                        <a href="form-element-checkbox.html">Laporan Behandle Confirm</a>
                    </li> -->
    </ul>
  </li>


  <!-- gate -->

  <li class="sidebar-item  has-sub <?= $active == "delivery" ? "active" : "" ?>">
    <a href="#" class='sidebar-link'>
      <i class="fa-solid fa-torii-gate"></i>
      <span>Gate</span>
    </a>
    <ul class="submenu <?= $active == "delivery" ? "active" : "" ?>">
      <li class="submenu-item <?= $subactive == "gatein" ? "active" : "" ?>">
        <a href="/delivery/gate-in">Get in Delivery</a>
      </li>
      <li class="submenu-item <?= $subactive == "gateout" ? "active" : "" ?>">
        <a href="/delivery/gate-out">Gate Out Delivery</a>
      </li>

      <hr>


      <!-- <li class="submenu-item ">
                        <a href="form-element-select.html">Gate In Receiving</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="form-element-radio.html">Gate Out Receiving</a>
                    </li> -->
    </ul>
  </li>




  <!-- EDI -->

  <li class="sidebar-item  has-sub <?php if(Request::is('edi/*')): ?> active <?php endif; ?>">
    <a href="#" class='sidebar-link'>
      <i class="fa-solid fa-file"></i>
      <span>EDI</span>
    </a>
    <ul class="submenu ">
      <li class="submenu-item ">
        <a href="/edi/receiveedi">Baplie Arrival</a>
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
    <ul class="submenu ">
      <li class="submenu-item ">
        <a href="/disch/confrim_disch">Confirm Disch</a>
      </li>
      <li class="submenu-item ">
        <a href="/yard/placement">Placement Container</a>
      </li>
      <li class="submenu-item ">
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


  <!-- Report -->

  <li class="sidebar-item has-sub <?php if(Request::is('reports/*')): ?> active <?php endif; ?>">
    <a href="#" class='sidebar-link'>
      <i class="fa-solid fa-circle-info"></i>
      <span>Report and Information</span>
    </a>
    <ul class="submenu">
      <li class="submenu-item ">
        <a href="/reports/hist">History Container</a>
      </li>
      <!-- <li class="submenu-item ">
                        <a href="layout-vertical-1-column.html">History Container Equal</a>
                    </li>
                    <li class="submenu-item">
                        <a href="layout-vertical-navbar.html">Yard Display</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-rtl.html">Vessel Activity Monitoring</a>
                    </li>

                    <hr>

                    <li class="submenu-item ">
                        <a href="layout-horizontal.html">Laporan Dwelling</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-default.html">Container per Vessel Imp/Exp</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-vertical-1-column.html">Container Activity di Lapangan</a>
                    </li>
                    <li class="submenu-item">
                        <a href="layout-vertical-navbar.html">Import List</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-rtl.html">Container All Status Per Vessel</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-horizontal.html">Container per Status per Vessel</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-default.html">Entry Bayplan Perfomance</a>
                    </li>
                    <li class="submenu-item ">
                        <a href="layout-vertical-1-column.html">Yard Occupantion Ratio</a>
                    </li>
                    <li class="submenu-item">
                        <a href="layout-vertical-navbar.html">Perfomance Gate</a>
                    </li>

                    <hr>

                    <li class="submenu-item ">
                        <a href="layout-vertical-1-column.html">Equipment Production</a>
                    </li>
                    <li class="submenu-item">
                        <a href="layout-vertical-navbar.html">Operator Production</a>
                    </li>

                    <hr>

                    <li class="submenu-item ">
                        <a href="layout-vertical-1-column.html">All Container per B/L</a>
                    </li>
                    <li class="submenu-item">
                        <a href="layout-vertical-navbar.html">All Container per Kapal per B/L</a>
                    </li>
                    <li class="submenu-item">
                        <a href="layout-vertical-navbar.html">SP2 Active</a>
                    </li> -->
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

  <li class="sidebar-item has-sub <?php if(Request::is('system/*')): ?> active <?php endif; ?>">
    <a href="#" class='sidebar-link'>
      < <i class="fa-solid fa-wrench"></i>
        <span>System</span>
    </a>
    <ul class="submenu">
      <li class="submenu-item ">
        <a href="/system/user">User</a>
      </li>
      <li class="submenu-item ">
        <a href="/system/role">Role</a>
      </li>
    </ul>
  </li>





  <!-- invoice -->

  <li class="sidebar-item">
    <a href="/invoice" class='sidebar-link'>
      <i class="bi bi-currency-exchange"></i>
      <span>Invoice</span>
    </a>
  </li>

  <!-- Delivery SPPS -->

  <li class="sidebar-item">
    <a href="/spps" class='sidebar-link'>
      <i class="bi bi-currency-exchange"></i>
      <span>Delivery SPPS</span>
    </a>
  </li>

  <!-- Master -->
  <li class="sidebar-item  has-sub <?php if(Request::is('master/*')): ?> active <?php endif; ?>">
    <a href="#" class='sidebar-link'>
      <i class="bi bi-stack"></i>
      <span>Master</span>
    </a>
    <ul class="submenu ">
      <li class="submenu-item ">
        <a href="/master/port">Port Maintenance</a>
      </li>
      <li class="submenu-item ">
        <a href="/master/berth">Berth Maintenance</a>
      </li>
      <li class="submenu-item ">
        <a href="/master/vessel">Vessel Maintenance</a>
      </li>
      <li class="submenu-item ">
        <a href="/master/service">Vessel Service Maintenance</a>
      </li>
      <li class="submenu-item ">
        <a href="/master/block">Yard</a>
      </li>
      <li class="submenu-item ">
        <a href="/master/isocode">ISO Code Maintenance</a>
      </li>
    </ul>
  </li>




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
            
        </ul> --><?php /**PATH D:\Fdw Files\CTOS\dev\frontend\tos-dev-local\resources\views/partial/sidebar.blade.php ENDPATH**/ ?>