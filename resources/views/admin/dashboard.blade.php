@extends('layouts.app')

@section('content')

<div class="row">

<div class="col-lg-3">

<div class="small-box bg-info">

<div class="inner">

<h3>{{ $totalUser }}</h3>

<p>Total User</p>

</div>

<div class="icon">

<i class="fas fa-users"></i>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="small-box bg-success">

<div class="inner">

<h3>{{ $totalMahasiswa }}</h3>

<p>Mahasiswa</p>

</div>

<div class="icon">

<i class="fas fa-user-graduate"></i>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="small-box bg-warning">

<div class="inner">

<h3>{{ $totalAdmin }}</h3>

<p>Admin</p>

</div>

<div class="icon">

<i class="fas fa-user-shield"></i>

</div>

</div>

</div>

</div>

@endsection