<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Components\Admin\CommonManager;
use Auth, Redirect, DB, Validator;
use Image, stdClass;
use Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class DashboardController extends BaseController
{
    public function __construct()
    {
		$this->middleware('auth:admin');
    }

    public function dashboard()
    {	
        return view('admin.dashboard');
    }
	
	public function categories()
    {	
        return view('admin.categories.index');
    }
	
	public function status()
    {	
        return view('admin.status.index');
    }
	
	public function changepassword()
	{
		return view('admin.changepassword');
	}
	
	public function updatechangepassword(Request $request)
	{
		if ($request->ajax())
		{
			$rules = array(
				'password' => 'required|min:6',
				'confirmed' => 'required|min:6'
			);
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['status' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
			}
			
			$password = $request->password;
			$confirmed = $request->confirmed;
			if($password != $confirmed) {
				return response()->json(['status' => 'errors', 'errors' => ['confirmed' => 'The password confirmation does not match.']]);
			}
			
			$loginid = Auth::user()->id;
			
			$status = DB::table('admins')->where('id', $loginid)->update(['password' => Hash::make($password)]);
			if($status) {
				return response()->json(['status' => 'success', 'msg' => 'Successfully Updated']);
			}
			
			return response()->json(['status' => 'error', 'msg' => ['confirmed' => 'Something wrong']]);
		}
	}
	
	public function category_handle_attribute(Request $request)
	{
		$params = $request->all();
		$product_id = $params['id'];
		
		$attribute_type = $params['attribute_type'];
		//$attribute_type_id = 1;
		$groups = $params['group'];
		$attr = $params['attr'];
		$attrvalue = $params['attrvalue'];
		$attroption = $params['attroption'];
		
		$obj = CommonManager::getInstance();
		
		//echo '<pre>'; print_r($groups); print_r($attr); print_r($attrvalue); die;
		$flag = false;
		$obj->handelAttrSaveUpdate($product_id, $attribute_type, $groups, $attr, $attrvalue, $attroption, $flag);
		//die;
		if($flag == true)
		{
			return Redirect::to('product_form/'.$product_id.'#attributes') ->with('success','Attribute created succesfully!');
		}
		return Redirect::to('product_form/'.$product_id.'#attributes') ->with('error','There was a failure while update attrbute!');
	}
	
	public function attributes_ajax(Request $request)
	{
		## Read value
		$emptyObj = $this->ajax_search($request);
		
		// $columnIndex_arr = $request->get('order');
		 //$columnName_arr = $request->get('columns');
		 $order_arr = $request->get('order');
		 $search_arr = $request->get('search');
		 

		 //$columnIndex = $columnIndex_arr[0]['column']; // Column index
		 //$columnName = $columnName_arr[$columnIndex]['data']; // Column name
		 //$columnSortOrder = $order_arr[0]['dir']; // asc or desc
		 $searchValue = $search_arr['value']; // Search value

		 // Total records
		 $totalRecords = DB::table('attribute_types as c')->select('count(*) as allcount')->count();
		 $totalRecordswithFilter = $totalRecords;

		 // Fetch records
		 $records = DB::table('attribute_types as c')
		   //->where('employees.name', 'like', '%' .$searchValue . '%')
		   ->select('c.*')
		   ->orderBy('c.id', 'DESC')
		   ->skip($emptyObj->start)
		   ->take($emptyObj->rowperpage)
		   ->get();

		 $data_arr = array();
		 $obj = CommonManager::getInstance();
		 
		 foreach($records as $record){
			 $action = '';
			$id = $record->id;
			$data_arr[] = array(
			  "no" => $id,
			   "name" => $record->name,
			  "action" => $action
			  
			);
		 }

		 $response = array(
			"draw" => intval($emptyObj->draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordswithFilter,
			"aaData" => $data_arr
		 );

		 echo json_encode($response);
		 exit;
	}
	
	
	public function category_form(Request $Request, $id)
    {
		$data = $this->get_category($id);
		if($data['obj'] == null && $id > 0) {
			return Redirect::to('category_form/-1');
		}
        return view('admin.categories.form', $data);
    }
	
	private function get_category($id)
	{
		$obj = CommonManager::getInstance();

		//$data = ['id' => $id, 'sub_cat' => $sub_cat, 'parants' => $parant, 'obj' => null, 'attributeTypes' => $obj->get_attribute_types(), 'attributes' => $attributes];
		$data = ['id' => $id, 'obj' => null];
		if($id > 0)
		{
			$category = $obj->getCategoryById($id);
			if($category) {
				$data = ['id' => $id, 'obj' => $category];
			}
		}
		
		//echo '<pre>'; print_r($data); die;
		return $data;
	}
	
	private function ajax_search($request)
	{
		$emptyObj = new \stdClass;
		$emptyObj->draw = $request->get('draw');
		$emptyObj->start = $request->get("start");
		$emptyObj->rowperpage = $request->get("length"); // Rows display per page
		
		return $emptyObj;
	}
	
	public function category_ajax(Request $request)
	{
		## Read value
		$emptyObj = $this->ajax_search($request);
		
		 $order_arr = $request->get('order');
		 $search_arr = $request->get('search');
		 
		 $searchValue = $search_arr['value']; // Search value

		 // Total records
		 $datatotalRecords = DB::table('categories as c')->select('count(*) as allcount');
		 if($searchValue != null) {
			 $datatotalRecords->where('c.name', 'like', '%' .$searchValue . '%');
		 }
		 $totalRecords = $datatotalRecords->count();
		 $totalRecordswithFilter = $totalRecords; //DB::table('categories')->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

		 // Fetch records
		 $data = DB::table('categories as c')
		   ->select('c.*')
		   ->orderBy('c.id', 'DESC')
		   ->skip($emptyObj->start)
		   ->take($emptyObj->rowperpage);
		
		if($searchValue != null) {
			$data->where('c.name', 'like', '%' .$searchValue . '%');
		}
		
		$records = $data->get();
		 $data_arr = array();
		 $obj = CommonManager::getInstance();
		 
		 foreach($records as $record){
			$id = $record->id;
			$filename = $record->banner_image;
			$image = '';
			if($filename != null)
			{
				$path = cdn($filename);
				$image = '<img src="'.$path.'" height="33" />';
			}
			
			$url = url('category_form', ['id' => $record->id]);
			$action = "<a href='".$url."'>Edit</a>";
			
			$data_arr[] = array(
			  "no" => $id,
			  "image" => $image,
			  "name" => $record->name,
			  //"status" => ($record->status == ACTIVE) ? 'Active' : 'InActive',
			  "action" => $action
			  
			);
		 }

		 $response = array(
			"draw" => intval($emptyObj->draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordswithFilter,
			"aaData" => $data_arr
		 );

		 echo json_encode($response);
		 exit;
	}
	
	
	public function category_handle(Request $request)
	{
		if ($request->ajax())
		{
			$id = ($request->id > 0) ? $request->id : 0;
			$rules = array(
				'name' => 'required|unique:categories'
			);
			if($id > 0) {
				$rules = array(
					'name' => Rule::unique('categories')->ignore($id)
				);
			}
			
			
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['status' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
			}
			
			
			$flag = false;
			$params = $this->category_handle_post($request, $id, $flag);
			//return response()->json(['status' => 'error', 'params' => $params, 'msg' => 'Please try again']);
			$obj = CommonManager::getInstance();
			if($id > 0)
			{
				$obj->updateCategoryById($id, $params);
			} else {
				$id = $obj->insert_category($params, true);
			}
			
			if($id > 0)
			{
				$params = $this->uploadImage($request, 'image', 'categories');
				if(count($params) > 0) {
					$obj->updateCategoryById($id, $params);
				}
				$params = $this->uploadImage($request, 'banner_image', 'categories');

				if(count($params) > 0) {
					$obj->updateCategoryById($id, $params);
				}
				return response()->json(['status' => 'success', 'msg' => 'Successfully Save']);
			}
			
			return response()->json(['status' => 'error', 'msg' => 'Please try again']);
		}
	}

	
	private function category_handle_post($request, $id, &$flag = false)
	{
		$params = [];
		$params['name'] = $request->name;
		if($id == 0) {
			$params['slug'] = \Str::slug($params['name'], '-');
		}
		
		$params['status'] = 1;
		
		
		return $params;
	}
	
	
	
}
