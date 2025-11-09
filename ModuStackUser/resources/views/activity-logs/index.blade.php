@extends('layouts.admin')

@section('title', 'Logs de Actividad')
@section('page-title', 'Logs de Actividad')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
<li class="breadcrumb-item active">Logs</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registro de Actividades</h3>
                <div class="card-tools">
                    @can('export activity logs')
                    <div class="btn-group">
                        <a href="{{ route('activity-logs.export.pdf', request()->query()) }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </a>
                        <a href="{{ route('activity-logs.export.excel', request()->query()) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('activity-logs.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="user_id" class="form-control">
                                <option value="">Todos los usuarios</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="action" class="form-control">
                                <option value="">Todas las acciones</option>
                                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Crear</option>
                                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Actualizar</option>
                                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Eliminar</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Desde">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Hasta">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Descripción</th>
                                <th>IP</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->user->name ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ $log->action }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('activity-logs.show', $log) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron logs</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


