<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{url('dashboard')}}" class="nav-link {{request()->is('dashboard') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{url('setting')}}" class="nav-link {{request()->is('setting') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        Site Setting
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
            </li>
            <li class="nav-item has-treeview {{ str_contains(url()->current(), 'categor') || str_contains(url()->current(), 'product')
                    || request()->is('coupons') || str_contains(url()->current(), 'addon') || str_contains(url()->current(), 'attributes') || str_contains(url()->current(), 'attribute-item')
                    || str_contains(url()->current(), 'area-code/') || str_contains(url()->current(), 'deals') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-tags fw"></i>
                    <p>
                        Catalog
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview {{ str_contains(url()->current(), 'categor') || str_contains(url()->current(), 'product')
                        || request()->is('coupons') || str_contains(url()->current(), 'addon') || str_contains(url()->current(), 'attributes') || str_contains(url()->current(), 'attribute-item')
                        || str_contains(url()->current(), 'area-code/') || str_contains(url()->current(), 'deals') ? 'display:block;' : '' }}">
                    <li class="nav-item">
                        <a href="{{ route('admin.categories') }}"
                           class="nav-link {{ str_contains(url()->current(), 'categor') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.deals')}}"
                           class="nav-link {{ str_contains(url()->current(), 'deals') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Deals</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.products')}}"
                           class="nav-link {{ str_contains(url()->current(), 'product') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Product</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.addonGroups')}}"
                           class="nav-link {{ str_contains(url()->current(), 'addon-group') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Addon Groups</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.addonItems')}}"
                           class="nav-link {{ str_contains(url()->current(), 'addon-item') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Addon Items</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.attributes')}}"
                           class="nav-link {{ str_contains(url()->current(), 'attributes') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Attributes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.attributeItems')}}"
                           class="nav-link {{ str_contains(url()->current(), 'attribute-item') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Attribute Items</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.coupons')}}"
                           class="nav-link {{ str_contains(url()->current(), 'coupon') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Coupons</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.banners')}}"
                           class="nav-link {{ str_contains(url()->current(), 'banners') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Banners</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.areaCodeCharge')}}"
                           class="nav-link {{ str_contains(url()->current(), 'area-code/') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-angle-double-right"></i>
                            <p>Area Code</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.customers')}}"
                   class="nav-link {{ str_contains(url()->current(), 'customers') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-user"></i>
                    <p>Customers</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.staffMember')}}"
                   class="nav-link {{ str_contains(url()->current(), 'staffMember') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-user"></i>
                    <p>Staff</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.orders')}}"
                   class="nav-link {{ str_contains(url()->current(), 'order') ? 'active' : '' }}">
                    <i class="nav-icon fa fa-boxes"></i>
                    <p>Orders</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.logout')}}"
                   class="nav-link">
                    <i class="nav-icon fa fa-lock"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
