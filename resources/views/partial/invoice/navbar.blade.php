<div class="container" style="width: 100%; max-width: 1500px; margin: 0 auto;">
  <ul>
    <li class="menu-item  ">
      <a href="/invoice/menu" class='menu-link'>
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Delivery
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="{{ route('billinImportgMain')}}" class="submenu-link">Bongkaran Billing System</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('deliveryMenu')}}" class="submenu-link">Bongkaran Form</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('index-extend')}}" class="submenu-link">Bongkaran Form Extend</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('invoice-master-tarifImport')}}" class="submenu-link">Master Tarif</a>
            </li>
          </ul>
        </div>
      </div>
    </li>
    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Receiving
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="{{ route('billingExportMain')}}" class="submenu-link">Muat Billing System</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('deliveryMenuExport')}}" class="submenu-link">Muat Form</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('invoice-master-tarifExport')}}" class="submenu-link">Master Tarif</a>
            </li>
          </ul>
        </div>
      </div>
    </li>

    <!--Plugging -->
    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Plugging
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="{{ route('plugging-main')}}" class="submenu-link">Plugging Billing System</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('plugging-form-index')}}" class="submenu-link">Plugging Form</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('plugging-tarif-index')}}" class="submenu-link">Master Tarif</a>
            </li>
          </ul>
        </div>
      </div>
    </li>
    <!-- Steva -->
    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Stevadooring
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="{{ route('index-stevadooring')}}" class="submenu-link">Stevadooring Billing System</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('index-stevadooring-listForm')}}" class="submenu-link">Stevadooring Form</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('index-stevadooring-Tarif')}}" class="submenu-link">Master Tarif</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('index-stevadooring-RBM')}}" class="submenu-link">Realisasi Bongkar Muat Kapal</a>
            </li>
          </ul>
        </div>
      </div>
    </li>

    <!--Rental Repair -->
    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Others
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="{{ route('rental-repair-main')}}" class="submenu-link">Rental & Repair Billing System</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('rental-repair-form-index')}}" class="submenu-link">Rental & Repair Form</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('rental-repair-tarif-index')}}" class="submenu-link">Master Tarif</a>
            </li>
          </ul>
        </div>
      </div>
    </li>
    <!-- end -->
    <li class="menu-item">
      <a href="{{ route('Customer')}}" class="menu-link">
        <i class="fa-solid fa-user">
        </i>
        Customer
      </a>
    </li>
    <li class="menu-item">
      <a href="/invoice/customer/register" class="menu-link">
        <i class="fa-solid fa-user">
        </i>
        Customer Account
      </a>
    </li>

    <li class="menu-item active has-sub">
      <a href="#" class='menu-link'>
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Documents</span>
      </a>
      <div class="submenu ">
        <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
        <div class="submenu-group-wrapper">


          <ul class="submenu-group">

            <li class="submenu-item  ">
              <a href="{{ route('doMain')}}" class='submenu-link'>Do Online Check</a>


            </li>



            <li class="submenu-item  ">
              <a href="{{ route('coparnMain')}}" class='submenu-link'>Upload Coparn</a>
            </li>
            <li class="submenu-item  ">
              <a href="/bea/req-dok" class='submenu-link'>Request Clearance BC</a>
            </li>
            <li class="submenu-item active ">
              <a href="{{route('invoice-master-item')}}" class='submenu-link'>Master Item</a>
            </li>
            <li class="submenu-item active ">
              <a href="{{route('invoice-master-os')}}" class='submenu-link'>Order Service</a>
            </li>

          </ul>


        </div>
      </div>
    </li>

    <li class="menu-item active has-sub">
      <a href="#" class='menu-link'>
        <i class="fas fa-cogs"></i>
        <span>Service</span>
      </a>
      <div class="submenu ">
        <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item ">
              <a href="{{ route('invoiceService.tracking.indexImport')}}" class='submenu-link'>Tracking Container Import</a>
            </li>
            <li class="submenu-item ">
              <a href="{{ route('invoiceService.system.userIndex')}}" class='submenu-link'>Invoice User Management</a>
            </li>
          </ul>
        </div>
      </div>
    </li>
  </ul>
</div>
