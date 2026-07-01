<aside class="main-sidebar sidebar-dark-primary elevation-4">

<a href="#" class="brand-link">

<span class="brand-text font-weight-light">

BeasiswaPedia

</span>

</a>

<div class="sidebar">

<nav class="mt-2">

<ul class="nav nav-pills nav-sidebar flex-column"
    data-widget="treeview">

<li class="nav-item">

<a href="/admin/dashboard" class="nav-link">

<i class="nav-icon fas fa-home"></i>

<p>Dashboard</p>

</a>

</li>

<li class="nav-item">

<a href="#" class="nav-link">

<i class="nav-icon fas fa-graduation-cap"></i>

<p>Beasiswa</p>

</a>

</li>

<li class="nav-item">

<a href="#" class="nav-link">

<i class="nav-icon fas fa-folder"></i>

<p>Kategori</p>

</a>

</li>

<li class="nav-item">

<a href="#" class="nav-link">

<i class="nav-icon fas fa-building"></i>

<p>Penyelenggara</p>

</a>

</li>

<li class="nav-item">

<a href="#" class="nav-link">

<i class="nav-icon fas fa-users"></i>

<p>Mahasiswa</p>

</a>

</li>

</ul>

</nav>

</div>

</aside>
<li class="nav-item">

    <a href="#"
       class="nav-link"
       onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">

        <i class="nav-icon fas fa-sign-out-alt"></i>

        <p>Logout</p>

    </a>

</li>

<form id="logout-form"
      action="{{ route('logout') }}"
      method="POST"
      style="display:none;">

    @csrf

</form>