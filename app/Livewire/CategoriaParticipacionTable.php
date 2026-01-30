<?php

namespace App\Livewire;

use App\Models\CategoriaParticipacion;
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

final class CategoriaParticipacionTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'categoria-participacion-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'categorias_participacion')
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
        return CategoriaParticipacion::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields([
            'id',
            'nombre',
            'descripcion',
            'created_at',
        ])->add('fecha_creacion_formatted', fn($row) =>
            Carbon::parse($row->created_at)
                ->timezone('America/Caracas')
                ->format('d/m/Y h:i A')
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
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Descripción', 'descripcion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

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
            Filter::datetimepicker('created_at'),
        ];
    }
public function actions(CategoriaParticipacion $row): array
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
            ->route('admin.categorias_participacion.edit', ['categorias_participacion' => $row->id])
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
