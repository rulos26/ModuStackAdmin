@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\User::count() }}</h3>
                <p>Usuarios</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            @can('view users')
            <a href="{{ route('users.index') }}" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
            @endcan
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \Spatie\Permission\Models\Role::count() }}</h3>
                <p>Roles</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-shield"></i>
            </div>
            @can('view roles')
            <a href="{{ route('roles.index') }}" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
            @endcan
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Models\User::where('is_active', true)->count() }}</h3>
                <p>Usuarios Activos</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ \App\Models\UserActivityLog::count() }}</h3>
                <p>Logs de Actividad</p>
            </div>
            <div class="icon">
                <i class="fas fa-history"></i>
            </div>
            @can('view activity logs')
            <a href="{{ route('activity-logs.index') }}" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
            @endcan
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bienvenido, {{ auth()->user()->name }}</h3>
            </div>
            <div class="card-body">
                <p>Bienvenido al sistema de gestión de usuarios y roles.</p>
            </div>
        </div>
    </div>
</div>
@endsection
