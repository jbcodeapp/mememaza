<?php
 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Components\Api\CommonManager;
use Illuminate\Http\Request;
use Validator, Auth, DB;
//use Illuminate\Support\Str;
class IndexController extends Controller
{
	/* private function generateSlug($id, $title) {
		$slug = Str::slug($title);
		DB::table('posts')->where('id', $id)->update(['slug' => $slug]);
	} */
	public function stories() {
		$currentDate = (new \DateTime)->format('Y-m-d H:i:s');
		$stories = DB::table('stories')->select(['id', 'story', 'link', 'story_type', 'created_at'])->where('status', 1)->whereDate('time', '>', $currentDate)->get();
		foreach($stories as $story) {
			$story->image_path = cdn($story->story);
		}
		
		return response()->json(['statuscode'=>true, 'stories' => $stories], 200);
	}
    public function index(Request $request, $slug=null) {
		$userid = null;
		if (Auth()->guard('api')->check()) {
			$userid =  auth('api')->user()->id;
		}
		$obj = CommonManager::getInstance();
		$page = ($request->page > 0) ?  $request->page : 1;
		$limit = 10;
		
		$path=cdn('');
		$postcol = [
				'posts.id', 'categories.name as category', 'posts.desc', 'posts.slug', 'posts.title', 'posts.created_at', DB::raw('CONCAT("' . $path . '","",posts.image) as image_path'),
				'posts.image', 'posts.like', 'posts.view', 'posts.share', 'posts.comment'
				];
		
		$path=cdn(PUB."story/");
		$currentDate = (new \DateTime)->format('Y-m-d H:i:s');
		/* $stories = DB::table('stories')->select(['id', 'story', 'link', 'story_type', 'created_at'])->where('status', 1)->whereDate('time', '>', $currentDate)->get();
		foreach($stories as $story) {
			$story->image_path = cdn($story->story);
		} */
		
		$categories = $obj->getCategories(['categories.id', 'categories.name', 'categories.slug', 'categories.image', 'categories.created_at']);
		foreach($categories as $category) {
			$category->image_path = cdn($category->image);
		} 
		$post = $this->postData($page, $limit, $slug, $postcol);
		
		$postlist  = $post['data'];
		foreach($post['data'] as $postobj) {
			//$this->generateSlug($postobj->id, $postobj->title);
			
			$postobj->likestatus = 0;
			if($userid != null) {
				$postobj->likestatus = $obj->getLikeCountById($userid, $postobj->id, 1);
			}
		}
		
		$reels = DB::table('reels')->select(['id', 'slug', 'reel', 'link', 'thumb', 'reel_type', 'created_at'])->where('status', 1)->orderBy('id', 'desc')->get();
		$reelsData = [];
		
		foreach($reels as $reel) {
			$reelspath=$reel->link;
			$thumb = '';
			if($reel->reel_type == 2) {
				$reelspath=cdn($reel->link);
				$thumb = cdn($reel->thumb);
			} else if($reel->reel_type == 3) {
				$reelspath=cdn($reel->link);
			}
			$reelsData[] = [
							'id' => $reel->id,
							'reel_type' => $reel->reel_type,
							'name' => $reel->reel,
							'link' => $reelspath,
							'thumb' => $thumb,
							'slug' => $reel->slug,
							'created_at' => $reel->created_at
							];
		}

		return response()->json(['statuscode'=>true, 'userid' => $userid,
		'page' => $page, 'slug' => $slug, 'postcount' => $post['count'],
		'categories' => $categories, 'post' => $postlist, 'reels' => $reelsData], 200);
	}

	/* public function post(Request $request, $slug=null) {
		
		$page = ($request->page > 0) ?  $request->page : 1;
		$limit = 3;
		$path=cdn(PUB."uploads/post");
		$col = [
				'posts.id', 'categories.name as category', 'posts.desc', 'posts.title', DB::raw('CONCAT("' . $path . '/","",posts.image) as image_path'),
				'posts.image', 'posts.like', 'posts.view', 'posts.share', 'posts.comment'
				];
		
		return response()->json(['statuscode'=>true, 'post' => $this->postData($page, $limit, $slug, $col)], 200);
	} */
	
	private function postData($page, $limit, $slug, $col) {
		
		$commonManagerObj = CommonManager::getInstance();
		return $post = $commonManagerObj->getPostsLimit($page, $limit, $slug, $col);
		
	}
	
	public function search(Request $request, $search) {
		$params = [];
		$params[] = ['id' => 0, 'name' => 'Cobol'];
		$params[] = ['id' => 1, 'name' => 'JavaScript'];
		$params[] = ['id' => 2, 'name' => 'Basic'];
		$params[] = ['id' => 3, 'name' => 'PHP'];
		$params[] = ['id' => 4, 'name' => 'Java'];
		//return response()->json($params);
		
		$data = DB::table('posts')->select('id', 'title as name', 'slug')->where('title', 'LIKE', '%'.$search.'%')->limit(10)->get();
		
		return response()->json($data);
		
	}
	
