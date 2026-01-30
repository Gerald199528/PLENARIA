<?php

namespace App\Livewire;

use App\Models\TipoSolicitud;
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

final class TipoSolicitudTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'tipo-solicitud-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'tipos_solicitud')
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
        return TipoSolicitud::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre')
            ->add('descripcion')
            ->add('activo')
            ->add('created_at')
            // Formato de fecha
            ->add('created_at_formatted', fn($row) =>
                Carbon::parse($row->created_at)
                    ->timezone('America/Caracas')
                    ->format('d/m/Y h:i A')
            )
            // Badge de estado activo
            ->add('activo_badge', fn($row) =>
                $row->activo
                    ? '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Inactivo</span>'
            );
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'nombre')
                ->sortable()
                ->searchable(),

            Column::make('Descripción', 'descripcion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Estado', 'activo_badge', 'activo')
                ->sortable()
                ->searchable(),

            Column::make('Fecha de Creación', 'created_at_formatted')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('nombre')->operators(['contains']),
            Filter::inputText('descripcion')->operators(['contains']),
        ];
    }

    public function actions(TipoSolicitud $row): array
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

            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->route('admin.tipos_solicitud.edit', ['tipo_solicitud' => $row->id])
                ->attributes([
                    'title' => 'Editar tipo de solicitud',
                ]),

            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes([
                    'onclick' => "confirmDelete({$row->id})",
                    'title' => 'Eliminar tipo de solicitud'
                ]),
        ];
    }
}
