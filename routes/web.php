<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Ldap;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CoadminController;
use App\Http\Controllers\GraficasController;
use App\Http\Controllers\ProveedorController;

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
        
});


//Ruta Autorizador
Route::get('/auth_index', [PrincipalController::class, 'index_auth'])
    ->middleware('auth.auth')
    ->name('auth.index');

Route::get('/resetear_pass_auth/{id}', [UserController::class, 'password_auth'])
    ->middleware('auth.auth')
    ->name('usuarios.pass_auth');

//Termina ruta Authorizing

//Ruta Usuario
Route::get('/user_index', [PrincipalController::class, 'index_user'])
    ->middleware('auth.user')
    ->name('user.index');

Route::get('/resetear_pass_user/{id}', [UserController::class, 'password_user'])
    ->middleware('auth.user')
    ->name('usuarios.pass_user');
//Termina ruta Usuario

//Ruta Validador
Route::get('/validator_index', [PrincipalController::class, 'index_validator'])
    ->middleware('auth.validator')
    ->name('validator.index');

Route::get('/resetear_pass_val/{id}', [UserController::class, 'password_val'])
    ->middleware('auth.validator')
    ->name('usuarios.pass_val');
//Termina ruta Validador

//Ruta Revisor
Route::get('/revisor_index', [PrincipalController::class, 'index_revisor'])
    ->middleware('auth.revisor')
    ->name('revisor.index');

Route::get('/resetear_pass_rev/{id}', [UserController::class, 'password_rev'])
    ->middleware('auth.revisor')
    ->name('usuarios.pass_rev');
//Termina ruta Revisor
//Terminan las rutas del Index
/*********************************** */
/*********************************** */

/************************************ */
/************************************ */
//Comienzan las rutas del Usuario
//Ruta Administrador
//Usuarios


Route::post('/resetear_password', [UserController::class, 'reset_password'])
    ->middleware('auth.admin')
    ->name('usuarios.resetear');


// Proveedores
Route::get('/admin_proveedores', [ProveedorController::class, 'proveedor_index'])
    ->middleware('auth.admin')
    ->name('admin.proveedores');

Route::get('/proveedor_nuevo', [ProveedorController::class, 'proveedor_nuevo'])
    ->name('proveedores.nuevo');

Route::post('/proveedor_crear', [ProveedorController::class, 'proveedor_crear'])
    ->name('proveedores.crear');

Route::get('/editar_proveedores/{id}', [ProveedorController::class, 'proveedores_editar'])
    ->middleware('auth.admin')
    ->name('proveedores.editar');

Route::post('/actualizar_proveedores', [ProveedorController::class, 'proveedores_actualizar'])
    ->name('proveedores.actualizar');

Route::get('/inactivar_proveedores/{id}', [ProveedorController::class, 'proveedor_inactivar'])
    ->middleware('auth.admin')
    ->name('proveedores.inactivar');

// Articulos
//Termina ruta Administrador
/*********************************** */
/*********************************** */

/************************************ */
/************************************ */
//Comienzan las rutas de Operacion
//Ruta Administrador
//Pases de Salida

Route::get('/mostrar_salidas_autorizados/{id}', [RegisterController::class, 'salida_mostrar_autorizados'])
    ->middleware('auth.admin')
    ->name('salidas.mostrar_autorizados');

Route::get('/editar_salidas/{id}', [RegisterController::class, 'salida_editar'])
    ->middleware('auth.admin')
    ->name('salidas.editar');

Route::post('/actualizar_salidas', [RegisterController::class, 'salida_actualizar'])
    ->middleware('auth.admin')
    ->name('salidas.actualizar');

Route::get('/inactivar_salidas/{id}', [RegisterController::class, 'salida_inactivar'])
    ->middleware('auth.admin')
    ->name('salidas.inactivar');

Route::get('/autorizar_index', [RegisterController::class, 'autorizar_admin'])
    ->middleware('auth.admin')
    ->name('admin.autorizar');

Route::get('/autorizar_salida/{id}', [RegisterController::class, 'salida_autorizar'])
    ->middleware('auth.admin')
    ->name('salidas.autorizar');

Route::get('/articulo_borrar/{article_id}/{line_id}/{register_id}', [RegisterController::class, 'articulo_borrar'])
    ->middleware('auth.admin')
    ->name('admin.articulo_borrar');

Route::get('/validar_index', [RegisterController::class, 'validar_admin'])
    ->middleware('auth.admin')
    ->name('admin.validar');

Route::get('/validar_salida/{id}', [RegisterController::class, 'salida_validar'])
    ->middleware('auth.admin')
    ->name('salidas.validar');

