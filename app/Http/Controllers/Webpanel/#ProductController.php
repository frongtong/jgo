<?php

namespace App\Http\Controllers\Webpanel;

use App\Models\Backend\Product_link;
use App\Models\Backend\Text_product;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Webpanel\LogsController;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use App\Models\Backend\Product;
use App\Models\Backend\Product_image;

use App\Models\Backend\Standardinproduct;
use App\Models\Backend\Standardproduct;
use App\Models\Backend\Brand;
use App\Models\Backend\Category1;
use App\Models\Backend\Sale_product;
// use App\Models\product;

use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'product';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 10);
        $query = new product;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {
        $items = Product::query()->with('brand')->with('category1')->orderBy('id', 'desc')->paginate(15);
        $items->pages = new Product();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "สินค้า", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }

    public function add(Request $request)
    {
        $items = Product::query()->with('brand')->with('category1')->paginate(10);
        $items->pages = new Product();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $brands = Brand::all();
        $standardproducts = Standardproduct::all();
        $categorys1 = Category1::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "สินค้า", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'standardproducts' => $standardproducts,
            'categorys1' => $categorys1,
            'brands' => $brands,
            'items' => $items
        ]);
    }

    public function edit(Request $request, $id)
    {
        $product = Product::with('product_image')->with('category1')->with('category2')->with('category3')->with('brand')->with('product_link')->with('text_product')->with('standardinproduct.standardproduct')->find($id);
        $sale_product = Sale_product::where('product_id', $id)->with('Bigproduct.brand')->get();
        $items = Product::query()->with('brand')->with('category1')->paginate(10);
        $items->pages = new Product();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $brands = Brand::all();
        $standardproducts = Standardproduct::all();
        $categorys1 = Category1::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เกี่ยวกับเรา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'standardproducts' => $standardproducts,
            'categorys1' => $categorys1,
            'brands' => $brands,
            'items' => $items,
            'product' => $product,
            'id' => $id,
            'sale_product' => $sale_product
        ]);
    }
    public function destroy(Request $request)
    {
        if ($request->id == 1) {
            return response()->json(false);
        } else {
            $datas = product::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = product::destroy($data->id);
                }
            }

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }
    //==== Function Insert Update Delete Status Sort & Others ====
    public function insert(Request $request, $id = null)
    {
        return $this->store($request, $id = null);
    }
    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }
    public function store($request, $id = null)
    {
        try {
            DB::beginTransaction();
            if ($id == null) {
                $data = new product();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
                $data->status = 0;
            } else {
                $data = product::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name_th = $request->name_th;
            $data->name_en = $request->name_en;
            $data->description_th = $request->description_th;
            $data->description_en = $request->description_en;
            $data->weight_th = $request->weight_th;
            $data->weight_en = $request->weight_en;
            $data->ingredient_th = $request->ingredient_th;
            $data->ingredient_en = $request->ingredient_en;
            $data->category1_id = $request->category1_id;
            $data->category2_id = $request->category2_id;
            $data->category3_id = $request->category3_id;
            $data->quantity_th = $request->quantity_th;
            $data->quantity_en = $request->quantity_en;
            $data->treatment_th = $request->treatment_th;
            $data->treatment_en = $request->treatment_en;
            $data->brand_id = $request->brand_id;
            $data->number_OY = $request->number_OY;
            $data->color = $request->color;
            $path = 'upload/product';
            $data->save();
            
            if ($request->file("path")) {
                $files = $request->file("path");
                $ids = $request->id_path; // Retrieve the id_path array from the request
            
                foreach ($files as $index => $file) {
                    $fileId = $ids[$index] ?? null; // Get the corresponding id_path or null if not present
            
                    $imgName = $path . '/product-img-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path($path), $imgName);
            
                    if ($fileId) {
                        // Update existing record
                        $Product_image = Product_image::find($fileId);
                        if ($Product_image) {
                            $Product_image->image = $imgName;
                            $Product_image->save();
                        }
                    } else {
                        // Create a new record
                        $Product_image = new Product_image();
                        $Product_image->created_at = date('Y-m-d H:i:s');
                        $Product_image->updated_at = date('Y-m-d H:i:s');
                        $Product_image->image = $imgName;
                        $Product_image->product_id = $data->id;
                        $Product_image->save();
                    }
                }
            }
            if ($request->header_th) {
                if($id != null){
                    Text_product::where('product_id', $id)->delete();
                }
                foreach ($request->header_th as $index => $header) {

                    $text = new Text_product();
                    $text->header_th = $header;
                    $text->product_id = $data->id;
                    $text->header_en = $request->header_en[$index];
                    $text->description_th = $request->adddescription_th[$index];
                    $text->description_en = $request->adddescription_en[$index];
                    $text->save();

                }
            }
            // Handle file uploads
            if($request->id_link){
                foreach( $request->id_link as $index => $id_link ) {
                    $Product_link = Product_link::find($id_link);
                    if ($Product_link) {

                        $Product_link->url = $request->urls[$index];
                        $Product_link->save();
                    }
                }
            }
            if ($request->urls && $request->hasFile('images')) {
                $files = $request->file("images");
                $ids = $request->id_link; // Retrieve the id_link array from the request

                foreach ($files as $index => $file) {
                    $fileId = $ids[$index] ?? null; // Get the corresponding id_link or null if not present
            
                    $imgName = $path . '/link-img-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path($path), $imgName);

                    if ($fileId) {
                        // Update existing record
                        $Product_link = Product_link::find($fileId);
                        if ($Product_link) {
                            $Product_link->url = $request->urls[$index];
                            $Product_link->image = $imgName;
                            $Product_link->save();
                        }
                    } else {
                        // Create a new record
                        $Product_link = new Product_link();
                        $Product_link->created_at = date('Y-m-d H:i:s');
                        $Product_link->updated_at = date('Y-m-d H:i:s');
                        $Product_link->url = $request->urls[$index];
                        $Product_link->product_id = $data->id;
                        $Product_link->image = $imgName;
                        $Product_link->save();
                    }
                }
            }
            if ($request->productstandard) {
                if($id != null){
                    Standardinproduct::where('product_id', $id)->delete();
                }
                foreach ($request->productstandard as $index => $standard) {

                    $data2 = new Standardinproduct();
                    $data2->created_at = date('Y-m-d H:i:s');
                    $data2->updated_at = date('Y-m-d H:i:s');
                    $data2->standardproduct_id = $standard;
                    $data2->product_id = $data->id;
                    $data2->save();
                }
            }
            if ($request->selected_ids) {
                if($id != null){
                    Sale_product::where('product_id', $id)->delete();
                }
                foreach ($request->selected_ids as $index => $ids) {

                    $Sale = new Sale_product();
                    $Sale->created_at = date('Y-m-d H:i:s');
                    $Sale->updated_at = date('Y-m-d H:i:s');
                    $Sale->big_product_id = $ids;
                    $Sale->product_id = $data->id;
                    $Sale->save();
                }
            }
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder")]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            LogsController::logInsert($error_line, $error_url, $error_log, $type_log);
            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "ไม่สามารถทำรายการได้",
                'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
                'icon' => 'error'
            ]);
        }
    }
    public function searchProducts(Request $request)
    {
        $query = $request->input('query');

        // Fetch products from the database based on the search query
        $products = Product::where('name_th', 'LIKE', '%' . $query . '%')
            ->orWhere('id', 'LIKE', '%' . $query . '%')

            ->orWhere('name_en', 'LIKE', '%' . $query . '%')
            ->orWhereHas('brand', function ($q) use ($query) {
                $q->where('name_th', 'LIKE', '%' . $query . '%');
                $q->where('name_en', 'LIKE', '%' . $query . '%');
            })
            ->with('brand') // Include the brand relationship
            ->get();

        return response()->json($products); // Return the products as JSON
    }
    public function destroy_fileproduct(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        }
            $deleted = Product_image::destroy($request->id);
            if (!$deleted) {
                $success = false;
            }
        if ($request->id == null) {
            return response()->json(false);
        }

        return response()->json('true');
    }
    public function destroy_fileurl(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);

            $deleted = Product_link::destroy($request->id);
            if (!$deleted) {
                $success = false;
            }

        return response()->json('true');
    }
    public function destroy_text(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        }

            $deleted = Text_product::destroy($request->id);
            if (!$deleted) {
                $success = false;
            }

        return response()->json('end');
    }
    public function destroy_link(Request $request)
    {

        $ids = explode(',', $request->id);

            $deleted = Sale_product::destroy($request->id);

        return response()->json('true');
    }
    public function updateStatus(Request $request)
    {
        try {
            $item = Product::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
