<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\CoadminController;
use App\Http\Controllers\ReceptorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/************************************ */
/************************************ */
//Rutas antes del Login
Route::get('/', [LoginController::class, 'index'])
    ->name('home.index');

Route::post('/home', [LoginController::class, 'login'])
    ->name('home.store');

Route::get('/home', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('home.destroy');
//Terminan rutas antes del Login
/************************************ */
/************************************ */


/************************************ */
/************************************ */
//******** Ruta Administrador ********//
/************************************ */
/************************************ */
Route::controller(AdministradorController::class)->group(function(){

    Route::get('/admin_index','index_admin')
        ->middleware('auth.admin')
        ->name('admin.index');

    Route::get('/resetear_pass/{id}', 'password')
        ->middleware('auth.admin')
        ->name('admin.pass');

    Route::post('/resetear_pass', 'actualizar_password')
        ->middleware('auth.admin')
        ->name('admin.actualizar_pass');

    /**********************/
    /**********************/
    /****** Usuarios ******/
    Route::get('/admin_usuarios', 'users_admin')
        ->middleware('auth.admin')
        ->name('admin.usuarios');

    Route::get('/usuario_nuevo', 'nuevo')
        ->middleware('auth.admin')
        ->name('usuarios.nuevo');

    Route::post('/usuario_crear', 'crear')
        ->middleware('auth.admin')
        ->name('usuarios.crear');

    Route::get('/editar_usuarios/{id}', 'editar')
        ->middleware('auth.admin')
        ->name('usuarios.editar');

    Route::post('/actualizar_usuarios', 'actualizar')
        ->middleware('auth.admin')
        ->name('usuarios.actualizar');

    Route::get('/inactivar_usuarios/{id}', 'inactivar')
        ->middleware('auth.admin')
        ->name('usuarios.inactivar');

    Route::get('/usuarios_inactivos', 'inactivos')
        ->middleware('auth.admin')
        ->name('usuarios.inactivos');

    /**********************/
    /**********************/
    /****** Personal ******/
    Route::get('/admin_personal', 'personal_admin')
        ->middleware('auth.admin')
        ->name('admin.personal');
    
    Route::get('/personal_nuevo', 'personal_nuevo')
        ->middleware('auth.admin')
        ->name('personal.nuevo');
    
    Route::post('/personal_crear', 'personal_crear')
        ->middleware('auth.admin')
        ->name('personal.crear');

    Route::get('/editar_personal/{id}', 'personal_editar')
        ->middleware('auth.admin')
        ->name('personal.editar');

    Route::post('/actualizar_personal', 'personal_actualizar')
        ->middleware('auth.admin')
        ->name('personal.actualizar');

    Route::get('/inactivar_personal/{id}', 'personal_inactivar')
        ->middleware('auth.admin')
        ->name('personal.inactivar');

    Route::get('/personal_inactivos', 'personal_inactivos')
        ->middleware('auth.admin')
        ->name('personal.inactivos');
    

    /**********************/
    /**********************/
    /***** Categorías *****/
    Route::get('/admin_categorias', 'categoria_index')
        ->middleware('auth.admin')
        ->name('admin.categorias');

    Route::get('/categoria_nuevo', 'categoria_nuevo')
        ->middleware('auth.admin')
        ->name('categorias.nuevo');
        
    Route::post('/categoria_crear', 'categoria_crear')
        ->middleware('auth.admin')
        ->name('categorias.crear');

    Route::get('/editar_categorias/{id}', 'categoria_editar')
        ->middleware('auth.admin')
        ->name('categorias.editar');
        
    Route::post('/actualizar_categorias', 'categoria_actualizar')
        ->middleware('auth.admin')
        ->name('categorias.actualizar');
    
    Route::get('/inactivar_categorias/{id}', 'categoria_inactivar')
        ->middleware('auth.admin')
        ->name('categorias.inactivar');

    /**********************/
    /**********************/
    /******* Grupos *******/
    Route::get('/admin_grupos', 'grupos_admin')
        ->middleware('auth.admin')
        ->name('admin.grupos');

    Route::get('/grupo_nuevo', 'nuevo_grupo')
        ->middleware('auth.admin')
        ->name('grupos.nuevo');

    Route::post('/grupo_crear', 'crear_grupo')
        ->middleware('auth.admin')
        ->name('grupos.crear');

    Route::get('/editar_grupos/{id}', 'editar_grupo')
        ->middleware('auth.admin')
        ->name('grupos.editar');
    
    Route::get('/categorias_grupos/{id}', 'categorias_grupo')
        ->middleware('auth.admin')
        ->name('grupos.categorias');
    
    Route::post('/categorias_grupos', 'categorias_crear_grupo')
        ->middleware('auth.admin')
        ->name('grupos.categoriascrear');
    
    Route::get('/categorias_grupos_default/{id}/{grupo}', 'categorias_default_grupo')
        ->middleware('auth.admin')
        ->name('grupos.categoriasdefault');
    
    Route::post('/actualizar_grupos', 'actualizar_grupo')
        ->name('grupos.actualizar');
    
    Route::get('/inactivar_grupos/{id}', 'inactivar_grupo')
        ->middleware('auth.admin')
        ->name('grupos.inactivar');        

    /**********************/
    /**********************/
    /***** Artículos ******/
    Route::get('/admin_articulos', 'articulo_index')
        ->middleware('auth.admin')
        ->name('admin.articulos');

    Route::get('/articulo_nuevo', 'articulo_nuevo')
        ->middleware('auth.admin')
        ->name('articulos.nuevo');

    Route::post('/articulo_crear', 'articulo_crear')
        ->middleware('auth.admin')
        ->name('articulos.crear');

    Route::get('/editar_articulos/{id}','articulos_editar')
        ->middleware('auth.admin')
        ->name('articulos.editar');

    Route::post('/actualizar_articulos', 'articulos_actualizar')
        ->middleware('auth.admin')
        ->name('articulos.actualizar');

    Route::get('/inactivar_articulos/{id}', 'articulo_inactivar')
        ->middleware('auth.admin')
        ->name('articulos.inactivar');

    /**********************/
    /**********************/
    /***** Resguardos *****/
    Route::get('/resguardo_nuevo', 'resguardo_nuevo')
        ->middleware('auth.admin')
        ->name('resguardo.nuevo');
        
    Route::get('/resguardo_buscar_persona', 'resguardo_buscar_persona')
        ->middleware('auth.admin')
        ->name('resguardo.buscar_persona');
        
    Route::post('/resguardo_resumen', 'resguardo_resumen')
        ->middleware('auth.admin')
        ->name('resguardo.resumen');
        
    Route::post('/resguardo_crear', 'resguardo_crear')
        ->middleware('auth.admin')
        ->name('resguardo.crear');
    
    Route::get('/resguardo_editar/{id}', 'resguardo_editar')
        ->middleware('auth.admin')
        ->name('resguardo.editar');            
    
    Route::get('/resguardo_editar_linea/{linea}', 'resguardo_editar_linea')
        ->middleware('auth.admin')
        ->name('resguardo.editarlinea'); 
        
    Route::post('/resguardo_actualizar_linea', 'resguardo_actualizar_linea')
        ->middleware('auth.admin')
        ->name('resguardo.actualizarLinea'); 
        
    Route::get('/resguardo_buscar_articulo', 'resguardo_buscar_articulo')
        ->middleware('auth.admin')
        ->name('resguardo.buscar_articulo');
    
    Route::post('/asignado_articulo', 'asignado_articulo')
        ->middleware('auth.admin')
        ->name('resguardo.asignado_articulo');
        
    Route::post('/actualizar_asignado_articulo', 'actualizar_asignado_articulo')
        ->middleware('auth.admin')
        ->name('resguardo.actualizar_asignado_articulo');

    /****************************/
    /****************************/
    /******** Historial *********/  
    Route::get('/buscar_historial_articulo', 'buscar_historial_articulo')
        ->middleware('auth.admin')
        ->name('resguardo.buscar_historial_articulo');
        
    Route::post('/historial_articulo', 'historial_articulo')
        ->middleware('auth.admin')
        ->name('resguardo.historial_articulo');

    Route::get('/buscar_historial_persona', 'buscar_historial_persona')
        ->middleware('auth.admin')
        ->name('resguardo.buscar_historial_persona');
        
    Route::post('/historial_persona', 'historial_persona')
        ->middleware('auth.admin')
        ->name('resguardo.historial_persona');

        /****************************/
        /****************************/
        /*********** LDAP ***********/  
    Route::get('/server_ldap', 'server_ldap')
        ->middleware('auth.admin')
        ->name('admin.server_ldap');
        
    Route::post('/editar_ldap', 'editar_ldap')
        ->middleware('auth.admin')
        ->name('admin.editar_ldap');

    Route::get('/probar_ldap', 'probar_ldap')
        ->middleware('auth.admin')
        ->name('admin.probar_ldap');
});


/************************************ */
/************************************ */
//*********** Ruta Coadmin ***********//
/************************************ */
/************************************ */
Route::controller(CoadminController::class)->group(function(){

    Route::get('/coadmin_index','index_admin')
        ->middleware('auth.coadmin')
        ->name('coadmin.index');

    /**********************/
    /**********************/
    /****** Artículos *****/
    Route::get('/coadmin_articulos', 'articulo_index')
        ->middleware('auth.coadmin')
        ->name('articulos.coadmin_index');

    Route::get('/coadmin_articulo_nuevo', 'articulo_nuevo')
        ->middleware('auth.coadmin')
        ->name('articulos.coadmin_nuevo');

    Route::post('/coadmin_articulo_crear', 'articulo_crear')
        ->middleware('auth.coadmin')
        ->name('articulos.coadmin_crear');

    Route::get('/coadmin_editar_articulos/{id}','articulos_editar')
        ->middleware('auth.coadmin')
        ->name('articulos.coadmin_editar');

    Route::post('/coadmin_actualizar_articulos', 'articulos_actualizar')
        ->middleware('auth.coadmin')
        ->name('articulos.coadmin_actualizar');

    Route::get('/coadmin_inactivar_articulos/{id}', 'articulo_inactivar')
        ->middleware('auth.coadmin')
        ->name('articulos.coadmin_inactivar');

    /**********************/
    /**********************/
    /***** Resguardos *****/
    Route::get('/resguardo_coadmin_nuevo', 'resguardo_nuevo')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_nuevo');
        
    Route::get('/resguardo_coadmin_buscar_persona', 'resguardo_buscar_persona')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_buscar_persona');
            
    Route::post('/resguardo_coadmin_resumen', 'resguardo_resumen')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_resumen');

    Route::post('/resguardo_coadmin_crear', 'resguardo_crear')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_crear');

    Route::get('/resguardo_coadmin_editar/{id}', 'resguardo_editar')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_editar');

    Route::get('/resguardo_coadmin_editar_linea/{linea}', 'resguardo_editar_linea')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_editarlinea'); 

    Route::post('/resguardo_coadmin_actualizar_linea', 'resguardo_actualizar_linea')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_actualizarLinea');

    Route::get('/resguardo_coadmin_buscar_articulo', 'resguardo_buscar_articulo')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_buscar_articulo');

    Route::post('/asignado_coadmin_articulo', 'asignado_articulo')
        ->middleware('auth.coadmin')
        ->name('resguardo.asignado_coadmin_articulo');

    Route::post('/actualizar_coadmin_asignado_articulo', 'actualizar_asignado_articulo')
        ->middleware('auth.coadmin')
        ->name('resguardo.actualizar_coadmin_asignado_articulo');

    /****************************/
    /****************************/
    /******** Historial *********/  
    Route::get('/coadmin_buscar_historial_articulo', 'buscar_historial_articulo')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_buscar_historial_articulo');
        
    Route::post('/coadmin_historial_articulo', 'historial_articulo')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_historial_articulo');

    Route::get('/coadmin_buscar_historial_persona', 'buscar_historial_persona')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_buscar_historial_persona');
        
    Route::post('/coadmin_historial_persona', 'historial_persona')
        ->middleware('auth.coadmin')
        ->name('resguardo.coadmin_historial_persona');
});


/************************************ */
/************************************ */
//********** Ruta Receptor ***********//
/************************************ */
/************************************ */
Route::controller(ReceptorController::class)->group(function(){

    Route::get('/receptor_index','index_admin')
        ->middleware('auth.receptor')
        ->name('receptor.index');

    /**********************/
    /**********************/
    /***** Resguardos *****/
        
    Route::get('/resguardo_receptor_buscar_persona', 'resguardo_buscar_persona')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_buscar_persona');

    Route::get('/resguardo_receptor_editar/{id}', 'resguardo_editar')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_editar');

    Route::get('/resguardo_receptor_editar_linea/{linea}', 'resguardo_editar_linea')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_editarlinea'); 

    Route::post('/resguardo_receptor_actualizar_linea', 'resguardo_actualizar_linea')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_actualizarLinea');

    Route::get('/resguardo_receptor_buscar_articulo', 'resguardo_buscar_articulo')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_buscar_articulo');

    Route::post('/asignado_receptor_articulo', 'asignado_articulo')
        ->middleware('auth.receptor')
        ->name('resguardo.asignado_receptor_articulo');
    
    Route::post('/actualizar_receptor_asignado_articulo', 'actualizar_asignado_articulo')
        ->middleware('auth.receptor')
        ->name('resguardo.actualizar_receptor_asignado_articulo');

        Route::get('/resguardo_reportes', 'resguardo_reportes')
            ->middleware('auth.receptor')
            ->name('resguardo.receptor_reportes');

    /****************************/
    /****************************/
    /******** Historial *********/  
    Route::get('/receptor_buscar_historial_articulo', 'buscar_historial_articulo')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_buscar_historial_articulo');
        
    Route::post('/receptor_historial_articulo', 'historial_articulo')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_historial_articulo');

    Route::get('/receptor_buscar_historial_persona', 'buscar_historial_persona')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_buscar_historial_persona');
        
    Route::post('/receptor_historial_persona', 'historial_persona')
        ->middleware('auth.receptor')
        ->name('resguardo.receptor_historial_persona');

});