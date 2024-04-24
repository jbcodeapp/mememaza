<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Components\Api\CommonManager;
use App\Models\PostReelIndex;
use App\Models\Reel;
use App\Models\Story;
use Illuminate\Http\Request;
use DB;
use App\Models\Banner;
use App\Models\Post;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Support\Str;
class IndexController extends Controller
{
	private $postsLimit = 10;

	public function stories()
	{
		$currentDate = (new \DateTime)->format('Y-m-d H:i:s');
		$stories = DB::table('stories')->select(['id', 'story', 'link', 'story_type', 'created_at'])->where('status', 1)->whereDate('time', '>', $currentDate)->get();
		foreach ($stories as $story) {
			$story->image_path = cdn($story->story);
		}

		return response()->json(['statuscode' => true, 'stories' => $stories], 200);
	}

	public function getPaginatedPosts(Request $request)
	{
		$page = ($request->page > 0) ? $request->page : 1;
		$categorySlug = $request->category_slug;
		$search = $request->search ?? null;

		$postBaseQuery = Post::select(['id', 'category_id'])->where('status', 1);

		$reelBaseQuery = Reel::select(['id', 'category_id'])->where('status', 1);

		$totalItems = $postBaseQuery
			->whereHas('category', function ($query) use ($categorySlug) {
				$query->where('status', 1);
				if ($categorySlug) {
					$query->whereSlug($categorySlug);
				}
			})
			->union(
				$reelBaseQuery
					->whereHas('category', function ($query) use ($categorySlug) {
						$query->where('status', 1);
						if ($categorySlug) {
							$query->whereSlug($categorySlug);
						}
					})
			)
			->count();

		$commonManagerObj = CommonManager::getInstance();

		$post = $commonManagerObj->getPostsLimit($page, $this->postsLimit, null, $categorySlug, $search);

		$postlist = $post['data'];
		$reels = $post['reels'];

		// Combine the posts and reels into a single array
		$combinedData = array_merge($postlist, $reels);

		// Create a new array to store the sorted data
		$sortedData = collect($combinedData)->sortBy('created_at', null, true)->values();

		return response()->json(['posts' => $sortedData, 'total' => $totalItems, 'limit' => $this->postsLimit, 'page' => $page], 200);
	}

