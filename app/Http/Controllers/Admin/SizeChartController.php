<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\SizeChart;
use App\Models\SizeChartDetail;
use App\Models\SizeChartDetailValue;
use App\Models\SizeChartAssign;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\SizeChartImages;
use Illuminate\Support\Facades\File;
use Redirect,DB,Response;
use App\Models\SizeChartManager; 
use App\Models\SizeChartMeasurement;
use App\Models\SizeChartMeasureMentValue; 
use App\Models\SizeChartSection;
use App\Models\SizeChartSizes;

class SizeChartController extends Controller
{
    public $model = 'size-charts';
    public function __construct(Request $request)
    {

        $this->middleware('permission:view_size_chart_tebular|view_sizechart|edit_sizechart|delete_sizechart', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_sizechart', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_sizechart', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:delete_sizechart', ['only' => ['destroy']]);


        $this->listRouteName = 'admin-size-charts.index';
        View()->share('model', $this->model);
        View()->share('listRouteName', $this->listRouteName);
        $this->request = $request;

    }

    public function index(Request $request)
    {
        try {
            $DB = SizeChartManager::query();
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : 'size_charts.created_at';
            $order = $request->input('order') ? $request->input('order') : 'DESC';
            $offset = !empty($request->input('offset')) ? $request->input('offset') : 0 ;
            $limit =  !empty($request->input('limit')) ? $request->input('limit') : Config("Reading.records_per_page");
            $results = $DB->with([
                'sections.measurements.values',
                'sizes'
            ])->orderBy('id', $order)->offset($offset)->limit($limit)->get();

            $totalResults = $DB->count();

            if($request->ajax()){

                return  View("admin.$this->model.load_more_data", compact('results','totalResults'));
            }else{
                return  View("admin.$this->model.index", compact('results','totalResults'));
            }

        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }

    }

    public function create()
    {
        try {
            $categories = Category::whereNull('parent_id')->where('is_active', 1)->where('is_deleted', 0)->get();

            $products = DB::table('products')->where('is_active', 1)->where('is_deleted', 0)->select('id','name')->get()->toArray();
            $countries = Country::where('is_active', 1)->select('id','name')->get()->toArray();
            return view('admin.size-charts.create',['categories' => $categories, 'products' => $products,'countries'=>$countries]);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $formData = $request->all();  
            $validator = Validator::make( $formData, 
            [ 
                'chart_title' => 'required|string|max:255', 
                'mesurement_type' => 'required|in:inch,cm', 
            ], 
            [   'chart_title.required' => trans("The chart title field is required."), 
                'mesurement_type.required' => trans("The measurement type field is required."), 
            ] ); 
            if ($validator->fails()) { 
                return Redirect::back()->withErrors($validator)->withInput(); 
            }

            DB::beginTransaction();
            
            try{
                $manager = new SizeChartManager;
                $manager->title = $request->chart_title ; 
                $manager->type = $request->mesurement_type; 
                $manager->chart_format = $request->chart_format; 
                $manager->chart_description = $request->chart_description; 
                $folderName = "" ; 
                $filename = ""; 
                if($request->has('chart_image')){
                    $file = $request->file('chart_image'); 
                    $filename = 'chart_'.uniqid().$file->getClientOriginalExtension();
                    $folderName = strtoupper(date('M') . date('Y')) . "/";
                    $folderPath = Config('constant.SIZECHART_IMAGE_ROOT_PATH') . $folderName;
                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0777, true);
                    }
                    $manager->chart_image = $folderName . $filename; 
                }
                $manager->status = 1 ; 
                $manager->save(); 
                info("========manager data=========",[$manager]); 
                $sections = [ 
                        'upper' => $request->upper_type, 
                        'bottom' => $request->bottom_type, 
                    ];
                $sizeNames = []; 
                foreach ($request->all() as $key => $value) { 
                    if (str_starts_with($key, 'upper_size_') && !str_starts_with($key, 'upper_size_cm_')) { 
                        $sizeName = str_replace('upper_size_', '', $key); 
                        $sizeNames[$sizeName] = strtoupper($sizeName); 
                    } 
                    if (str_starts_with($key, 'bottom_size_') && !str_starts_with($key, 'bottom_size_cm_')) { 
                        $sizeName = str_replace('bottom_size_', '', $key); 
                        $sizeNames[$sizeName] = strtoupper($sizeName); 
                    }
                }
                $sizeIds = []; 
                $sizeSortOrder = 1; 
                foreach ($sizeNames as $sizeKey => $sizeName) { 
                    $size = new SizeChartSizes; 
                    $size->size_chart_id = $manager->id; 
                    $size->size_name = $sizeName; 
                    $size->sort_order = $sizeSortOrder; 
                    $size->save(); 
                    info("=========size====chart======size=======",[$size]); 
                    $sizeIds[$sizeKey] = $size->id; 
                    $sizeSortOrder++; 
                }

                foreach ($sections as $sectionType => $measurementList) { 
                    if (empty($measurementList)) { 
                        continue; 
                    } 
                    $measurementList = array_values(array_unique($measurementList)); 

                    $sec = new SizeChartSection; 
                    $sec->size_chart_id = $manager->id; 
                    $sec->section_type = $sectionType; 
                    $sec->sort_order = $sectionType == 'upper' ? 1 : 2; 
                    $sec->save(); 
                    info("==========section map=========",[$sec]); 
                    foreach ($measurementList as $measurementIndex => $measurementName) { 
                        $measurement = new SizeChartMeasurement; 
                        $measurement->size_chart_section_id = $sec->id; 
                        $measurement->measurement_name = $measurementName; 
                        $measurement->sort_order = $measurementIndex + 1; 
                        $measurement->save();
                        info("========measurement=========",[$measurement]);
                        foreach ($sizeIds as $sizeKey => $sizeId) { 
                            $inchKey = $sectionType . '_size_' . $sizeKey; 
                            $inchValues = $request->input($inchKey, []); 
                            $cmKey = $sectionType . '_size_cm_' . $sizeKey;
                            $cmValues = $request->input($cmKey, []); 
                            $value = new SizeChartMeasureMentValue; 
                            $value->size_chart_id = $manager->id; 
                            $value->size_chart_section_id = $sec->id; 
                            $value->measurement_id = $measurement->id; 
                            $value->size_id = $sizeId; 
                            $value->value_inch = $inchValues[$measurementIndex] ?? null; 
                            $value->value_cm = $cmValues[$measurementIndex] ?? null; 
                            $value->save();

                            info("=========values========",[$value]);
                        } 
                    } 
                }
                    
                DB::commit();
                return redirect()->route('admin-size-charts.index')->with('success', 'Size Chart created successfully');
            } 
            catch(Exception $e){
                Log::error($e);
                return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
            }


            // try {
            //     $obj = new SizeChart;
            //     $obj->name = $request->input('name');
            //     $obj->description = $request->input('description', '');
            //     $obj->country_id = $request->input('country_id', '');
            //     $obj->category_id = $request->input('category_id', '');
            //     $obj->sub_category_id = $request->input('sub_category_id', '');
            //     $obj->child_category_id = $request->input('child_category_id', '');
            //     $obj->centimeter_details = $request->input('centimeter_details', '');
            //     $obj->inch_details = $request->input('inch_details', '');

            //     $obj->save(); 
            //     info("==========obj======",[$obj]); 

            //     if ($request->hasFile('chart_image')) {
            //         foreach ($request->file('chart_image') as $key => $file) {
            //             $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
            //             $folderName = strtoupper(date('M') . date('Y')) . "/";
            //             $folderPath = Config('constant.SIZECHART_IMAGE_ROOT_PATH') . $folderName;
            //             if (!File::exists($folderPath)) {
            //                 File::makeDirectory($folderPath, 0777, true);
            //             }
            //             if ($file->move($folderPath, $filename)) {
            //                 $image = new SizeChartImages();
            //                 $image->size_chart_id = $obj->id; // Assuming you have a foreign key in the SizeChartImages table
            //                 $image->image = $folderName . $filename; // Store relative path
            //                 $image->heading = $request->input('image_heading')[$key];
            //                 $image->description = $request->input('image_description')[$key];
            //                 $image->save();

            //                 info("=========images=========",[$image]); 
            //             }
            //         }
            //     }

            //     // Commit the transaction after successful save
            //     DB::commit();

            //     return redirect()->route('admin-size-charts.index')->with('success', 'Size Chart created successfully');
                
            // } catch (Exception $e) {
            //     // Rollback the transaction on error
            //     DB::rollback();
            //     Log::error($e);
            //     return redirect()->back()->with(['error' => 'Something went wrong', 'error_msg' => $e->getMessage()]);
            // }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }
    }


