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
use App\Models\Backend\Product_attribute;
use App\Models\Backend\ProductIcon;
use App\Models\Backend\ProductPackingSize;
use App\Models\Backend\ProductDetail;
use App\Models\Backend\ProductDetailValue;
use App\Models\Backend\Category1;
use App\Models\Backend\Category2;
use App\Models\Backend\Category3;
use App\Models\Backend\Sale_product;
use App\Models\Backend\Brand;
use App\Models\Backend\AttributeModel;

use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'product';

    public function items($parameters)
    {
        $keyword = Arr::get($parameters, 'keyword');
        $status = Arr::get($parameters, 'status');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Product;
        if($keyword) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($keyword) . '%');
            $query = $query->orWhere('name_en', 'LIKE', '%' . trim($keyword) . '%');
        }
        if($status) {
            $query = $query->where('status', $status);
        }
        $query = $query->orderBy('sort', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new \stdClass();
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
        $items = Product::query()->paginate(10);
        $items->pages = new Product();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ผลิตภัณฑ์ของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "สินค้า", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category1' => Category1::where('status','on')->get(),
            'brand' => Brand::all(),
            'attribute' => AttributeModel::all(),
            'product_detail' => ProductDetail::all(),
            'items' => $items
        ]);
    }

    public function edit(Request $request, $id)
    {
        $items = Product::query()->paginate(10);
        $items->pages = new Product();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ผลิตภัณฑ์ของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "สินค้า", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category1' => Category1::where('status','on')->get(),
            'brand' => Brand::all(),
            'product_detail' => ProductDetail::all(),
            'product_image' => Product_image::where('product_id', $id)->get(),
            'product_attribute' => Product_attribute::where('product_id', $id)->get(),
            'attribute' => AttributeModel::all(),
            'product_detail_value' => ProductDetailValue::where('product_id', $id)->get(),
            'product_packing_size' => ProductPackingSize::where('product_id', $id)->get(),
            'items' => $items,
            'row' => Product::find($id),
            'id' => $id,
            'sale_product' => Sale_product::where('product_id', $id)->get(),
        ]);
    }

    public function destroy(Request $request)
    {
        $products = Product::with(['product_image', 'product_icon', 'product_packing_size', 'product_detail_value', 'product_link', 'sale_product'])
            ->whereIn('id', explode(',', $request->id))
            ->get();

        if ($products->isEmpty()) {
            return response()->json(false);
        }

        foreach ($products as $product) {

            // Product::where('sort', '>', $data->sort)
            // ->update([
            //     'sort' => DB::raw('`sort` - 1'),
            // ]);

            foreach ($product->product_image as $prod_image) {
                Storage::disk('public')->delete($prod_image->image);
            }

            foreach ($product->product_icon as $prod_icon) {
                Storage::disk('public')->delete($prod_icon->image);
            }

            foreach ($product->product_link as $prod_link) {
                Storage::disk('public')->delete($prod_link->image);
            }

            $product->product_image()->delete();
            $product->product_icon()->delete();
            $product->product_packing_size()->delete();
            $product->product_detail_value()->delete();
            $product->product_link()->delete();
            // $product->sale_product()->delete();

            $product->delete();
        }

        return response()->json(true);
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
                $sort = Product::max('sort') + 1;
                $data = new Product();

                $data->sort = $sort;
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = Product::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name_th = $request->name_th;
            $data->name_en = $request->name_en;
            $data->description_th = $request->description_th;
            $data->description_en = $request->description_en;
            $data->product_age_th = $request->product_age_th;
            $data->product_age_en = $request->product_age_en;
            $data->suitable_for_th = $request->suitable_for_th;
            $data->suitable_for_en = $request->suitable_for_en;
            $data->category1_id = $request->category1_id;
            $data->brand_id     = $request->brand_id;

            if($data->save()){
            
                // รูปภาพ
                $path = 'upload/product';
                if ($request->file("path")) {
                    $files = $request->file("path");
                    $ids = $request->id_path;
                
                    foreach ($files as $index => $file) {
                        $fileId = $ids[$index] ?? null;
                
                        $imgName = $path . '/product-img-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path($path), $imgName);
                
                        if ($fileId) {
                            // Update
                            $Product_image = Product_image::find($fileId);
                            if ($Product_image) {
                                Storage::disk('public')->delete($Product_image->image);
                                
                                $Product_image->image = $imgName;
                                $Product_image->save();
                            }
                        } else {
                            // Create
                            $Product_image = new Product_image();
                            $Product_image->created_at = date('Y-m-d H:i:s');
                            $Product_image->updated_at = date('Y-m-d H:i:s');
                            $Product_image->image = $imgName;
                            $Product_image->product_id = $data->id;
                            $Product_image->save();
                        }
                    }
                }

                // รูปภาพ Icon
                $path = 'upload/product-icon';
                if ($request->file("path_icon")) {
                    $files = $request->file("path_icon");
                    $ids = $request->id_path_icon;
                
                    foreach ($files as $index => $file) {
                        $fileId = $ids[$index] ?? null;
                
                        $imgName = $path . '/product-icon-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path($path), $imgName);
                
                        if ($fileId) {
                            $product_icon = ProductIcon::find($fileId);
                            if ($product_icon) {
                                Storage::disk('public')->delete($product_icon->image);
                                
                                $product_icon->image = $imgName;
                                $product_icon->save();
                            }
                        } else {
                            $product_icon = new ProductIcon();
                            $product_icon->created_at = date('Y-m-d H:i:s');
                            $product_icon->updated_at = date('Y-m-d H:i:s');
                            $product_icon->image = $imgName;
                            $product_icon->product_id = $data->id;
                            $product_icon->save();
                        }
                    }
                }

               
                $attribute_size_id = array();
                if ($request->has('attribute_size')) {
                    $product_detail_value_id = array();
                    foreach ($request->attribute_size as $i  => $attribute) {
                        
                            $fileId_attribute = $attribute['attribute_size_id'][0] ?? null;
                            if ($fileId_attribute) {
                                // อัปเดตข้อมูลหากมี ID เดิม
                                $product_attribute = Product_attribute::find($fileId_attribute);
                                if ($product_attribute) {
                                   
                                    $product_attribute->product_id = $product_attribute->product_id;
                                    $product_attribute->attribute_id = $attribute['id'][0];
                                    $product_attribute->updated_at = now();
                                    $product_attribute->save();
                                }
                            } else {
                                $product_attribute = new Product_attribute();
                                $product_attribute->created_at = now();
                                $product_attribute->updated_at = now();
                                $product_attribute->attribute_id = $attribute['id'];
                                $product_attribute->product_id = $data->id;
                                $product_attribute->save();
                            }
                       
                    }
                 
                }
                
                // ขนาดบรรจุ หลิว
                if($request->packing_size){
                    $packing_size_id = array();
                    foreach( $request->packing_size as $i => $packing ) {
                        if($packing['packing_size_id'][0]){
                            $packing_size = ProductPackingSize::find($packing['packing_size_id'][0]);
                        }else{
                            $packing_size = new ProductPackingSize;
                        }
                        $packing_size->product_id = $data->id;
                        $packing_size->name = $packing['name'][0];
                        $packing_size->price = $packing['price'][0];
                        if($packing_size->save()){
                            $packing_size_id[] = $packing_size->id;
                        }
                    }
    
                    ProductPackingSize::where('product_id',$data->id)->whereNotIn('id',$packing_size_id)->delete();
                }

                  // รายละเอียดสินค้า หลิว
                if($request->product_detail){
                    $product_detail_value_id = array();
                    foreach( $request->product_detail as $i => $prod_detail ) {
                        if($prod_detail['product_detail_value_id'][0]){
                            $product_detail_value = ProductDetailValue::find($prod_detail['product_detail_value_id'][0]);
                        }else{
                            $product_detail_value = new ProductDetailValue;
                        }
                        $product_detail_value->product_id = $data->id;
                        $product_detail_value->product_detail_id = $prod_detail['product_detail_id'][0];
                        $product_detail_value->amount = $prod_detail['amount'][0];
                        if($product_detail_value->save()){
                            $product_detail_value_id[] = $product_detail_value->id;
                        }
                    }
    
                    ProductDetailValue::where('product_id',$data->id)->whereNotIn('id',$product_detail_value_id)->delete();
                }
                // อัพเดทสั่งซื้อออนไลน์ จะเข้าเฉพาะหน้า Edit
                if($request->id_link){
                    foreach( $request->id_link as $index => $id_link ) {
                        $Product_link = Product_link::find($id_link);
                        if ($Product_link) {
                            $Product_link->url = $request->urls[$index];
                            $Product_link->save();
                        }
                    }
                }

                // สั่งซื้อออนไลน์
                $path = 'upload/product-link';
                if ($request->urls && $request->hasFile('images')) {
                    $files = $request->file("images");
                    $ids = $request->id_link;

                    foreach ($files as $index => $file) {
                        $fileId = $ids[$index] ?? null;
                
                        $imgName = $path . '/link-img-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path($path), $imgName);

                        if ($fileId) {
                            // Update
                            $Product_link = Product_link::find($fileId);
                            if ($Product_link) {
                                Storage::disk('public')->delete($Product_link->image);

                                $Product_link->url = $request->urls[$index];
                                $Product_link->image = $imgName;
                                $Product_link->save();
                            }
                        } else {
                            // Create
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

                // ผลิตภัณฑ์อื่นๆ
                // if ($request->selected_ids) {
                //     if($id != null){
                //         Sale_product::where('product_id', $id)->delete();
                //     }
                //     foreach ($request->selected_ids as $index => $ids) {
                //         $Sale = new Sale_product();
                //         $Sale->created_at = date('Y-m-d H:i:s');
                //         $Sale->updated_at = date('Y-m-d H:i:s');
                //         $Sale->big_product_id = $ids;
                //         $Sale->product_id = $data->id;
                //         $Sale->save();
                //     }
                // }

                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
            } else {
                DB::rollback();
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

    public function destroy_fileproduct(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {
            $data = Product_image::find($request->id);

            if (@$data) {
                Storage::disk('public')->delete(@$data->image);
                $query = Product_image::destroy($data->id);
            }

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }

    public function destroy_fileproduct_icon(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {
            $data = ProductIcon::find($request->id);

            if (@$data) {
                Storage::disk('public')->delete(@$data->image);
                $query = ProductIcon::destroy($data->id);
            }

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }

    public function destroy_product_packing_size(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {

            $query = ProductPackingSize::destroy($request->id);

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }
    public function destroy_product_attribute(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {

            $query = Product_attribute::destroy($request->id);

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }

    public function destroy_product_detail(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {

            $query = ProductDetailValue::destroy($request->id);

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }

    public function destroy_fileurl(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {
            $data = Product_link::find($request->id);

            if (@$data) {
                Storage::disk('public')->delete(@$data->image);
                $query = Product_link::destroy($data->id);
            }

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }

    public function destroy_link(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {

            $query = Sale_product::destroy($request->id);

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
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

    // Sort หลิว
    public function updateSortOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            Product::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return response()->json(['success' => true]);
    }

    // public function updateRowOrder(Request $request, $id = null)
    // {
    //     $id = $request->input('id');
    //     $old_sort = (int) $request->input('old_sort');
    //     $new_sort = (int) $request->input('new_sort');

    //     $product = Product::find($id); 
    
    //     if ($old_sort > $new_sort) {
    //         Product::where('sort', '>=', $new_sort)
    //             ->where('sort', '<', $old_sort)
    //             ->update([
    //                'sort' => DB::raw('`sort` + 1'), 
    //             ]);
    //     } else {
    //         Product::where('sort', '<=', $new_sort)
    //             ->where('sort', '>', $old_sort)
    //             ->update([
    //                 'sort' => DB::raw('`sort` - 1'), 
    //             ]);
    //     }

    //     $product->sort = $new_sort;
    //     $product->save(); 
        
    //     return response()->json(['success' => true]);
    // }
}
