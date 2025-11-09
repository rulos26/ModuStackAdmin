@extends('layouts.admin')

@section('title', 'Ver Log')
@section('page-title', 'Detalle del Log #' . $activityLog->id)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{ route('activity-logs.index') }}">Logs</a></li>
<li class="breadcrumb-item active">Ver</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Log</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Usuario:</dt>
                    <dd class="col-sm-9">{{ $activityLog->user->name ?? 'N/A' }} ({{ $activityLog->user->email ?? 'N/A' }})</dd>

                    <dt class="col-sm-3">Acción:</dt>
                    <dd class="col-sm-9"><span class="badge badge-info">{{ $activityLog->action }}</span></dd>

                    <dt class="col-sm-3">Descripción:</dt>
                    <dd class="col-sm-9">{{ $activityLog->description }}</dd>

                    <dt class="col-sm-3">URL:</dt>
                    <dd class="col-sm-9">{{ $activityLog->url }}</dd>

                    <dt class="col-sm-3">Método:</dt>
                    <dd class="col-sm-9">{{ $activityLog->method }}</dd>

                    <dt class="col-sm-3">IP Address:</dt>
                    <dd class="col-sm-9">{{ $activityLog->ip_address }}</dd>

                    <dt class="col-sm-3">User Agent:</dt>
                    <dd class="col-sm-9">{{ $activityLog->user_agent }}</dd>

                    <dt class="col-sm-3">Fecha:</dt>
                    <dd class="col-sm-9">{{ $activityLog->created_at->format('d/m/Y H:i:s') }}</dd>

                    @if($activityLog->old_values)
                    <dt class="col-sm-3">Valores Anteriores:</dt>
                    <dd class="col-sm-9">
                        <pre class="bg-light p-2 rounded">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                    </dd>
                    @endif

                    @if($activityLog->new_values)
                    <dt class="col-sm-3">Valores Nuevos:</dt>
                    <dd class="col-sm-9">
                        <pre class="bg-light p-2 rounded">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                    </dd>
                    @endif
                </dl>
            </div>
            <div class="card-footer">
                <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection


