<?php

namespace App\Livewire;

use App\Models\Solicitud;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SolicitudTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'solicitud-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'solicitudes')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Solicitud::query()
            ->with(['ciudadano', 'tipoSolicitud']);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ciudadano_id')
            ->add('tipo_solicitud_id')
            ->add('descripcion')
            ->add('estado')
            ->add('acepta_terminos')
            ->add('fecha_respuesta')
            ->add('respuesta')
            ->add('created_at')
            // Datos del Ciudadano a través de la relación
            ->add('cedula', fn($row) => $row->ciudadano?->cedula ?? 'N/A')
            ->add('nombre_completo', fn($row) =>
                ($row->ciudadano?->nombre ?? 'N/A') . ' ' . ($row->ciudadano?->apellido ?? '')
            )
            ->add('email', fn($row) => $row->ciudadano?->email ?? 'N/A')
            ->add('telefono_movil', fn($row) => $row->ciudadano?->telefono_movil ?? 'N/A')
            // Datos del Tipo de Solicitud
            ->add('tipo_solicitud_nombre', fn($row) => $row->tipoSolicitud?->nombre ?? 'N/A')
            // Formato de fechas
            ->add('fecha_solicitud_formatted', fn($row) =>
                Carbon::parse($row->created_at)
                    ->timezone('America/Caracas')
                    ->format('d/m/Y h:i A')
            )
            ->add('fecha_respuesta_formatted', fn($row) =>
                $row->fecha_respuesta
                    ? Carbon::parse($row->fecha_respuesta)
                        ->timezone('America/Caracas')
                        ->format('d/m/Y h:i A')
                    : 'Pendiente'
            )
            // Badge de estado
            ->add('estado_badge', fn($row) =>
                match($row->estado) {
                    'pendiente' => '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Pendiente</span>',
                    'en_proceso' => '<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">En Proceso</span>',
                    'aprobado' => '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Aprobado</span>',
                    'rechazado' => '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Rechazado</span>',
                    default => $row->estado
                }
            )
            // Badge de términos
            ->add('terminos_badge', fn($row) =>
                $row->acepta_terminos
                    ? '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Aceptado</span>'
                    : '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">No Aceptado</span>'
            );
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Cédula', 'cedula')
                ->sortable()
                ->searchable(),

            Column::make('Nombre Completo', 'nombre_completo')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Teléfono', 'telefono_movil')
                ->sortable()
                ->searchable(),

            Column::make('Tipo de Solicitud', 'tipo_solicitud_nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Descripción', 'descripcion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Estado', 'estado_badge', 'estado')
                ->sortable()
                ->searchable(),

            Column::make('Acepta Términos', 'terminos_badge', 'acepta_terminos')
                ->sortable()
                ->searchable(),

            Column::make('Respuesta', 'respuesta')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Fecha de Respuesta', 'fecha_respuesta_formatted')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de Solicitud', 'fecha_solicitud_formatted')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('cedula')->operators(['contains']),
            Filter::inputText('nombre_completo')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('telefono_movil')->operators(['contains']),
            Filter::inputText('tipo_solicitud_nombre')->operators(['contains']),
            Filter::inputText('descripcion')->operators(['contains']),
        ];
    }

    public function actions(Solicitud $row): array
    {
        return [
            Button::add('pdf')
                ->slot('<i class="fas fa-file-pdf"></i>')
                ->class('bg-blue-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-blue-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes([
                    'wire:click' => "\$parent.call('generatePdf', {$row->id})",
                    'title' => 'Descargar PDF',
                    'style' => 'cursor: pointer;'
                ]),

            Button::add('confirmar')
                ->slot('<i class="fas fa-handshake"></i>')
                ->class('bg-green-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-green-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes([
                    'wire:click' => '$dispatch(\'abrir-confirmar-modal-solicitud\', { id: ' . $row->id . ' })',
                    'title' => 'Confirmar solicitud'
                ]),

            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes([
                    'onclick' => "confirmDeleteSolicitud({$row->id})",
                    'title' => 'Eliminar solicitud'
                ]),
        ];
    }
}
