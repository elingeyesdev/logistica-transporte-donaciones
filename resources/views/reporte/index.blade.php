@extends('adminlte::page')

@section('template_title')
    Reporte
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Reporte') }}
                            </span>

                             {{-- <div class="float-right">
                                <a href="{{ route('reporte.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Crear Nuevo') }}
                                </a>
                              </div> --}} 
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
								<th>Nombre PDF</th>
								<th>Fecha Reporte</th>
								<th>Gestión</th>
								<th>Archivo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportes as $reporte)
                                        <tr>
                                            <td>{{ $reporte->nombre_pdf ?? '—' }}</td>
                                            <td>
                                                @if($reporte->fecha_reporte)
                                                    {{ \Carbon\Carbon::parse($reporte->fecha_reporte)->format('d/m/Y') }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $reporte->gestion ?? '—' }}</td>
                                            <td>
                                                @php
                                                    $pdfUrl = $reporte->ruta_pdf ? asset('storage/'.$reporte->ruta_pdf) : null;
                                                @endphp
                                                @if($pdfUrl)
                                                    <a href="{{ $pdfUrl }}" target="_blank" rel="noopener" class="btn btn-link btn-sm p-0">Descargar</a>
                                                @else
                                                    <span class="text-muted">Sin archivo</span>
                                                @endif
                                            </td>

                                            <td>
                                                <form action="{{ route('reporte.destroy', $reporte->id_reporte) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('reporte.show', $reporte->id_reporte) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('reporte.edit', $reporte->id_reporte) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Seguro que quieres eliminiar este registro?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $reportes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
