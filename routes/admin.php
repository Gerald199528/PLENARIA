<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;



Route::middleware(['auth'])->group(function () {

 //Ruta de Dashboard
    Volt::route('/dashboard', 'pages.admin.dashboard.index')->name('dashboard');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');

                // Rutas para la gestión de usuarios
    Volt::route('/users', 'pages.admin.users.index')
    ->middleware('permission:view-user')
    ->name('users.index');

    Volt::route('/users/create', 'pages.admin.users.create')
    ->middleware('permission:create-user')
    ->name('users.create');

    Volt::route('/users/{user}/edit', 'pages.admin.users.edit')
    ->middleware('permission:edit-user')
    ->name('users.edit');

    Volt::route('/users/{user}/show', 'pages.admin.users.show')
    ->middleware('permission:view-user')
    ->name('users.show');

    Volt::route('/users/{user}', 'pages.admin.users.destroy')
    ->middleware('permission:delete-user')
    ->name('users.destroy');

        // Rutas para la gestión de roles
    Volt::route('/roles', 'pages.admin.roles.index')
    ->middleware('permission:view-role')
    ->name('roles.index');

    Volt::route('/roles/create', 'pages.admin.roles.create')
    ->middleware('permission:create-role')
    ->name('roles.create');

    Volt::route('/roles/{role}/edit', 'pages.admin.roles.edit')
    ->middleware('permission:edit-role')
    ->name('roles.edit');

    Volt::route('/roles/{role}/show', 'pages.admin.roles.show')
    ->middleware('permission:view-role')
    ->name('roles.show');

    Volt::route('/roles/{role}', 'pages.admin.roles.destroy')
    ->middleware('permission:delete-role')
    ->name('roles.destroy');

        // Rutas para la gestión de permisos
    Volt::route('/permissions', 'pages.admin.permissions.index')
    ->middleware('permission:view-permission')
    ->name('permissions.index');

    Volt::route('/permissions/create', 'pages.admin.permissions.create')
    ->middleware('permission:create-permission')
    ->name('permissions.create');

    Volt::route('/permissions/{permission}/edit', 'pages.admin.permissions.edit')
    ->middleware('permission:edit-permission')
    ->name('permissions.edit');

    Volt::route('/permissions/{permission}/show', 'pages.admin.permissions.show')
    ->middleware('permission:view-permission')
    ->name('permissions.show');

    Volt::route('/permissions/{permission}', 'pages.admin.permissions.destroy')
    ->middleware('permission:delete-permission')
    ->name('permissions.destroy');

        // Rutas para la gestión de configuración
        Volt::route('/settings', 'pages.admin.settings.index')
            ->middleware('permission:view-setting')
            ->name('settings.index');

        Volt::route('/settings/general', 'pages.admin.settings.general')
            ->middleware('permission:view-setting')
            ->name('settings.general');

        Volt::route('/settings/logo', 'pages.admin.settings.logo')
            ->middleware('permission:view-setting')
            ->name('settings.logo');

        Volt::route('/settings/profile', 'pages.admin.profile.index')
            ->middleware('permission:profile-setting')
            ->name('profile.index');

        Volt::route('/color_piker', 'pages.admin.color_piker.index')
        ->middleware('permission:color_piker-setting')
        ->name('color_piker.index');


        // Rutas para la gestión de ordenanzas
        Volt::route('/ordenanzas', 'pages.admin.ordenanzas.index')
            ->middleware('permission:view-ordenanza')
            ->name('ordenanzas.index');

            Volt::route('/ordenanzas/create', 'pages.admin.ordenanzas.create')
            ->middleware('permission:create-ordenanza')
            ->name('ordenanzas.create');

        Volt::route('/ordenanzas/{ordenanza}/edit', 'pages.admin.ordenanzas.edit')
            ->middleware('permission:edit-ordenanza')
            ->name('ordenanzas.edit');

        // Rutas para la gestión de gacetas
        Volt::route('/gacetas', 'pages.admin.gacetas.index')
            ->middleware('permission:view-gaceta')
            ->name('gacetas.index');


            Volt::route('/gacetas/create', 'pages.admin.gacetas.create')
            ->middleware('permission:create-gaceta')
            ->name('gacetas.create');

            Volt::route('/gacetas/{gaceta}/edit', 'pages.admin.gacetas.edit')
            ->middleware('permission:edit-gaceta')
            ->name('gacetas.edit');

                // Rutas para la gestión de acuerdos
                Volt::route('/acuerdos', 'pages.admin.acuerdos.index')
                ->middleware('permission:view-acuerdo')
                ->name('acuerdos.index');


                Volt::route('/acuerdo/create', 'pages.admin.acuerdos.create')
                ->middleware('permission:create-acuerdo')
                ->name('acuerdos.create');

                Volt::route('/acuerdos/{acuerdo}/edit', 'pages.admin.acuerdos.edit')
                ->middleware('permission:edit-acuerdo')
                ->name('acuerdos.edit');



                // Rutas para la gestión de categoría de instrumentos legales
                Volt::route('/categoria-instrumentos', 'pages.admin.categoria_instrumentos.index')
                ->middleware('permission:view-categoria-instrumento')
                ->name('categoria-instrumentos.index');

                Volt::route('/categoria-instrumentos/create', 'pages.admin.categoria_instrumentos.create')
                ->middleware('permission:create-categoria-instrumento')
                ->name('categoria-instrumentos.create');

                // Editar
                Volt::route('/categoria-instrumentos/{categoria_instrumento}/edit', 'pages.admin.categoria_instrumentos.edit')
                ->middleware('permission:edit-categoria-instrumento')
                ->name('categoria-instrumentos.edit');



            // Rutas para la gestión de consejales

            Volt::route('/concejales', 'pages.admin.concejales.index')
            ->middleware('permission:view-concejal')
            ->name('concejales.index');

                Volt::route('/concejales/create', 'pages.admin.concejales.create')
                ->middleware('permission:create-consejal')
                ->name('concejales.create');

                Volt::route('/concejales/{concejal}/edit', 'pages.admin.concejales.edit')
                ->middleware('permission:edit-concejal')
                ->name('concejales.edit');

            // Rutas para añadir miembros
                Volt::route('/miembros', 'pages.admin.miembros.index')
                ->middleware('permission:view-miembro')
                ->name('miembros.index');


                Volt::route('/miembros/create', 'pages.admin.miembros.create')
                ->middleware('permission:create-miembro')
                ->name('miembros.create');

            Volt::route('/miembros/{miembro}/edit', 'pages.admin.miembros.edit')
                ->middleware('permission:edit-miembro')
                ->name('miembros.edit');

                    // Rutas para la gestión de comisiones

            Volt::route('/comisiones', 'pages.admin.comisiones.index')
            ->middleware('permission:view-comision')
            ->name('comisiones.index');


                //Ruta para  Cronistas
            Volt::route('/cronistas', 'pages.admin.cronistas.index')
                ->middleware('permission:view-cronista')
                ->name('cronistas.index');

            Volt::route('/cronistas/create', 'pages.admin.cronistas.create')
                ->middleware('permission:create-cronista')
                ->name('cronistas.create');

            Volt::route('/cronistas/{cronista}/edit', 'pages.admin.cronistas.edit')
                ->middleware('permission:edit-cronista')
                ->name('cronistas.edit');

            Volt::route('cronistas/{cronista}/show', 'pages.admin.cronistas.show')
                    ->middleware('permission:show-cronista')
                    ->name('cronista.show');

                //Ruta para  Crónicas
            Volt::route('/cronicas', 'pages.admin.cronicas.index')
                ->middleware('permission:view-cronica')
                ->name('cronicas.index');

            Volt::route('/cronicas/create', 'pages.admin.cronicas.create')
                ->middleware('permission:create-cronica')
                ->name('cronicas.create');

            Volt::route('/cronicas/{cronica}/edit', 'pages.admin.cronicas.edit')
                ->middleware('permission:edit-cronica')
                ->name('cronicas.edit');

            Volt::route('cronicas/show', 'pages.admin.cronicas.show')
                    ->middleware('permission:show-cronica')
                    ->name('cronicas.show');

                    //Ruta Categorías de Crónicas
            Volt::route('/categoria_cronicas', 'pages.admin.categoria_cronicas.index')
                ->middleware('permission:view-categoria_cronicas')
                ->name('categoria_cronicas.index');

            Volt::route('/categoria_cronicas/create', 'pages.admin.categoria_cronicas.create')
                        ->middleware('permission:create-categoria_cronica')
                        ->name('categoria_cronicas.create');

            Volt::route('/categoria_cronicas/{categoria_cronicas}/edit', 'pages.admin.categoria_cronicas.edit')
                ->middleware('permission:edit-categoria_cronicas')
                ->name('categoria_cronicas.edit');


                     //-----------------------Actas de sesiones-----------------------------------------
                        //sesion ordinaria
                    Volt::route('/sesion_ordinaria', 'pages.admin.sesion_ordinaria.index')
                    ->middleware('permission:view-sesion_ordinaria')
                    ->name('sesion_ordinaria.index');


                Volt::route('/sesion_ordinaria/create', 'pages.admin.sesion_ordinaria.create')
                            ->middleware('permission:create-sesion_ordinaria')
                            ->name('sesion_ordinaria.create');

                Volt::route('/sesion_ordinaria/{sesion_ordinaria}/edit', 'pages.admin.sesion_ordinaria.edit')
                    ->middleware('permission:edit-sesion_ordinaria')
                    ->name('sesion_ordinaria.edit');

                  //sesion extraordinaria
                Volt::route('/sesion_extraordinaria', 'pages.admin.sesion_extraordinaria.index')
                ->middleware('permission:view-sesion_extraordinaria')
                ->name('sesion_extraordinaria.index');

                Volt::route('/sesion_extraordinaria/create', 'pages.admin.sesion_extraordinaria.create')
                            ->middleware('permission:create-sesion_extraordinaria')
                            ->name('sesion_extraordinaria.create');
                Volt::route('/sesion_extraordinaria/{sesion_extraordinaria}/edit', 'pages.admin.sesion_extraordinaria.edit')
                    ->middleware('permission:edit-sesion_extraordinaria')
                    ->name('sesion_extraordinaria.edit');

                      //sesion solemne
                Volt::route('/sesion_solemne', 'pages.admin.sesion_solemne.index')
                ->middleware('permission:view-sesion_solemne')
                ->name('sesion_solemne.index');

                Volt::route('/sesion_solemne/create', 'pages.admin.sesion_solemne.create')
                            ->middleware('permission:create-sesion_solemne')
                            ->name('sesion_solemne.create');


                Volt::route('/sesion_solemne/{sesion_solemne}/edit', 'pages.admin.sesion_solemne.edit')
                    ->middleware('permission:edit-sesion_solemne')
                    ->name('sesion_solemne.edit');

                                 //sesion especial
                Volt::route('/sesion_especial', 'pages.admin.sesion_especial.index')
                ->middleware('permission:view-sesion_especial')
                ->name('sesion_especial.index');

                Volt::route('/sesion_especial/create', 'pages.admin.sesion_especial.create')
                            ->middleware('permission:create-sesion_especial')
                            ->name('sesion_especial.create');


                Volt::route('/sesion_especial/{sesion_especial}/edit', 'pages.admin.sesion_especial.edit')
                    ->middleware('permission:edit-sesion_especial')
                    ->name('sesion_especial.edit');


                                 //Datos de la Empresa
                Volt::route('/empresa', 'pages.admin.empresa.index')
                ->middleware('permission:view-empresa')
                ->name('empresa.index');

                Volt::route('/empresa/create', 'pages.admin.empresa.create')
                ->middleware('permission:create-empresa')
                ->name('empresa.create');

                Volt::route('/empresa/{empresa}/edit', 'pages.admin.empresa.edit')
                ->middleware('permission:edit-empresa')
                ->name('empresa.edit');

                     //Noticias
                Volt::route('/noticias', 'pages.admin.noticias.index')
                ->middleware('permission:view-noticias')
                ->name('noticias.index');

                Volt::route('/noticias/create', 'pages.admin.noticias.create')
                ->middleware('permission:create-noticias')
                ->name('noticias.create');


                Volt::route('/noticias/{noticia}/edit', 'pages.admin.noticias.edit')
                ->middleware('permission:edit-noticias')
                ->name('noticias.edit');

                     //categorias_participacion

                Volt::route('/categorias_participacion', 'pages.admin.categorias_participacion.index')
                ->middleware('permission:view-categorias_participacion')
                ->name('categorias_participacion.index');

                Volt::route('/categorias_participacion/create', 'pages.admin.categorias_participacion.create')
            ->middleware('permission:create-categorias_participacion')
            ->name('categorias_participacion.create');

            Volt::route('/categorias_participacion/{categorias_participacion}/edit', 'pages.admin.categorias_participacion.edit')
            ->middleware('permission:edit-categorias_participacion')
            ->name('categorias_participacion.edit');



                        //sesion municipal
            Volt::route('/sesion_municipal', 'pages.admin.sesion_municipal.index')
            ->middleware('permission:view-sesion_municipal')
            ->name('sesion_municipal.index');

            Volt::route('/sesion_municipal/create', 'pages.admin.sesion_municipal.create')
            ->middleware('permission:create-sesion_municipal')
            ->name('sesion_municipal.create');

            Volt::route('/sesion_municipal/{sesion_municipal}/edit', 'pages.admin.sesion_municipal.edit')
            ->middleware('permission:edit-sesion_municipal')
            ->name('sesion_municipal.edit');

                        //Derecho de palabra

            Volt::route('/derecho_palabra', 'pages.admin.derecho_palabra.index')
            ->middleware('permission:view-derecho_palabra')
            ->name('derecho_palabra.index');



                     //Atencion Ciudadana

         Volt::route('/atencion_ciudadana', 'pages.admin.atencion_ciudadana.index')
            ->middleware('permission:view-atencion_ciudadana')
            ->name('atencion_ciudadana.index');



        // Tipos de Solicitud "Atención Ciudadana"

        Volt::route('/tipos_solicitud', 'pages.admin.tipos_solicitud.index')
            ->middleware('permission:view-tipos_solicitud')
            ->name('tipos_solicitud.index');

        Volt::route('/tipos_solicitud/create', 'pages.admin.tipos_solicitud.create')
            ->middleware('permission:create-tipos_solicitud')
            ->name('tipos_solicitud.create');

        Volt::route('/tipos_solicitud/{tipo_solicitud}/edit', 'pages.admin.tipos_solicitud.edit')
            ->middleware('permission:edit-tipos_solicitud')
            ->name('tipos_solicitud.edit');









    });


        // Rutas para salir del sistema
    Route::post('logout', App\Livewire\Actions\Logout::class)
        ->name('logout');
