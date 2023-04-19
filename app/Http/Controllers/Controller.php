<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;


class ExcepcionesGlobales{
    const ERROR_0 = "No se recibieron todos los parámetros requeridos";
    const ERROR_1 = "Excepción al ejecutar";
    const ERROR_2 = "Elemento no encontrado";
    const ERROR_300 = "Usuario no tiene permisos para realizar esta operación";
}

class EstadosGlobales
{
    const ERROR_0 =  0; //"No se recibieron todos los parámetros requeridos";
    const ERROR_1 = -1; //"Excepción al ejecutar";
    const ERROR_2 = -2; //"Elemento no encontrado";
    const ERROR_300 = -300; //"Usuario no tiene permisos para realizar esta operación";
}

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
