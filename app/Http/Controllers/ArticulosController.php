<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use App\Models\Compra;
use Illminate\Support\Facades\Storage;

    class ArticulosController extends Controller{
    public function index(Request $request){

    $query = Productos::with('categoria');

    if ($request->has('categoria') && !empty($request->categoria)) {
        $query->where('categoria_id', $request->categoria);
    }

    if ($request->has('estado') && !empty($request->estado)) {
        switch ($request->estado) {
            case 'stock':
                $query->where('existencia', '>', 10);
                break;
            case 'low':
                $query->whereBetween('existencia', [1, 10]);
                break;
            case 'out':
                $query->where('existencia', 0);
                break;
        }
    }

    // 👇 SI PIDEN TODOS
    if($request->has('all')){
        $productos = $query->get();
    }else{
        $productos = $query->paginate(10);
    }

    $categorias = Categoria::all();
    $proveedores = Proveedor::all();

    return response()->json([
        'resultado'=>true,
        'productos'=>$productos,
        'categorias'=>$categorias,
        'proveedores'=>$proveedores
    ],200);
}

   public function store(Request $request){

    $validator = Validator::make($request->all(), [
        'nombre_producto' => 'required|string|max:50',
        'precio' => 'required|numeric|min:0',
        'existencia' => 'required|integer|min:1', // Cambiado a min:1 para compra inicial
        'categoria_id' => 'required|exists:categoria,id_categoria',
        'id_proveedor' => 'required|exists:proveedor,id_proveedor', // Agregar validación
    ]);

    if ($validator->fails()) {
       return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        $producto = Productos::create([
            'nombre_producto' => $request->nombre_producto,
            'precio' => $request->precio,
            'existencia' => $request->existencia,
            'categoria_id' => $request->categoria_id,
            'activo' => 1,
            'img' => '/storage/producto/producto_default.jpg',
            'img1' => '/storage/producto/producto_default.jpg',
            'img2' => '/storage/producto/producto_default.jpg'
        ]);

        
        if($request->hasFile('img')){
            $img = $request->file('img');
            $route = Storage::disk('s3')->put('articulos',$img);
            $url = Storage::disk('s3')->url($route);
            $producto->img = $url;
            $producto->save();
        }

        if($request->hasFile('img1')){
            $img = $request->file('img1');
            
           
            $route = Storage::disk('s3')->put('articulos',$img);

            $url = Storage::disk('s3')->url($route);
            $producto->img1 = $url;
            $producto->save();
        }

        if($request->hasFile('img2')){
            $img = $request->file('img2');
            
            $nombre = 'productos_' . $producto->id_producto . '_img3.' . $img->extension();
           
            $route = Storage::disk('s3')->put('articulos',$img);

            $url = Storage::disk('s3')->url($route);
            $producto->img2 = $url;
            $producto->save();
        }
        
        
        $compra = Compra::create([
            'id_proveedor' => $request->id_proveedor,
            'fecha' => now()
        ]);

        $detalle_compra = DetalleCompra::create([
            'id_compra' => $compra->id_compra,
            'id_producto' => $producto->id_producto,
            'cantidad' => $request->existencia, // Usar existencia como cantidad inicial
            'precio' => $request->precio,
            'total' => $request->existencia * $request->precio
        ]);

        DB::commit();

         return response()->json([
            'success' => true,
            'message' => 'Producto registrado correctamente',
            'data' => [
                'producto' => $producto,
                'compra' => $compra,
                'detalle_compra' => $detalle_compra
            ]
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
           return response()->json([
            'success' => false,
            'message' => 'Error al registrar producto',
            'error'   => $e->getMessage()
        ], 422);
    }
    }

    public function show($id){
         
        $validator = Validator::make(
              ['id_producto'=>$id],
              ['id_producto'=>'required|integer|min:1|exists:productos,id_producto']
        );

        if($validator->fails()){
               return response()->json(['resultado'=>false, 'datos' =>null,'errors'=>$validator->errors()], 422);
        }

        $productos = Productos::find($id);
        $categorias = Categoria::all();
        $proveedores = Proveedor::all();
        
        if (!$productos) {
              return response()->json(['resultado'=>true, 'datos' =>$productos,$categorias,$proveedores], 404);
        }

          return response()->json(['resultado'=>true, 'datos' =>$productos,$categorias,$proveedores], 200);
    }
    

   public function update(Request $request,$id_producto){

    $producto = Productos::find($id_producto);

    if (!$producto) {
        return response()->json([
            'resultado'=>false,
            'datos'=> null,
            'errors' => 'Producto no encontrado'
        ],422);
    }

    $validator = Validator::make($request->all(), [
        'nombre_producto' => 'sometimes|string|max:50',
        'precio' => 'sometimes|numeric|min:0',
        'existencia' => 'sometimes|integer|min:0',
        'categoria_id' => 'sometimes|exists:categoria,id_categoria',
        'img' => 'nullable|image|max:2048',
        'img1' => 'nullable|image|max:2048',
        'img2' => 'nullable|image|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'resultado'=>false,
            'datos'=> null,
            'errors' => $validator->errors()
        ],400);
    }

    $ruta = 'imagenes/productos';

    if($request->hasFile('img')){
        $img = $request->file('img');
        $nombre = 'productos_'.$producto->id_producto.'_img1.'.$img->extension();
        $img->storeAs($ruta,$nombre,'public');
        $producto->img = '/storage/'.$ruta.'/'.$nombre;
    }

    if($request->hasFile('img1')){
        $img1 = $request->file('img1');
        $nombre = 'productos_'.$producto->id_producto.'_img2.'.$img1->extension();
        $img1->storeAs($ruta,$nombre,'public');
        $producto->img1 = '/storage/'.$ruta.'/'.$nombre;
    }

    if($request->hasFile('img2')){
        $img2 = $request->file('img2');
        $nombre = 'productos_'.$producto->id_producto.'_img3.'.$img2->extension();
        $img2->storeAs($ruta,$nombre,'public');
        $producto->img2 = '/storage/'.$ruta.'/'.$nombre;
    }

    $producto->nombre_producto = $request->nombre_producto ?? $producto->nombre_producto;
    $producto->precio = $request->precio ?? $producto->precio;
    $producto->existencia = $request->existencia ?? $producto->existencia;
    $producto->categoria_id = $request->categoria_id ?? $producto->categoria_id;
    $producto->activo = $request->has('activo') ? 1 : 0;

    $producto->save();

    return response()->json([
        'resultado'=>true,
        'datos'=>$producto
    ],200);
}

    public function destroy($id_producto){
         $validator = Validator::make(
              ['id_producto'=>$id_producto],
              ['id_producto'=>'required|integer|min:1|exists:productos,id_producto']
        );
 if ($validator->fails()) {
          return response()->json(['resultado'=>false, 'datos' => null,'errors' => $validator->errors()
    ],422);
 }
        $producto = Productos::find($id_producto);
        if (!$producto) {
          return response()->json(['resultado'=>false, 'datos' => null,'errors' => $validator->errors()
    ],400);

        }
      
        $producto->update([
            'activo' => 0
            ]);
         return response()->json(['resultado'=>true, 'datos'=>$producto,'message'=> 'Producto deshabilitado '],200);
    }

    public function porCategoria($categoriaId){
        $productos_categorias = Productos::with('categoria')
            ->where('categoria_id', $categoriaId)
            ->where('activo', 1)
            ->get();

        return view('articulos',compact('productos_categorias'));
    }

    public function activar($id){
         $producto = Productos::find($id);
        if (!$producto) {
            return back()->with('error', 'Error producto no encontrado');

        }
        // Cambiar estado a inactivo
        $producto->activo = 1;
        $producto->save();

        $producto->update(['activo' => 0]);
        return redirect()->back()->with('success', 'Producto activado');
    }

}