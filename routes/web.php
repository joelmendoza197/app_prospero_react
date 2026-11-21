<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    try {
        Mail::raw('Hola Próspero, esto es una prueba de conexión SMTP.', function ($message) {
            $message->to('joel-777@hotmail.es')->subject('Prueba de Conexión');
        });
        return "Correo enviado con éxito.";
    } catch (\Exception $e) {
        return "Error al enviar: " . $e->getMessage();
    }
});
