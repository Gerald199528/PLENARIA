<?php

namespace App\Livewire;

use App\Models\Gaceta;
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
final class GacetasTable extends PowerGridComponent
{
    
    use WithExport;

    public string $tableName = 'gacetas-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'gacetas')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100, 500, 1000, 0])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Gaceta::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields([            
            'nombre',
            'ruta',
            'observacion',
        ])
        ->add('fecha_importacion_formatted', function ($gaceta) {
            return $gaceta->fecha_importacion
                ? Carbon::parse($gaceta->fecha_importacion)->format('d/m/Y h:i A')
                : null;
        })
        ->add('fecha_importacion_formatted', function ($gaceta) {
            return $gaceta->fecha_importacion
                ? Carbon::parse($gaceta->fecha_importacion)
                    ->timezone('America/Caracas') // opcional, según tu zona
                    ->format('d/m/Y h:i A')       // h para 12h, H para 24h
                : null;
        })
        ->add('fecha_aprobacion_formatted', function ($gaceta) {
            return $gaceta->fecha_aprobacion
                ? Carbon::parse($gaceta->fecha_aprobacion)
                    ->timezone('America/Caracas')
                    ->format('d/m/Y h:i A')
                : null;
        })
    ;
        
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('N°', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'nombre')
                ->sortable()
                ->searchable(),

                Column::make('Categoría', 'categoria')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Observación', 'observacion')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Fecha Aprobación', 'fecha_aprobacion_formatted', 'fecha_aprobacion')
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
            Filter::inputText('categoria')->operators(['contains']),
            Filter::inputText('observacion')->operators(['contains']),
            Filter::inputText('fecha_importacion')->operators(['contains']),
            Filter::inputText('fecha_aprobacion')->operators(['contains']),
         
        ];
    }
    public function actions(Gaceta $row): array
{
    return [
        // Editar
        Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
            ->route('admin.gacetas.edit', ['gaceta' => $row->id])
            ->attributes(['wire:navigate' => true, 'title' => 'Editar']),        

        // Ver PDF
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
