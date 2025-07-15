<div class="container">
  <ul>

    <li class="menu-item  ">
      <a href="/customer-dashboard" class='menu-link'>
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
      </a>
    </li>
  
    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Bongkar
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="/customer-import" class="submenu-link">Create Invoice</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('customer.importDO.index')}}" class="submenu-link">Upload DO Online</a>
            </li>
            
          </ul>
        </div>
      </div>
    </li>

    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Muat
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="/customer-export" class="submenu-link">Create Invoice</a>
            </li>
            <li class="submenu-item">
              <a href="{{ route('customer.coparn.index')}}" class="submenu-link">Upload Coparn</a>
            </li>
            
          </ul>
        </div>
      </div>
    </li>

    <li class="menu-item">
      <a href="/customer-extend" class="menu-link">
        <i class="fa-solid fa-clock"></i>
        Extend
      </a>
    </li>

    <li class="menu-item has-sub">
      <a type="button" class="menu-link">
        <i class="fa-solid fa-ship"></i>
        Payment
      </a>
      <div class="submenu">
        <div class="submenu-group-wrapper">
          <ul class="submenu-group">
            <li class="submenu-item">
              <a href="{{route('pembayaran.va.indexList')}}" class="submenu-link">List Virtual Account</a>
            </li>
            <!-- <li class="submenu-item">
              <a href="{{ route('customer.coparn.index')}}" class="submenu-link">Upload Coparn</a>
            </li> -->
            
          </ul>
        </div>
      </div>
    </li>


  </ul>
</div>
