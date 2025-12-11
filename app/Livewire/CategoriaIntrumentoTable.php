<?php

namespace App\Livewire;

use App\Models\CategoriaInstrumento;
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

final class CategoriaIntrumentoTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'categoria-intrumento-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'categorias_instrumentos')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
            ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100])
            ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return CategoriaInstrumento::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields([
            'nombre',
            'tipo_categoria',
            'observacion',
        ])
        ->add('created_at_formatted', fn($cat) => Carbon::parse($cat->created_at)
            ->timezone('America/Caracas')
            ->format('d/m/Y h:i A'));
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

            Column::make('Tipo Categoría', 'tipo_categoria')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Observación', 'observacion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Fecha Creación', 'created_at_formatted', 'created_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('nombre')->operators(['contains']),
            Filter::inputText('tipo_categoria')->operators(['contains']),
            Filter::inputText('observacion')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
        ];
    }

   public function actions(CategoriaInstrumento $row): array
{
    return [
        // PDF
        Button::add('pdf')
            ->slot('<i class="fas fa-file-pdf"></i>')
            ->class('bg-blue-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-blue-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes([
                'wire:click' => "\$parent.call('downloadCategoryPdf', {$row->id})",
                'title' => 'Descargar PDF',
                'style' => 'cursor: pointer;'
            ]),

        // Editar
        Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
            ->route('admin.categoria-instrumentos.edit', ['categoria_instrumento' => $row->id])
            ->attributes(['wire:navigate' => true, 'title' => 'Editar']),

        // Eliminar
        Button::add('delete')
            ->slot('<i class="fas fa-trash"></i>')
            ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
            ->attributes([
                'onclick' => "confirmDelete({$row->id})",
                'title' => 'Eliminar'
            ]),
    ];
}
}