Route::post('/validar_salida2', [RegisterController::class, 'salida_validar2'])
    ->middleware('auth.admin')
    ->name('salidas.validar2');

Route::get('/revisar_index', [RegisterController::class, 'revisar_admin'])
    ->middleware('auth.admin')
    ->name('admin.revisar');

Route::get('/revisar_salida/{id}', [RegisterController::class, 'salida_revisar'])
    ->middleware('auth.admin')
    ->name('salidas.revisar');

Route::post('/revisar_salida2', [RegisterController::class, 'salida_revisar2'])
    ->middleware('auth.admin')
    ->name('salidas.revisar2');

Route::get('/enviados_index', [RegisterController::class, 'enviados_admin'])
    ->middleware('auth.admin')
    ->name('admin.enviados');

Route::get('/recibir_salida/{id}', [RegisterController::class, 'salida_recibir'])
    ->middleware('auth.admin')
    ->name('salidas.recibir');

Route::get('/articulo_recibir/{article_id}/{line_id}/{register_id}', [RegisterController::class, 'articulo_recibir'])
    ->middleware('auth.admin')
    ->name('admin.articulo_recibir');

Route::post('/recibir_remision_salidas', [RegisterController::class, 'salida_recibir_remisión'])
    ->middleware('auth.admin')
    ->name('salidas.recibir_remision');

Route::get('/finalizar_salida/{id}', [RegisterController::class, 'salida_finalizar'])
    ->middleware('auth.admin')
    ->name('salidas.finalizar');

Route::get('/historial_index', [RegisterController::class, 'historial_admin'])
    ->middleware('auth.admin')
    ->name('admin.historial');

//Termina ruta Administrador
//Comienza Ruta Autorizador
Route::get('/pase_salida_auth', [RegisterController::class, 'register_auth'])
    ->middleware('auth.auth')
    ->name('registros.auth');

Route::post('/resetear_password_auth', [UserController::class, 'reset_password_auth'])
    ->middleware('auth.auth')
    ->name('usuarios.resetear_auth');

Route::get('/salida_nueva_auth', [RegisterController::class, 'salida_nueva_auth'])
    ->middleware('auth.auth')
    ->name('salidas.nueva.auth');

Route::post('/salida_crear_auth', [RegisterController::class, 'salida_crear_auth'])
    ->middleware('auth.auth')
    ->name('salidas.crear.auth');

Route::get('/editar_salidas_auth/{id}', [RegisterController::class, 'salida_editar_auth'])
    ->middleware('auth.auth')
    ->name('salidas.editar.auth');

Route::post('/actualizar_salidas_auth', [RegisterController::class, 'salida_actualizar_auth'])
    ->middleware('auth.auth')
    ->name('salidas.actualizar.auth');
    
Route::get('/articulo_borrar_auth/{article_id}/{line_id}/{register_id}', [RegisterController::class, 'auth_articulo_borrar'])
    ->middleware('auth.auth')
    ->name('auth.articulo_borrar');

Route::get('/inactivar_salidas_auth/{id}', [RegisterController::class, 'salida_inactivar_auth'])
    ->middleware('auth.auth')
    ->name('salidas.inactivar.auth');

Route::get('/mostrar_salidas_autorizados_auth/{id}', [RegisterController::class, 'salida_mostrar_autorizados_auth'])
    ->middleware('auth.auth')
    ->name('salidas.mostrar_autorizados_auth');

Route::get('/autorizar_auth', [RegisterController::class, 'autorizar_auth'])
    ->middleware('auth.auth')
    ->name('auth.autorizar'); 
    
Route::get('/autorizar_salida_auth/{id}', [RegisterController::class, 'salida_autorizar_auth'])
    ->middleware('auth.auth')
    ->name('salidas.autorizar.auth'); 

Route::get('/historial_auth', [RegisterController::class, 'historial_auth'])
    ->middleware('auth.auth')
    ->name('auth.historial');
//Termina ruta del Autorizador

//Rutas de Usuario
Route::get('/pase_salida_user', [RegisterController::class, 'register_user'])
    ->middleware('auth.user')
    ->name('registros.user');

Route::post('/resetear_password_user', [UserController::class, 'reset_password_user'])
    ->middleware('auth.user')
    ->name('usuarios.resetear_user');
    
Route::get('/salida_nueva_user', [RegisterController::class, 'salida_nueva_user'])
    ->middleware('auth.user')
    ->name('salidas.nueva.user');

Route::post('/salida_crear_user', [RegisterController::class, 'salida_crear_user'])
    ->middleware('auth.user')
    ->name('salidas.crear.user');

Route::get('/editar_salidas_user/{id}', [RegisterController::class, 'salida_editar_user'])
    ->middleware('auth.user')
    ->name('salidas.editar.user');