	public function index(Request $request, $slug = null)
	{
		$userid = null;
		if (Auth()->guard('api')->check()) {
			$userid = auth('api')->user()->id;
		}
		$obj = CommonManager::getInstance();

		$categories = $obj->getCategories(['categories.id', 'categories.name', 'categories.slug', 'categories.image', 'categories.created_at']);

		foreach ($categories as $category) {
			$category->image_path = $category->image;
		}

		$stories = Story::where('status', 1)
			->orderByDesc('created_at')

			->get();

		$reels = DB::table('reels')
			->select(['id', 'slug', 'reel', 'link', 'thumb', 'reel_type', 'created_at'])
			->where('status', 1)
			->orderBy('id', 'desc')
			->get();

		$banners = Banner::where('status', 1)->get();

		$reelsData = [];

		foreach ($reels as $reel) {
			$reelspath = $reel->link;
			$thumb = '';
			if ($reel->reel_type == 2) {
				$reelspath = $reel->link;
				$thumb = $reel->thumb;
			} else if ($reel->reel_type == 3) {
				$reelspath = $reel->link;
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

		return response()->json([
			'statuscode' => true,
			'userid' => $userid,
			'slug' => $slug,
			'stories' => $stories,
			'banners' => $banners,
			'categories' => $categories,
			'reels' => $reelsData
		], 200);
	}

	public function search(Request $request, $search)
	{
		// $params = [];
		// $params[] = ['id' => 0, 'name' => 'Cobol'];
		// $params[] = ['id' => 1, 'name' => 'JavaScript'];
		// $params[] = ['id' => 2, 'name' => 'Basic'];
		// $params[] = ['id' => 3, 'name' => 'PHP'];
		// $params[] = ['id' => 4, 'name' => 'Java'];
		//return response()->json($params);

		// $data = DB::table('posts')->select('id', 'title as name', 'slug')->where('title', 'LIKE', '%' . $search . '%')->orWhere('category_id', 'LIKE', '%' . $search . '%')->orWhere('meta_keyword', 'LIKE', '%' . $search . '%')->limit(10)->get();
		
		$data = DB::table('posts')
		->select('posts.*')
		->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
		->where(function($query) use ($search) {
			$query->where('posts.title', 'LIKE', '%' . $search . '%')
				  ->orWhere('posts.meta_keyword', 'LIKE', '%' . $search . '%')
				  ->orWhere('categories.name', 'LIKE', '%' . $search . '%');
		})
		->get();
		// dd($data);
		return response()->json($data);

	}

	public function detail(Request $request, $id)
	{

		$ip = $request->ip();

		$userid = null;
		if (Auth()->guard('api')->check()) {
			$userid = auth('api')->user()->id;
		}
		$checkobj = DB::table('post_views')->where('ip', $ip)->where('post_id', $id)->first();
		if ($checkobj == null) {
			$date = date('Y-m-d H:i:s');
			if (DB::table('post_views')->insert(['ip' => $ip, 'post_id' => $id, 'created_at' => $date, 'updated_at' => $date])) {
				DB::table('posts')->where('id', $id)->increment('view');
			}
		}

		$obj = CommonManager::getInstance();
		$path = cdn(PUB . "uploads/post");
		$post = $obj->getPostById($id, ['id', 'title', 'image', 'like', 'view', 'share', 'comment', 'desc', DB::raw('CONCAT("' . $path . '/","",image) as image_path')]);

		$post->comments = [];
		$post->isAuth = $userid;

		$page = $request->has('page') ? $request->get('page') : 1;
		$limit = $request->has('limit') ? $request->get('limit') : 100;
		$nextpage = $count = 0;
		if ($post) {
			//$post->comments = $obj->getPostCommentByAttr($post->id, 1);

			$commentCollection = DB::table('comments')->select(['*'])->where('type_id', $post->id)->where('type', 1);
			$count = $commentCollection->get()->count();
			$no = ($page * $limit);

			if ($no < $count) {

				$nextpage = ($page + 1);
			}
			$list = $commentCollection->orderBy('id', 'desc')->limit($limit)->offset(($page - 1) * $limit)->get()->toArray();

			$post->comments = $list;
		}

		return response()->json(['id' => $id, 'obj' => $post, 'nextpage' => $nextpage, 'count' => $count]);
	}

	public function loadcomment(Request $request, $postid, $pageid)
	{
		$page = $pageid;
		$limit = 100;
		$nextpage = $count = 0;

		$commentCollection = DB::table('comments')->select(['*'])->where('type_id', $postid)->where('type', 1);
		$count = $commentCollection->get()->count();
		$no = ($page * $limit);

		if ($no < $count) {

			$nextpage = ($page + 1);
		}
		$list = $commentCollection->orderBy('id', 'desc')->limit($limit)->offset(($page - 1) * $limit)->get()->toArray();
		$data = $list;
		return response()->json(['nextpage' => $nextpage, 'comments' => $data]);
	}

	public function getSortedData($page, $limit)
	{
		$postlist = Post::select(['id', 'slug', 'created_at'])
			->orderByDesc('created_at')
			->paginate($limit, $page)
			->items();

		$reels = Reel::select(['id', 'slug', 'created_at'])
			->orderByDesc('created_at')
			->paginate($limit, $page)
			->items();

		// Combine the posts and reels into a single array
		$combinedData = array_merge($postlist, $reels);

		// Create a new array to store the sorted data
		return collect($combinedData)
			->sortBy('created_at', null, true)
			->values()
			->toArray();
	}

	public function postbyslug(Request $request, $slug, $type)
	{
		$page = $request->page ? $request->page : 1;
		$modalName = '';

		$searchFor = 'slug';
		if ($type === 'reel') {
			$modelName = '\App\Models\Reel';
		} else if ($type === 'post') {
			$modelName = '\App\Models\Post';
		} else if ($type === 'story') {
			$modelName = '\App\Models\Story';
			$searchFor = 'id';
		}

		// Create a new array to store the sorted data
		$sortedData = $this->getSortedData($page, 200);

		$previousSlug = null;
		$nextSlug = null;
		$postOrReelQry = $modelName::with([
			'likes.liker' => function ($query) {
				$query->select('id');
			}
		])
			->with('comments.commenter')
			->with([
				'views.viewer' => function ($query) {
					$query->select(['id', 'name']);
				}
			])
			->with([
				'shares.sharer' => function ($query) {
					$query->select(['id', 'name']);
				}
			])
			->withCount(['shares', 'likes', 'views', 'comments'])
			->where('status', 1)->where($searchFor, $slug);

		if ($type !== 'story') {
			$postOrReelQry->whereHas('category', function ($query) {
				$query->where('status', 1);
			});
		}
		$postOrReel = $postOrReelQry->first();
		$id = $postOrReel->id;
		$ip = $request->ip();

		$checkQry = DB::table('views')
			->where('ip', $ip)
			->where('type_id', $id)
			->where('type', $type);

		if (auth()->user()) {
			$checkQry->where('user_id', auth()->user()->id);
		}
		$checkobj = $checkQry->first();

		if ($checkobj == null) {
			$date = date('Y-m-d H:i:s');

			DB::table('views')->insert([
				'ip' => $ip,
				'user_id' => auth()->user() ? auth()->user()->id : null,
				'type' => $type,
				'type_id' => $id,
				'created_at' => $date,
				'updated_at' => $date
			]);
		}
		if ($type !== 'story') {
			
			
			foreach ($sortedData as $i => $item) {
			
				if ($item['slug'] === $slug && $item['type'] === $type) {

					// Create a new array to store the sorted data
					$currentItem = PostReelIndex::where($type . "_id", $id)
						->first();

					$previousItem = PostReelIndex::where('created_at', '>', $currentItem->created_at)
						->with(['post', 'reel'])->orderBy('created_at')
						->first();

					$nextItem = PostReelIndex::where('created_at', '<', $currentItem->created_at)
						->with(['post', 'reel'])->orderByDesc('created_at')
						->first();

				}
			}
		} else {
			$currentStory = $postOrReel;
			$previousItem = Story::where('created_at', '>', $currentStory->created_at)
				->orderBy('created_at')
				->first();

			$nextItem = Story::where('created_at', '<', $currentStory->created_at)
				->orderByDesc('created_at')
				->first();
		}
		if ($previousItem) {
			if ($previousItem['reel']) {
				$prevType = 'reel';
				$prevSlug = $previousItem[$prevType]['slug'];
			} else if ($previousItem['post']) {
				$prevType = 'post';
				$prevSlug = $previousItem[$prevType]['slug'];
			} else {
				$prevType = 'story';
				$prevSlug = $previousItem['id'];
			}
		}

		if ($nextItem) {
			if ($nextItem['reel']) {
				$nextType = 'reel';
				$nxtSlug = $nextItem[$nextType]['slug'];
			} else if ($nextItem['post']) {
				$nextType = 'post';
				$nxtSlug = $nextItem[$nextType]['slug'];
			} else {
				$nextType = 'story';
				$nxtSlug = $nextItem['id'];
			}
		}

		if ($previousItem) {
			$previousSlug = '/' . $prevType . '/' . $prevSlug;
		}
		if ($nextItem) {
			$nextSlug = '/' . $nextType . '/' . $nxtSlug;
		}
		return response()->json(['obj' => $postOrReel, 'previous' => $previousSlug, 'next' => $nextSlug]);
	}
	
	public function getAdvertisements(Request $request)
	{
		$advertisements = DB::table('advertisements')->inRandomOrder()->limit(1)->get();

		return  response()->json($advertisements);
	}

	public function reelbyslug(Request $request, $slug)
	{
		return response()->json(['obj' => DB::table('reels')->where('slug', $slug)->first()]);
	}

	public function postdownload(Request $request, $slug)
	{

		return response()->json(['download' => DB::table('posts')->select('download')->where('slug', $slug)->first()->download]);
	}

	public function postshare(Request $request, $slug)
	{
		return response()->json(['share' => DB::table('posts')->select('share')->where('slug', $slug)->first()->share]);
	}

	public function postview(Request $request, $slug)
	{
		return response()->json(['view' => DB::table('posts')->select('view')->where('slug', $slug)->first()->view]);
	}

	public function postlike(Request $request, $slug)
	{
		return response()->json(['like' => DB::table('posts')->select('like')->where('slug', $slug)->first()->like]);
	}

	public function postcomment(Request $request, $slug)
	{
		return response()->json(['comment' => DB::table('posts')->select('comment')->where('slug', $slug)->first()->comment]);
	}

	public function updateshare(Request $request)
	{
		$postid = $request->id;
		$post = DB::table('posts')->select('share')->whereid($postid);
		if ($post->first() == null) {
			return response()->json(['status' => 'error', 'message' => 'Post not found']);
		}
		$post->increment('share');
		return response()->json(['status' => 'success', 'share' => $post->first()->share]);
	}

	public function updatelike(Request $request)
	{
		$modelType = "App\Models\\" . $request->type;

		$model = new $modelType;

		$item = $model::find($request->id);
		if ($item) {
			// check if already liked
			$preExistingLike = $item->likes()->where('user_id', auth()->user()->id)->first();

			if ($preExistingLike) {
				$preExistingLike->delete();
				return response()->json(['status' => 'success']);
			}
			try {
				$item->like();
				return response()->json(['status' => 'success']);
			} catch (\Exception $e) {
				return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
			}
		} else {
			abort(400);
		}
	}
}