	public function detail(Request $request, $id) {
		
		$ip = $request->ip();
		
		$userid = null;
		if (Auth()->guard('api')->check()) {
			  $userid =  auth('api')->user()->id;
		}
		$checkobj = DB::table('post_views')->where('ip', $ip)->where('post_id', $id)->first();
		if($checkobj == null) {
			$date = date('Y-m-d H:i:s');
			if(DB::table('post_views')->insert(['ip' => $ip, 'post_id' => $id, 'created_at' => $date, 'updated_at' => $date])) {
				DB::table('posts')->where('id', $id)->increment('view');
			}
		}
		
		$obj = CommonManager::getInstance();
		$path=cdn(PUB."uploads/post");
		$post = $obj->getPostById($id, ['id', 'title', 'image', 'like', 'view', 'share', 'comment', 'desc', DB::raw('CONCAT("' . $path . '/","",image) as image_path')]);
		
		$post->comments = [];
		$post->isAuth = $userid;
		
		$page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 100;
		$nextpage = $count = 0;
		if($post) {
			//$post->comments = $obj->getPostCommentByAttr($post->id, 1);
			
			$commentCollection = DB::table('comments')->select(['*'])->where('type_id', $post->id)->where('type', 1);
			$count = $commentCollection->get()->count();
			$no = ($page*$limit);
			
			if($no < $count) {
				
				$nextpage = ($page + 1);
			}
			$list = $commentCollection->orderBy('id', 'desc')->limit($limit)->offset(($page - 1) * $limit)->get()->toArray();
			
			$post->comments = $list;
		}

		return response()->json(['id' => $id, 'obj' => $post, 'nextpage' => $nextpage, 'count' => $count]);
	}
	
	public function loadcomment(Request $request, $postid, $pageid) {
		$page = $pageid;
        $limit = 100;
		$nextpage = $count = 0;
		
		$commentCollection = DB::table('comments')->select(['*'])->where('type_id', $postid)->where('type', 1);
		$count = $commentCollection->get()->count();
		$no = ($page*$limit);
		
		if($no < $count) {
			
			$nextpage = ($page + 1);
		}
		$list = $commentCollection->orderBy('id', 'desc')->limit($limit)->offset(($page - 1) * $limit)->get()->toArray();
		$data = $list;
		return response()->json(['nextpage' => $nextpage, 'comments' => $data]);
	}
	
	public function postbyslug(Request $request, $slug) {
		
		return response()->json(['obj' => DB::table('posts')->where('slug', $slug)->first()]);
	}
	
	public function reelbyslug(Request $request, $slug) {
		return response()->json(['obj' => DB::table('reels')->where('slug', $slug)->first()]);
	}
	
	public function postdownload(Request $request, $slug) {
		
		return response()->json(['download' => DB::table('posts')->select('download')->where('slug', $slug)->first()->download]);
	}
	
	public function postshare(Request $request, $slug) {
		return response()->json(['share' => DB::table('posts')->select('share')->where('slug', $slug)->first()->share]);
	}
	
	public function postview(Request $request, $slug) {
		return response()->json(['view' => DB::table('posts')->select('view')->where('slug', $slug)->first()->view]);
	}
	
	public function postlike(Request $request, $slug) {
		return response()->json(['like' => DB::table('posts')->select('like')->where('slug', $slug)->first()->like]);
	}
	
	public function postcomment(Request $request, $slug) {
		return response()->json(['comment' => DB::table('posts')->select('comment')->where('slug', $slug)->first()->comment]);
	}
	
	public function updatedownload(Request $request) {
		$postid = $request->id;
		$post = DB::table('posts')->select('download')->whereid($postid);
		if($post->first() == null) {
			return response()->json(['status' => 'error', 'message' => 'Post not found']);
		}
		$post->increment('download');
		return response()->json(['status' => 'success', 'download' => $post->first()->download]);
	}
	
	public function updateshare(Request $request) {
		$postid = $request->id;
		$post = DB::table('posts')->select('share')->whereid($postid);
		if($post->first() == null) {
			return response()->json(['status' => 'error', 'message' => 'Post not found']);
		}
		$post->increment('share');
		return response()->json(['status' => 'success', 'share' => $post->first()->share]);
	}
	
	public function updatelike(Request $request) {
		$postid = $request->id;
		$post = DB::table('posts')->select('like')->whereid($postid);
		if($post->first() == null) {
			return response()->json(['status' => 'error', 'message' => 'Post not found']);
		}
		$post->increment('like');
		return response()->json(['status' => 'success', 'like' => $post->first()->like]);
	}
	
	public function updatedislike(Request $request) {
		$postid = $request->id;
		$post = DB::table('posts')->select('like')->whereid($postid);
		if($post->first() == null) {
			return response()->json(['status' => 'error', 'message' => 'Post not found']);
		} else {
			if($post->first()->like == 0) {
				return response()->json(['status' => 'success', 'like' => 0]);
			}
		}
		$post->decrement('like');
		return response()->json(['status' => 'success', 'like' => $post->first()->like]);
	}
}
