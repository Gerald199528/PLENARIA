<?php

namespace App\Livewire;

use App\Models\DerechoDePalabra;
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

final class DerechodePalabraTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'derecho-palabra-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'derecho_palabra')
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
        return DerechoDePalabra::query()
            ->with(['ciudadano', 'sesion', 'comision']);
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
            ->add('sesion_municipal_id')
            ->add('comision_id')
            ->add('motivo_solicitud')
            ->add('estado')
            ->add('observaciones')
            ->add('fecha_respuesta')
            ->add('created_at')
            // Datos del Ciudadano a través de la relación
            ->add('cedula', fn($row) => $row->ciudadano?->cedula ?? 'N/A')
            ->add('nombre', fn($row) => $row->ciudadano?->nombre ?? 'N/A')
            ->add('apellido', fn($row) => $row->ciudadano?->apellido ?? 'N/A')
            ->add('email', fn($row) => $row->ciudadano?->email ?? 'N/A')
            ->add('telefono_movil', fn($row) => $row->ciudadano?->telefono_movil ?? 'N/A')
            ->add('whatsapp', fn($row) => $row->ciudadano?->whatsapp ?? 'N/A')
            // Datos de Sesión y Categoría
            ->add('sesion_titulo', fn($row) => $row->sesion?->titulo ?? 'Sin sesión')
            ->add('categoria_nombre', fn($row) => $row->sesion?->categoria?->nombre ?? 'Sin categoría')
            // Datos de Comisión
            ->add('comision_nombre', fn($row) => $row->comision?->nombre ?? 'Sin comisión')
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
                    'aprobada' => '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Aprobada</span>',
                    'rechazada' => '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Rechazada</span>',
                    default => $row->estado
                }
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

            Column::make('Nombre', 'nombre')
                ->sortable()
                ->searchable(),

            Column::make('Apellido', 'apellido')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Teléfono', 'telefono_movil')
                ->sortable()
                ->searchable(),

            Column::make('WhatsApp', 'whatsapp')
                ->sortable()
                ->searchable(),

            Column::make('Sesión', 'sesion_titulo')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Categoría', 'categoria_nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Comisión', 'comision_nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Motivo', 'motivo_solicitud')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Estado', 'estado_badge', 'estado')
                ->sortable()
                ->searchable(),

            Column::make('Observaciones', 'observaciones')
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
            Filter::inputText('nombre')->operators(['contains']),
            Filter::inputText('apellido')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('telefono_movil')->operators(['contains']),
            Filter::inputText('whatsapp')->operators(['contains']),
            Filter::inputText('comision_nombre')->operators(['contains']),
        ];
    }

    public function actions(DerechoDePalabra $row): array
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
                    'wire:click' => '$dispatch(\'abrir-confirmar-modal\', { id: ' . $row->id . ' })',
                    'title' => 'Confirmar solicitud'
                ]),

            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes([
                    'onclick' => "confirmDeleteDerechoPalabra({$row->id})",
                    'title' => 'Eliminar solicitud'
                ]),
        ];
    }
}
