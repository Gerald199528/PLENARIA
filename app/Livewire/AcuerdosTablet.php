<?php

namespace App\Livewire;

use App\Models\Acuerdo;
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




final class AcuerdosTablet extends PowerGridComponent
{

    use WithExport;
    public string $tableName = 'acuerdos-tablet';


    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'acuerdos')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100, 500, 1000, 0])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Acuerdo::query()->with('categoria');
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
        ])
        ->add('categoria_nombre', function ($acuerdo) {
            return $acuerdo->categoria ? $acuerdo->categoria->nombre : 'Sin categoría';
        })
        ->add('fecha_importacion_formatted', function ($acuerdo) {
            return $acuerdo->fecha_importacion
                ? Carbon::parse($acuerdo->fecha_importacion)
                    ->timezone('America/Caracas')
                    ->format('d/m/Y h:i A')
                : null;
        })
        ->add('fecha_aprobacion_formatted', function ($acuerdo) {
            return $acuerdo->fecha_aprobacion
                ? Carbon::parse($acuerdo->fecha_aprobacion)
                    ->timezone('America/Caracas')
                    ->format('d/m/Y h:i A')
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

            Column::make('Nombre', 'nombre')
                ->sortable()
                ->searchable(),
                Column::make('Observacion', 'observacion')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'categoria_nombre')
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
            Filter::inputText('observacion')->operators(['contains']),
            Filter::inputText('categoria_nombre')->operators(['contains']),
            Filter::inputText('fecha_importacion')->operators(['contains']),
            Filter::inputText('fecha_aprobacion')->operators(['contains']),
        ];
    }

  public function actions(Acuerdo $row): array
{
    return [
        // Editar
        Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
            ->route('admin.acuerdos.edit', ['acuerdo' => $row->id])
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
