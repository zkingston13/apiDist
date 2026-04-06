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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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




           if ($request->hasFile('img')) {
            $archivo = $request->file('img');
            $nombreArchivo = 'producto_' . $producto->id_producto . '_img_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = 'articulos/' . $nombreArchivo;
            
            // Leer el contenido del archivo
            $contenido = file_get_contents($archivo->getRealPath());
            
            // Subir a S3
            $subido = Storage::disk('s3')->put($ruta, $contenido, 'public');
            
            if ($subido) {
                $producto->img = Storage::disk('s3')->url($ruta);
                $producto->save();
                
                // Opcional: Verificar que se guardó
                \Log::info('Imagen subida a S3: ' . $producto->img);
            } else {
                \Log::error('Error al subir imagen a S3');
            }
        }

         if ($request->hasFile('img1')) {
            $archivo = $request->file('img1');
            $nombreArchivo = 'producto_' . $producto->id_producto . '_img1_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = 'articulos/' . $nombreArchivo;
            
            // Leer el contenido del archivo
            $contenido = file_get_contents($archivo->getRealPath());
            
            // Subir a S3
            $subido = Storage::disk('s3')->put($ruta, $contenido, 'public');
            
            if ($subido) {
                $producto->img1 = Storage::disk('s3')->url($ruta);
                $producto->save();
                
                // Opcional: Verificar que se guardó
                \Log::info('Imagen subida a S3: ' . $producto->img1);
            } else {
                \Log::error('Error al subir imagen a S3');
            }
        }

         if ($request->hasFile('img2')) {
            $archivo = $request->file('img2');
            $nombreArchivo = 'producto_' . $producto->id_producto . '_img2_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = 'articulos/' . $nombreArchivo;
            
            // Leer el contenido del archivo
            $contenido = file_get_contents($archivo->getRealPath());
            
            // Subir a S3
            $subido = Storage::disk('s3')->put($ruta, $contenido, 'public');
            
            if ($subido) {
                $producto->img2 = Storage::disk('s3')->url($ruta);
                $producto->save();
                
                // Opcional: Verificar que se guardó
                \Log::info('Imagen subida a S3: ' . $producto->img2);
            } else {
                \Log::error('Error al subir imagen a S3');
            }
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


    // Función para eliminar imagen vieja de S3
        $eliminarImagenS3 = function($url) {
            if ($url && !str_contains($url, 'producto_default.jpg')) {
                // Extraer la ruta del archivo de la URL
                $path = parse_url($url, PHP_URL_PATH);
                $path = ltrim($path, '/');
                
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                    \Log::info('Imagen eliminada de S3: ' . $path);
                }
            }
        };
        
        // Función para subir nueva imagen a S3
        $subirImagenS3 = function($file, $productoId, $tipo) {
            $extension = $file->getClientOriginalExtension();
            $nombreArchivo = 'producto_' . $productoId . '_' . $tipo . '_' . time() . '_' . uniqid() . '.' . $extension;
            $ruta = 'articulos/' . $nombreArchivo;
            
            $contenido = file_get_contents($file->getRealPath());
            $subido = Storage::disk('s3')->put($ruta, $contenido, 'public');
            
            if ($subido) {
                return Storage::disk('s3')->url($ruta);
            }
            
            return null;
        };
        
        // Actualizar imagen principal
        if ($request->hasFile('img')) {
            // Eliminar imagen vieja
            $eliminarImagenS3($producto->img);
            
            // Subir nueva imagen
            $nuevaUrl = $subirImagenS3($request->file('img'), $producto->id_producto, 'img');
            if ($nuevaUrl) {
                $producto->img = $nuevaUrl;
            }
        }
        
        // Actualizar imagen 1
        if ($request->hasFile('img1')) {
            $eliminarImagenS3($producto->img1);
            
            $nuevaUrl = $subirImagenS3($request->file('img1'), $producto->id_producto, 'img1');
            if ($nuevaUrl) {
                $producto->img1 = $nuevaUrl;
            }
        }
        
        // Actualizar imagen 2
        if ($request->hasFile('img2')) {
            $eliminarImagenS3($producto->img2);
            
            $nuevaUrl = $subirImagenS3($request->file('img2'), $producto->id_producto, 'img2');
            if ($nuevaUrl) {
                $producto->img2 = $nuevaUrl;
            }
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

         $deleteImageFromS3 = function($url) {
            if ($url && !str_contains($url, 'producto_default.jpg')) {
                $path = parse_url($url, PHP_URL_PATH);
                $path = ltrim($path, '/');

                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
            }
        };

        $deleteImageFromS3($producto->img);
        $deleteImageFromS3($producto->img1);
        $deleteImageFromS3($producto->img2);

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