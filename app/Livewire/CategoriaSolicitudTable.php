<?php

namespace App\Livewire;

use App\Models\CategoriaSolicitud;
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

final class CategoriaSolicitudTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'categoria-solicitud-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'categorias_solicitud')
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
        return CategoriaSolicitud::query()->with('tipo');
    }

    public function relationSearch(): array
    {
        return [
            'tipo' => ['nombre', 'descripcion'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields([
            'id',
            'nombre',
            'descripcion',
            'tipo_solicitud_id',
            'activo',
            'created_at',
        ])
        ->add('fecha_creacion_formatted', fn($row) =>
            Carbon::parse($row->created_at)
                ->timezone('America/Caracas')
                ->format('d/m/Y h:i A')
        )
        ->add('tipo_nombre_formatted', fn($row) => $row->tipo?->nombre ?? 'N/A')
        ->add('tipo_descripcion_formatted', fn($row) => $row->tipo?->descripcion ?? 'N/A')
        ->add('tipo_activo_formatted', function($row) {
            $estado = $row->tipo?->activo ? 'Activo' : 'Inactivo';
            $clase = $row->tipo?->activo
                ? 'bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold border border-green-300'
                : 'bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold border border-red-300';
            return '<span class="' . $clase . '">' . $estado . '</span>';
        })
        ->add('activo_formatted', function($row) {
            $estado = $row->activo ? 'Activo' : 'Inactivo';
            $clase = $row->activo
                ? 'bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold border border-green-300'
                : 'bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold border border-red-300';
            return '<span class="' . $clase . '">' . $estado . '</span>';
        });
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('ID Categoría', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Nombre Categoría', 'nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Descripción Categoría', 'descripcion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Tipo de Solicitud', 'tipo_nombre_formatted')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Descripción de Solicitud', 'tipo_descripcion_formatted')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Estado de Solicitud', 'tipo_activo_formatted')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Creación', 'fecha_creacion_formatted', 'created_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('nombre')->operators(['contains']),
            Filter::inputText('descripcion')->operators(['contains']),
            Filter::boolean('activo', 'Activo', 'Inactivo'),
            Filter::datetimepicker('created_at'),
        ];
    }

    public function actions(CategoriaSolicitud $row): array
    {
        return [


                    Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->route('admin.categorias_solicitud.edit', ['categorias_solicitud' => $row->id])
                ->attributes(['wire:navigate' => true]),

            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes([
                    'onclick' => "confirmDelete({$row->id})"
                ]),
        ];
    }
}
