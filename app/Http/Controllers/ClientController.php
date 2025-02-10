<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      title="Clients API",
 *      version="1.0",
 *      description="Documentación de la API de Clientes con Swagger"
 * )
 */
class ClientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clients",
     *     summary="Obtener todos los clientes",
     *     tags={"Clientes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Client::all(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/clients/{id}",
     *     summary="Obtener un cliente por ID",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del cliente",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del cliente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $client = Client::find($id);
        if (!$client) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        return response()->json($client, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/clients",
     *     summary="Crear un nuevo cliente",
     *     tags={"Clientes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "phone_number", "email", "address"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="phone_number", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="address", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cliente creado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:clients,email',
            'address' => 'required|string|max:255',
        ]);

        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/clients/{id}",
     *     summary="Actualizar un cliente",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del cliente a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="phone_number", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="address", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente actualizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        if (!$client) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $client->update($request->all());
        return response()->json($client, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     summary="Eliminar un cliente",
     *     tags={"Clientes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del cliente a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        if (!$client) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $client->delete();
        return response()->json(['message' => 'Cliente eliminado'], 200);
    }
}