    public function edit(Request $request, $token = null)
    {
        try {
            if (!empty($token)) {
                $chartId = base64_decode($token);
                $size_charts = SizeChartManager::with([ 'sizes', 'sections.measurements.values' ])->findOrFail($chartId);
                return View("admin.$this->model.edit",['size_charts' => $size_charts]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $token)
    {
        try {
            if (empty($token)) {
                return redirect()->route('admin-' . $this->model . '.index');
            }
            $chartId = base64_decode($token);
            $sizeChart = SizeChartManager::with(['sizes','sections.measurements.values'])->find($chartId);
            if (empty($sizeChart)) {
                return redirect()->route('admin-' . $this->model . '.index')->with('error', 'Size chart not found.');
            }

            $validator = Validator::make($request->all(), [
                'chart_title' => 'required|string|max:255',
                'mesurement_type' => 'required|in:inch,cm',
                'chart_format' => 'nullable|string',
                'chart_image' => 'nullable|mimes:jpg,jpeg,png,webp',
            ], [
                'chart_title.required' => 'The chart title field is required.',
                'mesurement_type.required' => 'The measurement type field is required.',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            DB::beginTransaction();
            $sizeChart->title = $request->input('chart_title');
            $sizeChart->type = $request->input('mesurement_type');
            $sizeChart->chart_format = $request->input('chart_format');
            $sizeChart->chart_description = $request->input('chart_description', '');

            if ($request->hasFile('chart_image')) {
                $file = $request->file('chart_image');
                $extension = $file->getClientOriginalExtension();
                $filename = 'chart_' . uniqid() . '.' . $extension;
                $folderName = strtoupper(date('M') . date('Y')) . "/";
                $folderPath = Config('constant.SIZECHART_IMAGE_ROOT_PATH') . $folderName;
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }
                $file->move($folderPath, $filename);
                $sizeChart->chart_image = $folderName . $filename;
            }
            $sizeChart->save();
            $sections = [
                'upper' => $request->input('upper_type', []),
                'bottom' => $request->input('bottom_type', []),
            ];

            foreach ($sections as $sectionType => $measurementNames) {
                $section = $sizeChart->sections ->firstWhere('section_type', $sectionType);
                if (!$section && !empty($measurementNames)) {
                    $section = new SizeChartSection;
                    $section->size_chart_id = $sizeChart->id;
                    $section->section_type = $sectionType;
                    $section->sort_order = $sectionType == 'upper' ? 1 : 2;
                    $section->save();
                }

                if (!$section) {
                    continue;
                }

                $measurementIds = $request->input($sectionType . '_measurement_id', []);
                $submittedMeasurementIds = [];
                foreach ($measurementNames as $index => $measurementName) {
                    $measurementName = trim($measurementName);
                    if ($measurementName === '') {
                        continue;
                    }

                    $measurementId = $measurementIds[$index] ?? null;
                    if (!empty($measurementId)) {
                        $measurement = SizeChartMeasurement::where('id', $measurementId)->where('size_chart_section_id', $section->id)->first();
                        if (!$measurement) {
                            continue;
                        }
                        $measurement->measurement_name = $measurementName;
                        $measurement->sort_order = $index + 1;
                        $measurement->save();
                    } else {
                        $measurement = new SizeChartMeasurement;
                        $measurement->size_chart_section_id = $section->id;
                        $measurement->measurement_name = $measurementName;
                        $measurement->sort_order = $index + 1;
                        $measurement->save();
                    }

                    $submittedMeasurementIds[] = $measurement->id;
                    foreach ($sizeChart->sizes as $size) {

                        $sizeKey = strtolower($size->size_name);
                        $inchKey = $sectionType . '_size_' . $sizeKey;
                        $cmKey = $sectionType . '_size_cm_' . $sizeKey;
                        $inchValues = $request->input($inchKey, []);
                        $cmValues = $request->input($cmKey, []);
                        $inchValue = $inchValues[$index] ?? null;
                        $cmValue = $cmValues[$index] ?? null;

                        $value = SizeChartMeasureMentValue::where('size_chart_id', $sizeChart->id)
                            ->where('size_chart_section_id', $section->id)
                            ->where('measurement_id', $measurement->id)
                            ->where('size_id', $size->id)
                            ->first();

                        if (!$value) {
                            $value = new SizeChartMeasureMentValue;
                            $value->size_chart_id = $sizeChart->id;
                            $value->size_chart_section_id = $section->id;
                            $value->measurement_id = $measurement->id;
                            $value->size_id = $size->id;
                        }

                        $value->value_inch = $inchValue !== '' ? $inchValue : null;
                        $value->value_cm = $cmValue !== '' ? $cmValue : null;
                        $value->save();
                    }
                }

                $oldMeasurements = SizeChartMeasurement::where(
                    'size_chart_section_id',
                    $section->id
                )->get();

                foreach ($oldMeasurements as $oldMeasurement) {

                    if (!in_array($oldMeasurement->id, $submittedMeasurementIds)) {
                        SizeChartMeasureMentValue::where('measurement_id',$oldMeasurement->id)->delete();
                        $oldMeasurement->delete();
                    }
                }
                $remainingMeasurements = SizeChartMeasurement::where('size_chart_section_id',$section->id)->count();
                if ($remainingMeasurements == 0) {
                    $section->delete();
                }
            }

            DB::commit();
            Session()->flash('flash_notice','Size Chart updated successfully.');
            return Redirect::route('admin-size-charts.index');

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()->with([
                'error' => 'Something is wrong',
                'error_msg' => $e->getMessage()
            ]);
        }
    }
    

    public function destroy($token)
    {
        try {
            $categoryId = '';
            if (!empty($token)) {
                $categoryId = base64_decode($token);
            }
            $category = SizeChartManager::find($categoryId);
            if (empty($category)) {
                return Redirect()->route($this->model . '.index');
            }
            if ($category) {
                SizeChartManager::where('id', $categoryId)->delete();
                Session()->flash('flash_notice', trans("Size chart has been removed successfully."));
            }
            return back();
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with(['error' => 'Something is wrong', 'error_msg' => $e->getMessage()]);
        }
    }

    public function changeStatus($modelId = 0, $status = 0)
    {
        if ($status == 1) {
            $statusMessage = trans("Size chart has been activated successfully");
        } else {
            $statusMessage = trans("Size chart has been deactivated successfully");
        }
        
        $category = SizeChartManager::find($modelId);
        if ($category) {
            $currentStatus = $category->status;
            $newStatus = $status;
            $category->status = $newStatus;
            $responseStatus = $category->save();
            if ($newStatus == 1) {
                SizeChartManager::where('id', '!=', $modelId)->update(['status' => 0]);
            }
            Session()->flash('flash_notice', $statusMessage);
        } else {
            Session()->flash('flash_notice', trans("Size chart not found"));
        }
        return back();
    }
}
