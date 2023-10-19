<?php
namespace App\Components\Api;
use DB, stdClass, Log;

use App\Models\Category;

use App\Models\Post;

class CommonManager {
    private static $instance = null;
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new CommonManager();
		}
		return self::$instance;
	}
	
	public function getCategories($col = ['*']) {
		return Category::select($col)->withCount(['reels', 'posts'])->get();
	}
	
	public function getPostsLimit($page, $limit, $slug = null) {
		// Create a base query using the Post model
		$query = Post::with([
				'category' => function($query) {
					$query->select(['id', 'name', 'banner_image']);
				}
			])
			->with(['likes.liker' => function($query) {
				$query->select('id');
			}])
			->with('comments.commenter')
			->with(['views.viewer' => function($query) {
				$query->select(['id', 'name']);
			}])
			->with(['shares.sharer' => function($query) {
				$query->select(['id', 'name']);
			}])
			->withCount(['shares', 'likes', 'views', 'comments'])
			->whereHas('category', function ($query) {
				$query->where('status', 1);
			})
			->where('status', 1);
	
		// Apply slug filter if provided
		if ($slug !== null) {
			$query->where('slug', $slug);
		}
	
		// Paginate the results
		$list = $query->orderBy('id', 'asc')
			->paginate($limit, $page);
	
		return ['count' => $list->count(), 'data' => $list->items()];
	}
	
	public function getLikeCountById($user_id, $type_id, $type, $col = ['*'])
	{
		return DB::table('likes')->select($col)->where('user_id', $user_id)->where('type_id', $type_id)->where('type', $type)->count();
	}
	
	public function getLikeById($user_id, $type_id, $type, $col = ['*'])
	{
		return DB::table('likes')->select($col)->where('user_id', $user_id)->where('type_id', $type_id)->where('type', $type)->first();
	}
	
	public function postLikeIncrementById($id)
	{
		$post = DB::table('posts')->select('like')->whereid($id);
		
		$post->increment('like');

		return $post->first()->like;
		
		return DB::table('posts')->whereid($id)->increment('like');
	}
	
	public function saveLike($user_id, $type_id, $type) {
		return DB::table('likes')->insert(['user_id' => $user_id, 'type_id' => $type_id , 'type' => $type, 'like' => 1]);
	}
	
	public function handleLikeStatus($userid, $type_id, $type)
	{
		$obj = $this->getLikeById($userid, $type_id, $type, ['like']);
		if($obj) {
			
			return 0;
		
		} else if($this->saveLike($userid, $type_id, $type)) {
			if($type == 1) { //post
			
				return $this->postLikeIncrementById($type_id);
			
				if($this->postLikeIncrementById($type_id)) {
					
				}
			}
			
		}
		
		return 0;
	}
	
	public function getPostById($id, $col = ['*']) {
		return DB::table('posts')->select($col)->where('id', $id)->first();
	}
	
	public function getPostCommentByAttr($type_id, $type, $col = ['*']) {
		return DB::table('comments')->select($col)->where('type_id', $type_id)->where('type', $type)->orderBy('id', 'desc')->paginate(3)->toArray();
		//->limit(5)->offset(1)->get()
	}
}
