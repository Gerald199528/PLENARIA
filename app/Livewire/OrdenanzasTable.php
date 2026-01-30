<?php

namespace App\Livewire;

use App\Models\Ordenanza;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class OrdenanzasTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'ordenanzas-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'ordenanzas') 
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100, 500, 1000, 0])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Ordenanza::query()->with('categoria');
    }

    public function relationSearch(): array
    {
        return [
            'categoria' => [
                'nombre',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields([
            'nombre',
            'ruta',
            'observacion',
        ])
        ->add('categoria_nombre', function ($ordenanza) {
            return $ordenanza->categoria ? $ordenanza->categoria->nombre : 'Sin categoría';
        })
        ->add('fecha_importacion_formatted', function ($ordenanza) {
            return $ordenanza->fecha_importacion
                ? Carbon::parse($ordenanza->fecha_importacion)->format('d/m/Y h:i A')
                : null;
        })
        ->add('fecha_aprobacion_formatted', function ($ordenanza) {
            return $ordenanza->fecha_aprobacion
                ? Carbon::parse($ordenanza->fecha_aprobacion)->format('d/m/Y h:i A')
                : null;
        });
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('N°', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Nombres', 'nombre')
                ->sortable()
                ->searchable(),

            Column::make('Categorías', 'categoria_nombre')
                ->sortable()
                ->searchable(),

            Column::make('Observación', 'observacion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Fecha Aprobación', 'fecha_aprobacion_formatted', 'fecha_aprobacion')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Importación', 'fecha_importacion_formatted', 'fecha_importacion' )
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('nombre')->operators(['contains']),
            Filter::inputText('categoria_nombre')->operators(['contains']),
            Filter::inputText('fecha_importacion')->operators(['contains']),
            Filter::inputText('observacion')->operators(['contains']),
            Filter::inputText('fecha_aprobacion')->operators(['contains']),
        ];
    }
    public function actions(Ordenanza $row): array
{
    return [

        // Editar
        Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
            ->route('admin.ordenanzas.edit', ['ordenanza' => $row->id])
            ->attributes(['wire:navigate' => true, 'title' => 'Editar']),   

        // Ver documento
        Button::add('view_pdf')
            ->slot('<i class="fas fa-eye"></i>')
            ->class('bg-amber-500 text-black px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-amber-600 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
            ->attributes([
                'onclick' => "window.open('" . asset('storage/' . $row->ruta) . "', '_blank')",
                'title' => 'Ver PDF'
            ]),

        // Descargar PDF
        Button::add('download_pdf')
            ->slot('<i class="fas fa-download"></i>')
            ->class('bg-emerald-600 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-emerald-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
            ->attributes([
                'onclick' => "const link = document.createElement('a');
                            link.href = '" . asset('storage/' . $row->ruta) . "';
                            link.download = '" . basename($row->ruta) . "';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);",
                'title' => 'Descargar'
            ]),

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
