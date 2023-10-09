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
use Illuminate\Support\Str;
class IndexController extends BaseController
{
    public function __construct()
    {
		$this->middleware('auth:admin');
    }

    public function index()
    {	
        return view('admin.reels.index');
    }
	
	public function reel_form(Request $Request, $id)
    {
		$data = $this->get_reel($id);
		if($data['obj'] == null && $id > 0) {
			return Redirect::to('reel_form/-1');
		}
        return view('admin.reels.form', $data);
    }
	
	private function get_reel($id)
	{
		$obj = CommonManager::getInstance();
		$categories = $obj->getCategories();
		$data = ['id' => $id, 'obj' => null, 'categories' => $categories];
		if($id > 0)
		{
			$reel = $obj->getReelById($id);
			if($reel) {
				$data = ['id' => $id, 'obj' => $reel, 'categories' => $categories];
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
	
	public function reel_ajax(Request $request)
	{
		## Read value
		$emptyObj = $this->ajax_search($request);
		
		$order_arr = $request->get('order');
		$search_arr = $request->get('search');
		 
		$searchValue = $search_arr['value']; // Search value

		 // Total records
		 $datatotalRecords = DB::table('reels as c')->select('count(*) as allcount');
		 if($searchValue != null) {
			 $datatotalRecords->where('c.reel', 'like', '%' .$searchValue . '%');
		 }
		 $totalRecords = $datatotalRecords->count();
		 $totalRecordswithFilter = $totalRecords; //DB::table('categories')->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

		 // Fetch records
		 $data = DB::table('reels as c')
		   ->select('c.*')
		   ->orderBy('c.id', 'DESC')
		   ->skip($emptyObj->start)
		   ->take($emptyObj->rowperpage);
		
		if($searchValue != null) {
			$data->where('c.reel', 'like', '%' .$searchValue . '%');
		}
		
		$records = $data->get();
		 $data_arr = array();
		 $obj = CommonManager::getInstance();
		 
		 foreach($records as $record){
			$id = $record->id;
			$link = $record->link;
			$thumb = '';
			if($record->reel_type == 1) { //link
				
			} else if($record->reel_type == 2) { //video
				$src = cdn($record->link);// 
				//$link = public_path('uploads/reel/'.$record->id.'/'.$record->link);
				$link = '<video width="190" height="140" controls>
				  <source src="'.$src.'" type="video/mp4">
				  Sorry, your browser doesn t support the video element.
				</video>';
				
				$src = cdn(PUB.'uploads/reel/'.$record->thumb);
				$thumb = '<img src="'.$src.'" height="33" />';
			} else if($record->reel_type == 3) { //image
				$src = cdn($record->link);
				$image = '<img src="'.$src.'" height="133" />';
				$link = $image;
			}
			$url = url('reel_form', ['id' => $record->id]);
			$action = "<a href='".$url."'>Edit</a>";
			
			$data_arr[] = array(
			  "no" => $id,
			  "reel" => $record->reel,
			  "link" =>  $link,
			  "thumb" =>  $thumb,
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
	
	private function generateSlug($title) {
		$slug = Str::slug($title);
		$count = DB::table('posts')->where('slug', $slug)->count();
		if($count > 0) {
			return $slug.$count;
		}
		return $slug;
	}
	
	public function reel_handle(Request $request)
	{
		if ($request->ajax()) {
			$id = ($request->id > 0) ? $request->id : 0;
			$rules = array(
				'name' => 'required',
				'category_id' => 'required',
				'reel_type' => 'required'
			);
			
			if($request->reel_type == 1) { //link
				$rules['videolink'] = 'required';
			} else if($request->reel_type == 2) { //video
				//if($request->hasFile('video')) {
					$rules['video'] = 'required|mimes:mp4,ogx,oga,ogv,ogg,webm|max:20000';
					$rules['thumb'] = 'required|mimes:jpeg,jpg,png,gif,webp|max:10000';
				//}
				
			} else if($request->reel_type == 3) { //image
				$rules['image'] = 'required|mimes:jpeg,jpg,png,gif,webp|max:10000';
			}
			
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['status' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
			}
			$commonManagerObj = CommonManager::getInstance();
			
			try{
				$flag = false;
				DB::beginTransaction();

				if($id > 0) {
					if($request->reel_type == 1) {
						
						$status = $commonManagerObj->updateReelById($id, ['category_id' => $request->category_id, 'reel' => $request->name, 'link' => $request->videolink, 'reel_type' => $request->reel_type]);
						if($status) {
							$flag = true;
						}
						
					} else if($request->reel_type == 2) { //echo 123; die;
						$statuschk = $commonManagerObj->updateReelById($id, ['category_id' => $request->category_id, 'reel' => $request->name, 'reel_type' => $request->reel_type]);
						
						if($request->hasFile('video') && $statuschk == true) {
							
							$status = $this->uploadRemoteVideo($id, $request);
							if($status === true) {
								$flag = true;
							}
						} else {
							if($statuschk) {
								$flag = true;
							}
						}
					}
				} else {
					if($request->reel_type == 1) { // link
						
						$status = $commonManagerObj->insert_reel(['reel' => $request->name, 'category_id' => $request->category_id, 'link' => $request->videolink, 'reel_type' => 1]);
						if($status) {
							$flag = true;
						}
						
					} else if($request->reel_type == 2) { // video
						$id = $commonManagerObj->insert_reel(['category_id' => $request->category_id, 'reel' => $request->name, 'reel_type' => 2], true);
						if($id) {
							$status = $this->uploadRemoteVideo($id, $request);
							if($status === true) {
								$flag = true;
							}
						}
					} else if($request->reel_type == 3) { // image
						$id = $commonManagerObj->insert_reel(['category_id' => $request->category_id, 'reel' => $request->name, 'reel_type' => 3], true);
						if($id) {
							$status = $this->uploadReelImage($id, $request);
							if($status === true) {
								$flag = true;
							}
						}
					}
					
				}
				
				if($flag == true)
				{
					DB::commit();
					return response()->json(['status' => 'success', 'msg' => 'Success']);
					return true;
				}
				
				DB::rollback();
				return false;
				
			} catch (\Exception $e)
			{
				$message = $e->getMessage();
				$msg = $message.' The exception was created on line: '.$e->getLine();
				/* 
				Log::error('OrderManager::handleOrder '.$message.' The exception was created on line: '.$e->getLine());
				return false; */
				
			}
			
			return response()->json(['status' => 'error', 'msg' => 'Something wrong plz try again']);
			/* $video = $request->file('video');
			
			$original_name = strtolower(trim($video->getClientOriginalName()));
			$file_name = time().rand(100,999).$original_name;
			
			$destinationPath = 'uploads/reel';
			$video->move($destinationPath,$file_name);
			
			$inputAudio = public_path('/uploads/reel/'.$file_name);
			$outputAudio = public_path('/uploads/reel/outputAudio.mp4');
			//exec("ffmpeg -i $inputAudio -ab 64 -ss 00:00:05 -t 00:00:08 $outputAudio");
			exec("ffmpeg -i $inputAudio -ab 64 $outputAudio"); */
			
			
		}
	}
	
	private function uploadReelImage($id, $request) {
		$commonManagerObj = CommonManager::getInstance();
		$flag = false;

		$params = $this->uploadImage($request, 'image', 'reels');

		if(count($params) > 0) {
			$status = $commonManagerObj->updateReelById($id, $params);
			if($status) {
				return true;
			}
		}
		return false;
	}
	
	private function uploadRemoteVideo($id, $request) {
		$commonManagerObj = CommonManager::getInstance();
		$params = [];

		$paramsVideo = $this->uploadVideo($request, 'video', 'reels');
		$paramsImage = $this->uploadImage($request, 'thumb', 'reels');

		if(count($paramsVideo) > 0 && count($paramsImage) > 0) {
			$params = ['link' => $paramsVideo['video'], 'thumb' => $paramsImage['thumb']];
		}

		if(count($params) > 0) {
			$status = $commonManagerObj->updateReelById($id, $params);
			if($status) {
				return true;
			}
		}

		return false;
	}
	
	
	/* Post Start */
	public function post()
    {	
        return view('admin.post.index');
    }
	
	public function post_form(Request $Request, $id)
    {
		$data = $this->get_post($id);
		if($data['obj'] == null && $id > 0) {
			return Redirect::to('post_form/-1');
		}
        return view('admin.post.form', $data);
    }
	
	private function get_post($id)
	{
		$obj = CommonManager::getInstance();
		$categories = $obj->getCategories();
		$data = ['id' => $id, 'obj' => null, 'categories' => $categories];
		if($id > 0)
		{
			$post = $obj->getPostById($id);
			if($post) {
				$data = ['id' => $id, 'obj' => $post, 'categories' => $categories];
			}
		}
		
		return $data;
	}
	
	public function post_ajax(Request $request)
	{
		## Read value
		$emptyObj = $this->ajax_search($request);
		
		$order_arr = $request->get('order');
		$search_arr = $request->get('search');
		 
		$searchValue = $search_arr['value']; // Search value

		 // Total records
		 $datatotalRecords = DB::table('posts as c')->select('count(*) as allcount');
		 if($searchValue != null) {
			 $datatotalRecords->where('c.title', 'like', '%' .$searchValue . '%');
		 }
		 $totalRecords = $datatotalRecords->count();
		 $totalRecordswithFilter = $totalRecords; //DB::table('categories')->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

		 // Fetch records
		 $data = DB::table('posts as c')
		   ->select('c.*')
		   ->orderBy('c.id', 'DESC')
		   ->skip($emptyObj->start)
		   ->take($emptyObj->rowperpage);
		
		if($searchValue != null) {
			$data->where('c.reel', 'like', '%' .$searchValue . '%');
		}
		
		$records = $data->get();
		 $data_arr = array();
		 $obj = CommonManager::getInstance();
		 
		 foreach($records as $record){
			$id = $record->id;
			$image = '';
			if($record->image != null) {
				$src = cdn($record->image);
				$image = '<img src="'.$src.'" height="33" />';
			}
			
			
			$url = url('post_form', ['id' => $record->id]);
			$action = "<a href='".$url."'>Edit</a>";
			//$action .= " <i class='fa fa-mail' aria-hidden='true'></i> <i class='fa fa-heart' aria-hidden='true'></i> <i class='fa fa-eye' aria-hidden='true'></i>  <i class='fa fa-share' aria-hidden='true'></i> ";
			
			$data_arr[] = array(
			  "no" => $id,
			  "title" => $record->title,
			  "image" => $image,
			  "desc" =>  $record->desc,
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
	
	public function post_handle(Request $request)
	{
		if ($request->ajax())
		{
			$id = ($request->id > 0) ? $request->id : 0;
			$rules = array(
				'title' => 'required',
				'category_id' => 'required',
				'img' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
				'desc' => 'required'
			);
			
			if($id > 0) {
				$rules['img'] = 'mimes:jpeg,jpg,png,gif,webp|max:10000';
			}
			
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['status' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
			}
			
			$params = ['title' => $request->title, 'desc' => $request->desc, 'category_id' => $request->category_id, 'status' => 1];
			
			if($id > 0) {
				if($request->hasFile('img'))
				{
					$image = $this->uploadPostImage($request);
			
					if($image === false) {
						return response()->json(['status' => 'error', 'msg' => 'Image not uploaded']);
					} else {
						$params['image'] = $image;
					}
				}
			} else {
				$image = $this->uploadPostImage($request);
			
				if($image === false) {
					return response()->json(['status' => 'error', 'msg' => 'Image not uploaded']);
				}
				$params['image'] = $image;
			}
			
			
			$commonManagerObj = CommonManager::getInstance();
			if($id > 0) {
				
				$status = $commonManagerObj->updatePostById($id, $params);
				if($status) {
					return response()->json(['status' => 'success', 'msg' => 'Successfully Updated']);
				}
				
			} else {
				$params['slug'] = $this->generateSlug($request->title);
				$status = $commonManagerObj->insert_post($params);
				if($status) {
					return response()->json(['status' => 'success', 'msg' => 'Successfully Save']);
				}
			}
			
			return response()->json(['status' => 'error', 'msg' => 'Please try again']);
		}
	}
	
	private function uploadPostImage($request) {
		return $this->uploadImage($request, 'img', 'posts')['img'];
	}
	/* Post End */
	
	/* Banner Start */
	public function banner()
    {	
        return view('admin.banner.index');
    }
	
	public function banner_form(Request $Request, $id)
    {
		$data = $this->get_banner($id);
		if($data['obj'] == null && $id > 0) {
			return Redirect::to('banner_form/-1');
		}
        return view('admin.banner.form', $data);
    }
	
	private function get_banner($id)
	{
		$obj = CommonManager::getInstance();

		$data = ['id' => $id, 'obj' => null];
		if($id > 0)
		{
			$reel = $obj->getBannerById($id);
			if($reel) {
				$data = ['id' => $id, 'obj' => $reel];
			}
		}
		
		return $data;
	}
	
	public function banner_ajax(Request $request)
	{
		## Read value
		$emptyObj = $this->ajax_search($request);
		
		$order_arr = $request->get('order');
		$search_arr = $request->get('search');
		 
		$searchValue = $search_arr['value']; // Search value

		 // Total records
		 $datatotalRecords = DB::table('banners as c')->select('count(*) as allcount');
		 if($searchValue != null) {
			 $datatotalRecords->where('c.banner', 'like', '%' .$searchValue . '%');
		 }
		 $totalRecords = $datatotalRecords->count();
		 $totalRecordswithFilter = $totalRecords; //DB::table('categories')->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

		 // Fetch records
		 $data = DB::table('banners as c')
		   ->select('c.*')
		   ->orderBy('c.id', 'DESC')
		   ->skip($emptyObj->start)
		   ->take($emptyObj->rowperpage);
		
		if($searchValue != null) {
			$data->where('c.banners', 'like', '%' .$searchValue . '%');
		}
		
		$records = $data->get();
		 $data_arr = array();
		 $obj = CommonManager::getInstance();
		 
		 foreach($records as $record){
			$id = $record->id;
			$image = '';
			if($record->banner != null) {
				$src = cdn($record->banner);
				$image = '<img src="'.$src.'" height="33" />';
			}
			
			
			$url = url('banner_form', ['id' => $record->id]);
			$action = "<a href='".$url."'>Edit</a>";
			
			$data_arr[] = array(
			  "no" => $id,
			  "banner" => $image,
			  "position" =>  $record->position,
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
	
	public function banner_handle(Request $request)
	{
		if ($request->ajax())
		{
			$id = ($request->id > 0) ? $request->id : 0;
			$rules = array(
				'banner' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10000',
				'position' => 'required'
			);
			
			if($id > 0) {
				$rules['banner'] = 'mimes:jpeg,jpg,png,gif,webp|max:10000';
			}
			
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['status' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
			}
			
			$params = ['position' => $request->position, 'status' => 1];
			
			if($id > 0) {
				if($request->hasFile('banner'))
				{
					$image = $this->uploadBannerImage($request);
			
					if($image === false) {
						return response()->json(['status' => 'error', 'msg' => 'Image not uploaded']);
					} else {
						$params['banner'] = $image;
					}
				}
			} else {
				$image = $this->uploadBannerImage($request);
			
				if($image === false) {
					return response()->json(['status' => 'error', 'msg' => 'Image not uploaded']);
				}
				$params['banner'] = $image;
			}
			
			
			$commonManagerObj = CommonManager::getInstance();
			if($id > 0) {
				
				$status = $commonManagerObj->updateBannerById($id, $params);
				if($status) {
					return response()->json(['status' => 'success', 'msg' => 'Successfully Updated']);
				}
				
			} else {
				
				$status = $commonManagerObj->insert_banner($params);
				if($status) {
					return response()->json(['status' => 'success', 'msg' => 'Successfully Save']);
				}
			}
			
			return response()->json(['status' => 'error', 'msg' => 'Please try again']);
		}
	}
	
	private function uploadBannerImage($request) {
		return $this->uploadImage($request, 'banner', 'banners')['banner'];
	}
	/* Banner End */
	
	/* Story Start */
	public function story()
    {	
        return view('admin.story.index');
    }
	
	public function story_form(Request $Request, $id)
    {
		$data = $this->get_story($id);
		if($data['obj'] == null && $id > 0) {
			return Redirect::to('story_form/-1');
		}
        return view('admin.story.form', $data);
    }
	
	private function get_story($id)
	{
		$obj = CommonManager::getInstance();

		$data = ['id' => $id, 'obj' => null];
		if($id > 0)
		{
			$reel = $obj->getStoryById($id);
			if($reel) {
				$data = ['id' => $id, 'obj' => $reel];
			}
		}
		
		return $data;
	}
	
	public function story_ajax(Request $request)
	{
		## Read value
		$emptyObj = $this->ajax_search($request);
		
		$order_arr = $request->get('order');
		$search_arr = $request->get('search');
		 
		$searchValue = $search_arr['value']; // Search value

		 // Total records
		 $datatotalRecords = DB::table('stories as c')->select('count(*) as allcount');
		 if($searchValue != null) {
			 $datatotalRecords->where('c.time', 'like', '%' .$searchValue . '%');
		 }
		 $totalRecords = $datatotalRecords->count();
		 $totalRecordswithFilter = $totalRecords; //DB::table('categories')->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

		 // Fetch records
		 $data = DB::table('stories as c')
		   ->select('c.*')
		   ->orderBy('c.id', 'DESC')
		   ->skip($emptyObj->start)
		   ->take($emptyObj->rowperpage);
		
		if($searchValue != null) {
			$data->where('c.time', 'like', '%' .$searchValue . '%');
		}
		
		$records = $data->get();
		 $data_arr = array();
		 $obj = CommonManager::getInstance();
		 
		 foreach($records as $record){
			$id = $record->id;
			$story = $story_type = '';
			
			if($record->story_type == 1) { //img
				$story_type = 'Image';
				if($record->story != null) {
					
					$src = cdn($record->story);
					$story = '<a href="'.$record->link.'" target="_blank"><img src="'.$src.'" height="50" /></a>';
				}
			} else if($record->story_type == 2) {
				$story_type = 'Video';
				$src = cdn($record->story);
				$story = '<video width="130" height="100" controls>
				  <source src="'.$src.'" type="video/mp4">
				  Sorry, your browser doesn t support the video element.
				</video>';
			} else if($record->story_type == 3) {
				$story = '<a href="'.$record->story.'" target="_blank">Link</a>';
			}
			
			
			
			$url = url('story_form', ['id' => $record->id]);
			$action = "<a href='".$url."'>Edit</a>";
			
			$data_arr[] = array(
			  "no" => $id,
			  "story" => $story,
			  "story_type" =>  $story_type,
			  "time" => $record->time,
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
	
	public function story_handle(Request $request)
	{
		if ($request->ajax())
		{
			$id = ($request->id > 0) ? $request->id : 0;
			$story_type = $request->story_type;
			$rules = array(
				'story_type' => 'required',
				'link'  => 'required',
			);
			
			if($id > 0) {
				if($story_type == 1) {
					$rules['story'] = 'mimes:jpeg,jpg,png,gif,webp|max:10000';
				} else if($story_type == 2) {
					$rules['story'] = 'mimes:mp4,webm|max:10000';
				}
			} else {
				$rules['time'] = 'required';
				if($story_type == 1) {
					$rules['story'] = 'required|mimes:jpeg,jpg,png,gif,webp|max:10000';
				} else if($story_type == 2) {
					$rules['story'] = 'required|mimes:mp4,webm|max:10000';
				}
			}
			
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['status' => 'errors', 'errors' => $validator->getMessageBag()->toArray()]);
			}
			
			$params = ['time' => $request->time, 'story_type' => $request->story_type, 'link'=> $request->link, 'status' => 1];
			
			$commonManagerObj = CommonManager::getInstance();
			
			if($id > 0) {
				$id = $commonManagerObj->updateStoryById($id, $params);
				if($story_type == 1) {
					$status = $this->uploadStoryImage($request, $id);
		
					if($status === false) {
						//$commonManagerObj->storyDeleteById($id);
						return response()->json(['status' => 'error', 'msg' => 'Image not uploaded']);
					}
					return response()->json(['status' => 'success', 'msg' => 'Successfully save']);
				} else if($story_type == 2) {
					$status = $this->uploadStoryVideo($request, $id);
		
					if($status === false) {
						
						//$commonManagerObj->storyDeleteById($id);
						return response()->json(['status' => 'error', 'msg' => 'Video not uploaded']);
					}
					return response()->json(['status' => 'success', 'msg' => 'Successfully save']);
				} else if($story_type == 3) {
					return response()->json(['status' => 'success', 'msg' => 'Successfully save']);
				}
			} else {
				$id = $commonManagerObj->insert_story($params, true);
				if($id > 0) {
					if($story_type == 1) {
						$status = $this->uploadStoryImage($request, $id);
			
						if($status === false) {
							$commonManagerObj->storyDeleteById($id);
							return response()->json(['status' => 'error', 'msg' => 'Image not uploaded']);
						}
						return response()->json(['status' => 'success', 'msg' => 'Successfully save']);
					} else if($story_type == 2) {
						$status = $this->uploadStoryVideo($request, $id);
			
						if($status === false) {
							
							$commonManagerObj->storyDeleteById($id);
							return response()->json(['status' => 'error', 'msg' => 'Video not uploaded']);
						}
						return response()->json(['status' => 'success', 'msg' => 'Successfully save']);
					} else if($story_type == 3) {
						return response()->json(['status' => 'success', 'msg' => 'Successfully save']);
					}
					
				}
			}
			
			
			/* $commonManagerObj = CommonManager::getInstance();
			if($id > 0) {
				
				$status = $commonManagerObj->updateStoryById($id, $params);
				if($status) {
					return response()->json(['status' => 'success', 'msg' => 'Successfully Updated']);
				}
				
			} else {
				
				$status = $commonManagerObj->insert_story($params);
				if($status) {
					return response()->json(['status' => 'success', 'msg' => 'Successfully Save']);
				}
			} */
			
			return response()->json(['status' => 'error', 'msg' => 'Please try again']);
		}
	}
	
	private function uploadStoryImage($request, $id) {
		$params = $this->uploadImage($request, 'story', 'stories');

		if(count($params) > 0) {
			$commonManagerObj = CommonManager::getInstance();
			$status = $commonManagerObj->updateStoryById($id, ['story' => $params['story']]);
			if($status) {
				return true;
			}
		}
		return false;
	}
	
	private function uploadStoryVideo($request, $id) {
		$params = $this->uploadVideo($request, 'story', "stories", true);

		if(count($params) > 0) {
			$commonManagerObj = CommonManager::getInstance();
			$status = $commonManagerObj->updateStoryById($id, ['story' => $params['story']]);

			if($status) {
				return true;
			}
		}
		
		return false;
		
	}
	/* Story End */
	
}
