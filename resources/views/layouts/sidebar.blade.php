<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">
            BeasiswaPedia
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-home"></i>

                        <p>Dashboard</p>

                    </a>
                </li>

                <!-- Kelola Beasiswa -->
                <li class="nav-item">
                    <a href="{{ route('admin.beasiswa.index') }}"
                       class="nav-link {{ request()->routeIs('admin.beasiswa.*') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-graduation-cap"></i>

                        <p>Kelola Beasiswa</p>

                    </a>
                </li>

                <!-- Kelola Pengguna -->
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-users"></i>

                        <p>Kelola Pengguna</p>

                    </a>
                </li>

                <!-- Kelola Iklan (Aktifkan nanti setelah CRUD dibuat) -->
                {{--
                <li class="nav-item">
                    <a href="{{ route('admin.iklan.index') }}"
                       class="nav-link {{ request()->routeIs('admin.iklan.*') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-bullhorn"></i>

                        <p>Kelola Iklan</p>

                    </a>
                </li>
                --}}

                <!-- Logout -->
                <li class="nav-item">

                    <a href="#"
                       class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                        <i class="nav-icon fas fa-sign-out-alt"></i>

                        <p>Logout</p>

                    </a>

                </li>

            </ul>

        </nav>

    </div>

</aside>

<form id="logout-form"
      action="{{ route('logout') }}"
      method="POST"
      style="display:none;">

    @csrf

</form>