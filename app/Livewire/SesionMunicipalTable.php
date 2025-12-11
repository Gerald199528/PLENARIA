<?php

namespace App\Livewire;

use App\Models\SesionMunicipal;
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

final class SesionMunicipalTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'sesion-municipal-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'sesion_municipal')
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
        return SesionMunicipal::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields([
            'id',
            'titulo',
            'descripcion',
            'fecha_hora',
            'estado',
            'created_at',
        ])->add('categoria_nombre', fn($row) =>
            \Illuminate\Support\Facades\DB::table('categorias_participacion')
                ->where('id', $row->categoria_participacion_id)
                ->value('nombre') ?? 'Sin categoría'
        )->add('fecha_hora_formatted', fn($row) =>
            Carbon::parse($row->fecha_hora)
                ->timezone('America/Caracas')
                ->format('d/m/Y h:i A')
        )->add('fecha_creacion_formatted', fn($row) =>
            Carbon::parse($row->created_at)
                ->timezone('America/Caracas')
                ->format('d/m/Y h:i A')
        )->add('estado_badge', fn($row) =>
            match($row->estado) {
                'proxima' => '<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">Próxima</span>',
                'abierta' => '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Abierta</span>',
                'cerrada' => '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Cerrada</span>',
                'completada' => '<span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">Completada</span>',
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

            Column::make('Título', 'titulo')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Categoría', 'categoria_nombre')
                 ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Descripción', 'descripcion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-sm'),

            Column::make('Fecha y hora', 'fecha_hora_formatted', 'fecha_hora')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'estado_badge', 'estado')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('titulo')->operators(['contains']),
            Filter::inputText('categoria_nombre')->operators(['contains']),
            Filter::inputText('descripcion')->operators(['contains']),
            Filter::inputText('estado')->operators(['contains']),
            Filter::datetimepicker('fecha_hora'),
        ];
    }

    public function actions(SesionMunicipal $row): array
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
                ->route('admin.sesion_municipal.edit', ['sesion_municipal' => $row->id])
                ->attributes(['wire:navigate' => true]),
        
            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes(['onclick' => "confirmDelete({$row->id})"]),
        ];
    }
}