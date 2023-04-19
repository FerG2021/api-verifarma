<?php

namespace App\Http\Clases;

use Illuminate\Http\Request;

/**
 * Clase que permite a los controladores devolver una respuesta estandar
 * en cualquier consulta
 */
class Respuesta
{
    private $pagTotal;
    private $pagActual;
    private $pagTotalItems;

    private $estado;
    private $mensaje;
    private $excepcion;
    private $datos;

    public function __construct(
        $estado,
        $mensaje,
        $excepcion,
        $datos,
        $pagTotal = 0,
        $pagActual = 0,
        $pagTotalItems = 0
    ) {
        $this->estado = $estado;

        if ($mensaje != null) {
            $this->mensaje = $mensaje;
        } else {
            if ($estado > 0) {
                $this->mensaje = "Ok";
            } else {
                $this->mensaje = "Error";
            }
        }

        $this->excepcion = $excepcion;
        $this->pagTotal = $pagTotal;
        $this->pagActual = $pagActual;
        $this->pagTotalItems = $pagTotalItems;
        $this->datos = $datos;
    }

    // Necesario para poder retornar una respuesta
    // si no se implementa esta funcion, lumen hace saltar una excepcion al
    // retornar Respuesta en el controlador
    public function __toString()
    {
        return json_encode([
            'estado' => $this->estado,
            'mensaje' => $this->mensaje,
            'excepcion' => $this->excepcion,
            'datos' => $this->estado > 0 ? [
                'pagTotal' => $this->pagTotal,
                'pagActual' => $this->pagActual,
                'pagTotalItems' => $this->pagTotalItems,
                'datos' => $this->datos
            ] : null
        ]);
    }
}
