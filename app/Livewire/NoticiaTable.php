<?php

namespace App\Livewire;

use App\Models\Noticia;
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

final class NoticiaTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'noticia-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'noticias')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Noticia::query()->with(['cronista', 'cronica']);
    }

    public function relationSearch(): array
    {
        return [
            'cronista' => ['nombre_completo'],
            'cronica' => ['titulo', 'archivo_pdf'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('titulo')
            ->add('tipo')
            ->add('contenido')
            ->add('destacada')
            ->add('fecha_publicacion_formatted', fn (Noticia $model) => Carbon::parse($model->fecha_publicacion)->format('d/m/Y'))
            ->add('cronista_nombre', fn (Noticia $model) => $model->cronista?->nombre_completo ?? '-')
            ->add('cronica_titulo', fn (Noticia $model) => $model->cronica?->titulo ?? '-')
            ->add('cronica_pdf_nombre', fn (Noticia $model) => $model->cronica?->archivo_pdf 
                ? '<div class="flex justify-center"><a href="' . asset('storage/' . $model->cronica->archivo_pdf) . '" target="_blank" class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm font-semibold transition-all"><i class="fas fa-file-pdf text-lg"></i></a></div>'
                : '-')
            ->add('created_at_formatted', fn (Noticia $model) => $model->created_at->format('d/m/Y h:i A'));
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
                ->searchable(),


            Column::make('Tipo', 'tipo')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Publicación', 'fecha_publicacion_formatted', 'fecha_publicacion')
                ->sortable(),

            Column::make('Contenido', 'contenido')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),


            Column::make('Cronista', 'cronista_nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Crónica', 'cronica_titulo')
                ->sortable()
                ->searchable(),

            Column::make('PDF Crónica', 'cronica_pdf_nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('text-center'),

            Column::make('Destacada', 'destacada')
                ->sortable()
                ->toggleable(),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('titulo')->operators(['contains']),
            Filter::inputText('tipo')->operators(['contains']),
            Filter::inputText('cronica_titulo')->operators(['contains']),     
            Filter::inputText('fecha_publicacion')->operators(['contains']),
            Filter::inputText('contenido')->operators(['contains']),
            Filter::inputText('cronista_nombre')->operators(['contains']),
        ];
    }
public function actions($row): array
{
    $actions = [
        // Editar
        Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->route('admin.noticias.edit', ['noticia' => $row->id])
            ->attributes(['wire:navigate' => true, 'title' => 'Editar noticia']),

        // Eliminar
        Button::add('delete')
            ->slot('<i class="fas fa-trash"></i>')
            ->class('bg-rose-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-rose-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes(['onclick' => "confirmDeleteNoticia({$row->id})", 'title' => 'Eliminar noticia']),

        // Ver imagen
        Button::add('imagen')
            ->slot('<i class="fas fa-image"></i>')
            ->class('bg-amber-500 text-black px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-amber-600 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes([
                'onclick' => $row->imagen
                    ? "window.open('" . asset('storage/' . $row->imagen) . "', '_blank')"
                    : "alert('No hay imagen disponible')",
                'title' => 'Ver imagen'
            ]),
    ];

    // Ver PDF - SOLO SI HAY PDF
    if ($row->archivo_pdf) {
        $actions[] = Button::add('pdf')
            ->slot('<i class="fas fa-file-pdf"></i>')
            ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes([
                'onclick' => "window.open('" . asset('storage/' . $row->archivo_pdf) . "', '_blank')",
                'title' => 'Ver PDF'
            ]);
    }

    // Ver video - SOLO SI HAY VIDEO (archivo o URL)
    if ($row->video_url || $row->video_archivo) {
        $actions[] = Button::add('video')
            ->slot('<i class="fas fa-play"></i>')
            ->class('bg-purple-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-purple-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes([
                'onclick' => $row->video_url
                    ? "window.open('" . $row->video_url . "', '_blank')"
                    : "window.open('" . asset('storage/' . $row->video_archivo) . "', '_blank')",
                'title' => 'Ver video'
            ]);
    }

    return $actions;
}
}