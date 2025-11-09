@extends('layouts.admin')

@section('title', 'Ver Usuario')
@section('page-title', 'Usuario: ' . $user->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
<li class="breadcrumb-item active">Ver</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://via.placeholder.com/128' }}" alt="Avatar">
                </div>
                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                <p class="text-muted text-center">{{ $user->email }}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Estado</b>
                        @if($user->is_active)
                            <span class="float-right badge badge-success">Activo</span>
                        @else
                            <span class="float-right badge badge-danger">Inactivo</span>
                        @endif
                    </li>
                    <li class="list-group-item">
                        <b>Último acceso</b>
                        <span class="float-right">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">Nunca</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <b>MFA</b>
                        @if($user->mfa_enabled)
                            <span class="float-right badge badge-success">Habilitado</span>
                        @else
                            <span class="float-right badge badge-secondary">Deshabilitado</span>
                        @endif
                    </li>
                </ul>
                @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-block"><b>Editar</b></a>
                @endcan
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#roles" data-toggle="tab">Roles</a></li>
                    <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Actividad</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="roles">
                        <h5>Roles asignados:</h5>
                        @foreach($user->roles as $role)
                            <span class="badge badge-info mr-2">{{ $role->name }}</span>
                        @endforeach
                        @if($user->roles->isEmpty())
                            <p class="text-muted">Sin roles asignados</p>
                        @endif

                        <h5 class="mt-3">Permisos directos:</h5>
                        @foreach($user->permissions as $permission)
                            <span class="badge badge-secondary mr-2">{{ $permission->name }}</span>
                        @endforeach
                        @if($user->permissions->isEmpty())
                            <p class="text-muted">Sin permisos directos</p>
                        @endif
                    </div>

                    <div class="tab-pane" id="activity">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Acción</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->activityLogs as $log)
                                    <tr>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay actividad registrada</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


