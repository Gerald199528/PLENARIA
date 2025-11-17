<?php

namespace App\Livewire;

use App\Models\SesionOrdinaria;
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

final class SesionOrdinariaTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'sesion-ordinaria-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'sesion_ordinaria')
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
        return SesionOrdinaria::query();
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
            'ruta',
            'fecha_sesion',
        ])->add('ruta_nombre', fn($row) => 
            basename($row->ruta)  // Extrae solo el nombre del archivo
        )->add('fecha_sesion_formatted', fn($row) => 
            Carbon::parse($row->fecha_sesion)
                ->timezone('America/Caracas')
                ->format('d/m/Y h:i A')
        )->add('fecha_importacion_formatted', fn($row) => 
            Carbon::parse($row->fecha_importacion)
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

            Column::make('Archivo PDF', 'ruta_nombre', 'ruta')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Fecha Sesión', 'fecha_sesion_formatted', 'fecha_sesion')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Importación', 'fecha_importacion_formatted', 'fecha_importacion')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('nombre')->operators(['contains']),
            Filter::inputText('ruta')->operators(['contains']),
            Filter::inputText('fecha_sesion')->operators(['contains']),
            Filter::inputText('fecha_importacion')->operators(['contains']),
        ];
    }

    public function actions(\App\Models\SesionOrdinaria $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('bg-indigo-600 text-white px-3 py-1 rounded-md hover:bg-indigo-700 shadow-sm mr-2')
                ->route('admin.sesion_ordinaria.edit', ['sesion_ordinaria' => $row->id])
                ->attributes(['wire:navigate' => true]),
        
            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 shadow-sm')
                ->attributes([
                    'onclick' => "confirmDelete({$row->id})"
                ]),
        ];
    }
}