<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Validator, Auth;
use MathPHP\Arithmetic;
use MathPHP\NumberTheory\Integer;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pharmacies = Pharmacy::all();

        if ($pharmacies) {
            $respuesta = APIHelpers::createAPIResponse(false, 200, 'Farmacias encontradas con éxito', $pharmacies);
            return response()->json($respuesta, 200);
        } else {
            $respuesta = APIHelpers::createAPIResponse(true, 500, 'No se encontraron farmacias', $pharmacies);
            return response()->json($respuesta, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request
     */
    public function create(Request $request)
    {
        $rules = [
            'nombre' => 'required',
            'direccion' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        $messages = [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La dirección es requerida',
            'lat.required' => 'La latitud es requerida',
            'lng.required' => 'La longitud es requerida',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $respuesta = APIHelpers::createAPIResponse(true, 400, 'Se ha producido un error', $validator->errors());
            return response()->json($respuesta, 200);
        }

        $pharmacy = new Pharmacy();

        $pharmacy->name = $request->nombre;
        $pharmacy->address = $request->direccion;
        $pharmacy->lat = $request->lat;
        $pharmacy->lng = $request->lng;

        if ($pharmacy->save()) {
            $respuesta = APIHelpers::createAPIResponse(false, 200, 'Farmacia creada con éxito', $validator->errors());
            return response()->json($respuesta, 200);
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pharmacy = Pharmacy::find($id);

        if ($pharmacy) {
            $respuesta = APIHelpers::createAPIResponse(false, 200, 'Farmacia encontrada con éxito', $pharmacy);
            return response()->json($respuesta, 200);
        } else {
            $respuesta = APIHelpers::createAPIResponse(true, 500, 'No se encontró la farmacia', $pharmacy);
            return response()->json($respuesta, 200);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function edit(Pharmacy $pharmacy)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, Pharmacy $pharmacy)
    {
        $rules = [
            'nombre' => 'required',
            'direccion' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        $messages = [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La dirección es requerida',
            'lat.required' => 'La latitud es requerida',
            'lng.required' => 'La longitud es requerida',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $respuesta = APIHelpers::createAPIResponse(true, 400, 'Se ha producido un error', $validator->errors());
            return response()->json($respuesta, 200);
        }

        $pharmacy = Pharmacy::find($id);

        if ($pharmacy) {
            $pharmacy->name = $request->nombre;
            $pharmacy->address = $request->direccion;
            $pharmacy->lat = $request->lat;
            $pharmacy->lng = $request->lng;

            if ($pharmacy->save()) {
                $respuesta = APIHelpers::createAPIResponse(false, 200, 'Farmacia modificada con éxito', $pharmacy);
            }
        } else {
            $respuesta = APIHelpers::createAPIResponse(false, 200, 'No se encontró la farmacia', $pharmacy);
        }

        return $respuesta;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pharmacy = Pharmacy::destroy($id);
        
        $respuesta = APIHelpers::createAPIResponse(false, 200, 'Farmacia eliminada con exito', $pharmacy);

        return response()->json($respuesta, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function farmaciasCercanas(Request $request)
    {
        $rules = [
            'lat' => 'required',
            'lng' => 'required',
            'cantMetros' => 'required'
        ];

        $messages = [
            'lat.required' => 'La latitud es requerida',
            'lng.required' => 'La longitud es requerida',
            'cantMetros.required' => 'La cantidad de metros es requerida',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $respuesta = APIHelpers::createAPIResponse(true, 400, 'Se ha producido un error', $validator->errors());
            return response()->json($respuesta, 200);
        }

        $pharmacies = Pharmacy::all();

        $closePharmacies = collect();
        $mtsPharmacies = [];

        if ($pharmacies) {
            foreach ($pharmacies as $pharmacy) {
                $R = 6378.137; //Radio de la tierra en km 
                $radLat = $pharmacy->lat - $request->lat;
                $dLat = $radLat*M_PI/180;

                $radLng = $pharmacy->lng - $request->lng;
                $dLong = $radLng*M_PI/180;

                $a = sin($dLat/2) * sin($dLat/2) + cos($request->lat*M_PI/180) * cos($pharmacy->lat*M_PI/180) * sin($dLong/2) * sin($dLong/2);
                $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                

                //aquí obtienes la distancia en metros por la conversion 1Km =1000m
                $mts = $R * $c * 1000; 
                
                if($mts < $request->cantMetros){
                    $list = [
                        'id' => $pharmacy->id,
                        'name' => $pharmacy->name,
                        'address' => $pharmacy->address,
                        'lat' => $pharmacy->lat,
                        'lng' => $pharmacy->lng,
                        'distance' => $mts,
                    ];

                    $closePharmacies->push($list);
                }
                
                array_push($mtsPharmacies, $mts);
            }

            if (count($closePharmacies) > 0) {
                $respuesta = APIHelpers::createAPIResponse(false, 200, 'Se encontraron farmacias', $closePharmacies);
                return response()->json($respuesta, 200);
            } else {
                // guardo cual es la distancia minima a la que se encuentra una farmacia en el caso de que no haya una dentro de los metros indicados por el usuario
                $minMts = min($mtsPharmacies);
                $minMtsRound = round($minMts);

                $message = "No hay farmacias dentro de los metros indicados. La farmacia más cercana se encuentra a $minMtsRound metros";

                $respuesta = APIHelpers::createAPIResponse(true, 301, $message, $minMts);
                return response()->json($respuesta, 200);
            }

            

        } else {
            $respuesta = APIHelpers::createAPIResponse(true, 400, 'No hay farmacias', $pharmacies);
            return response()->json($respuesta, 200);
        }
        

    }

    function rad($x) {
        return $x*M_PI/180;
    }
}
