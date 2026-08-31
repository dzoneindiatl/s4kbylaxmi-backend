<?php

namespace App\Http\Controllers\Admin;

use App\Config;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ProductDetailManager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Redirect,DB,Response,Str,Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;


class ProductDetailManagerController extends Controller
{
    public $model = 'product-detail-manager';
    public function __construct(Request $request) {
        $this->listRouteName = 'admin-product-detail-manager.index';
        View()->share('model', 'ProductDetailManager');
        View()->share('listRouteName', $this->listRouteName);
        $this->request = $request;

    }

    public function index(Request $request) {
        $DB = ProductDetailManager::query();
        $sortBy = $request->input('sortBy') ? $request->input('sortBy') : 'product_detail_manager.order';
        $order = $request->input('order') ? $request->input('order') : 'ASC';
        $offset = !empty($request->input('offset')) ? $request->input('offset') : 0 ;
        $limit =  !empty($request->input('limit')) ? $request->input('limit') : Config("Reading.records_per_page");

        if ($request->all()) {
            $searchData            =    $request->all();
            unset($searchData['display']);
            unset($searchData['_token']);
            if (isset($searchData['order'])) {
                unset($searchData['order']);
            }
            if (isset($searchData['sortBy'])) {
                unset($searchData['sortBy']);
            }
            if (isset($searchData['offset'])) {
                unset($searchData['offset']);
            }
            if (isset($searchData['limit'])) {
                unset($searchData['limit']);
            }
            if ((!empty($searchData['date_from'])) && (!empty($searchData['date_to']))) {
                $dateS = $searchData['date_from'];
                $dateE = $searchData['date_to'];
                $DB->whereBetween('product_detail_manager.created_at', [$dateS . " 00:00:00", $dateE . " 23:59:59"]);
            } elseif (!empty($searchData['date_from'])) {
                $dateS = $searchData['date_from'];
                $DB->where('product_detail_manager.created_at', '>=', [$dateS . " 00:00:00"]);
            } elseif (!empty($searchData['date_to'])) {
                $dateE = $searchData['date_to'];
                $DB->where('product_detail_manager.created_at', '<=', [$dateE . " 00:00:00"]);
            }
            if (!empty($searchData['section_name'])) {
                $DB->where('product_detail_manager.section_name', 'like', '%' . $searchData['section_name'] . '%');
            }
            if (!empty($searchData['field_type'])) {
                $DB->where('product_detail_manager.field_type', 'like', '%' . $searchData['field_type'] . '%');
            }
        }

        $results = $DB->orderBy($sortBy, $order)->offset($offset)->limit($limit)->get();
        $totalResults = $DB->count();

        if($request->ajax()){
            return  View("admin.$this->model.load_more_data", compact('results','totalResults'));
        }else{
            return  View("admin.$this->model.index", compact('results','totalResults'));
        }
    }

    public function create(Request $request) {
        $action = 'create';
        if($request->isMethod('post')){
            try{
                $rules = [
                    'section_name' => 'required|unique:product_detail_manager,section_name',
                    'field_type' => 'required',
                ];
                $validator = Validator::make($request->all(),$rules);
                if($validator->fails()) {
                    return Redirect::back()->withErrors($validator);
                }
                DB::beginTransaction();
                $slug = Str::slug($request->title);

                //echo "<pre>"; print_r($request->all()); die;
                $productDetailManager = new ProductDetailManager();
                $productDetailManager->section_name = $request->section_name;
                $productDetailManager->field_type = $request->field_type;
                $productDetailManager->content = $request->content;
                $productDetailManager->save();

                DB::commit();
                return redirect()->route("admin-$this->model.index")->with(['success'=>"Section Added Successfully."]);
            } catch(Exception $e) {
                DB::rollback();
                Log::error($e);
                return redirect()->back()->with(['error' => $e->getMessage()]);
            }
        } else {
            return view("admin.$this->model.create",compact('action'));
        }
    }

    public function edit(Request $request, $id) {
        
        $action = 'edit';
        // $id = base64_decode($id);
        if($request->isMethod('post')){
            try{
                $rules = [
                    // 'section_name' => 'required|unique:product_detail_manager,section_name',
                    'section_name' => 'required',
                    'field_type' => 'required'
                ];
                // echo "<pre>"; print_r($request->all()); die;
                $validator = Validator::make($request->all(),$rules);
                if($validator->fails()) {
                    return Redirect::back()->withErrors($validator);
                }
                DB::beginTransaction();
                $slug = Str::slug($request->title);
                
                //echo "<pre>"; print_r($request->all()); die;
                $productDetailManager = ProductDetailManager::find($id);
                $productDetailManager->section_name = $request->section_name;
                $productDetailManager->field_type = $request->field_type;
                $productDetailManager->content = $request->content;
                $productDetailManager->save();

                DB::commit();
                return redirect()->route("admin-$this->model.index")->with(['success'=>"Section Updated Successfully."]);
            } catch(Exception $e) {
                DB::rollback();
                Log::error($e);
                return redirect()->back()->with(['error' => $e->getMessage()]);
            }
        } else {
            $productDetailManager = ProductDetailManager::find($id);
            return view("admin.$this->model.create",compact('action','productDetailManager'));
        }
    }

    public function destroy($enuserid) {
        $product_detail_manager_id = '';
        if (!empty($enuserid)) {
            // $product_detail_manager_id = base64_decode($enuserid);
            $product_detail_manager_id = $enuserid;
        }
        $userDetails = ProductDetailManager::find($product_detail_manager_id);
        if (empty($userDetails)) {
            return Redirect()->route($this->model . '.index');
        }
        if ($product_detail_manager_id) {
            ProductDetailManager::where('id', $product_detail_manager_id)->delete();
            Session()->flash('flash_notice', trans("Section has been removed successfully."));
        }
        return back();
    }

    public function changeStatus($modelId = 0, $status = 0) {
        if ($status == 1) {
            $statusMessage = trans("Section has been activated successfully");
        } else {
            $statusMessage = trans("Section has been deactivated successfully");
        }
        $user = ProductDetailManager::find($modelId);
        if ($user) {
            $currentStatus = $user->is_active;
            if (isset($currentStatus) && $currentStatus == 0) {
                $NewStatus = 1;
            } else {
                $NewStatus = 0;
            }
            $user->is_active = $NewStatus;
            $ResponseStatus = $user->save();
        }
        Session()->flash('flash_notice', $statusMessage);
        return back();
    }

    function updateOrder(Request $request)
    {
        $requestOrder    =    $request->input("requestData");

        if (!empty($requestOrder)) {
            foreach ($requestOrder as $order) {
                ProductDetailManager::where("id", $order["id"])->update(array("order" => $order["order"]));
            }
        }
        die;
    }
}