Route::post('/actualizar_salidas_user', [RegisterController::class, 'salida_actualizar_user'])
    ->middleware('auth.user')
    ->name('salidas.actualizar.user');
    
Route::get('/articulo_borrar_user/{article_id}/{line_id}/{register_id}', [RegisterController::class, 'user_articulo_borrar'])
    ->middleware('auth.user')
    ->name('user.articulo_borrar');

Route::get('/inactivar_salidas_user/{id}', [RegisterController::class, 'salida_inactivar_user'])
    ->middleware('auth.user')
    ->name('salidas.inactivar.user');

Route::get('/historial_user', [RegisterController::class, 'historial_user'])
    ->middleware('auth.user')
    ->name('user.historial');
//Terminan Rutas de Usuario

//Rutas del Validador
Route::get('/pase_salida_val', [RegisterController::class, 'register_val'])
    ->middleware('auth.validator')
    ->name('registros.val');

Route::post('/resetear_password_val', [UserController::class, 'reset_password_val'])
    ->middleware('auth.validator')
    ->name('usuarios.resetear_val');

Route::get('/mostrar_salidas_autorizados_val/{id}', [RegisterController::class, 'salida_mostrar_autorizados_val'])
    ->middleware('auth.validator')
    ->name('salidas.mostrar_autorizados_val');

Route::post('/revisar_salidas_val', [RegisterController::class, 'salida_revisar_val'])
    ->middleware('auth.validator')
    ->name('salidas.revisar.val');

Route::get('/inactivar_salidas_val/{id}', [RegisterController::class, 'salida_inactivar_val'])
    ->middleware('auth.validator')
    ->name('salidas.inactivar.val');
//Terminan Rutas del Validador

//Rutas del Revisor
Route::get('/pase_salida_rev', [RegisterController::class, 'register_rev'])
    ->middleware('auth.revisor')
    ->name('registros.rev');

Route::post('/resetear_password_rev', [UserController::class, 'reset_password_rev'])
    ->middleware('auth.revisor')
    ->name('usuarios.resetear_rev');

Route::get('/mostrar_salidas_autorizados_rev/{id}', [RegisterController::class, 'salida_mostrar_autorizados_rev'])
    ->middleware('auth.revisor')
    ->name('salidas.mostrar_autorizados_rev');

Route::post('/revisar_salidas_rev', [RegisterController::class, 'salida_revisar_rev'])
    ->middleware('auth.revisor')
    ->name('salidas.revisar.rev');

Route::get('/inactivar_salidas_rev/{id}', [RegisterController::class, 'salida_inactivar_rev'])
    ->middleware('auth.revisor')
    ->name('salidas.inactivar.rev');
//Terminan Rutas del Revisor
//Terminan las rutas de Operacion
/*********************************** */
/*********************************** */
Route::get('/ldap_search', [Ldap::class, 'ldap_search'])
    ->name('ldap.search');

Route::get('/ldap', [Ldap::class, 'ldap'])
    ->middleware('auth.admin')
    ->name('ldap');

Route::get('/ldap_login', [LoginController::class, 'ldap_login'])
        ->middleware('auth.admin')
        ->name('ldap.login');


/*********************************** */
// Gráficas
/*********************************** */
Route::get('/graficas_articulos', [GraficasController::class, 'graficas_articulos'])
    ->middleware('auth.admin')
    ->name('graficas.articulos');

Route::get('/graficas_categorias', [GraficasController::class, 'graficas_categorias'])
    ->middleware('auth.admin')
    ->name('graficas.categorias');

Route::get('/escoger_articulo', [GraficasController::class, 'graficas_escogerArticulo'])
    ->middleware('auth.admin')
    ->name('graficas.escoger_articulos');

Route::post('/graficas_reparaciones_articulos', [GraficasController::class, 'graficas_reparaciones'])
    ->middleware('auth.admin')
    ->name('graficas.reparaciones_articulos');

/*********************************** */
// Tablas
/*********************************** */
Route::get('/tablas_articulos', [GraficasController::class, 'tablas_articulos'])
    ->middleware('auth.admin')
    ->name('tablas.articulos');

Route::get('/tablas_categorias', [GraficasController::class, 'tablas_categorias'])
    ->middleware('auth.admin')
    ->name('tablas.categorias');

    Route::get('/tablas_escoger_articulo', [GraficasController::class, 'tablas_escogerArticulo'])
        ->middleware('auth.admin')
        ->name('tablas.escoger_articulos');
    
    Route::post('/tablas_reparaciones_articulos', [GraficasController::class, 'tablas_reparaciones'])
        ->middleware('auth.admin')
        ->name('tablas.reparaciones_articulos');



