@extends('layouts.admin')

@section('title', 'Ver Rol')
@section('page-title', 'Rol: ' . $role->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
<li class="breadcrumb-item active">Ver</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Rol</h3>
                @can('update', $role)
                <div class="card-tools">
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
                @endcan
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9"><span class="badge badge-primary">{{ $role->name }}</span></dd>

                    <dt class="col-sm-3">Usuarios con este rol:</dt>
                    <dd class="col-sm-9">{{ $role->users->count() }}</dd>

                    <dt class="col-sm-3">Permisos asignados:</dt>
                    <dd class="col-sm-9">
                        @if($role->permissions->isEmpty())
                            <span class="text-muted">Sin permisos</span>
                        @else
                            @foreach($permissions as $group => $groupPermissions)
                                <div class="mb-2">
                                    <strong>{{ ucfirst($group) }}:</strong>
                                    @foreach($groupPermissions as $permission)
                                        @if($role->hasPermissionTo($permission->name))
                                            <span class="badge badge-success mr-1">{{ $permission->name }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection



