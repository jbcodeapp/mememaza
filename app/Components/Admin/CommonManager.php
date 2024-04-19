<?php
namespace App\Components\Admin;

use App\Components\Admin\ImageManager;
use DB, stdClass;
use App\Models\Post;

class CommonManager
{
	private static $instance = null;
	public static function getInstance()
	{
		if (self::$instance == null) {
			self::$instance = new CommonManager();
		}
		return self::$instance;
	}

	public function getCategoryByParentId($parent_id, $col = ['*'])
	{
		return DB::table('categories')->select($col)->where('parent_id', $parent_id)->get();
	}

	public function getCategoryById($id, $col = ['*'])
	{
		return DB::table('categories')->select($col)->where('id', $id)->first();
	}

	public function updateCategoryById($id, $params)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['updated_at'] = $current_date;
		return DB::table('categories')->where('id', $id)->update($params);
	}

	public function getCategories($col = ['*'])
	{
		return DB::table('categories')->select($col)->where('status', 1)->get();
	}

	public function insert_category($params, $id = false)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['created_at'] = $current_date;
		$params['updated_at'] = $current_date;
		if ($id === true) {
			$id = DB::table('categories')->insertGetId($params);
			deleteRedis('');
			return $id;
		}
		$status = DB::table('categories')->insert($params);

		if ($status) {
			deleteRedis('');
			return true;
		}

		return false;
	}

	public function getReelById($id, $col = ['*'])
	{
		return DB::table('reels')->select($col)->where('id', $id)->first();
	}

	public function insert_reel($params, $id = false)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['created_at'] = $current_date;
		$params['updated_at'] = $current_date;
		if ($id === true) {
			$id = DB::table('reels')->insertGetId($params);
			return $id;
		}
		$status = DB::table('reels')->insert($params);

		if ($status) {
			return true;
		}

		return false;
	}

	public function updateReelById($id, $params)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['updated_at'] = $current_date;
		return DB::table('reels')->where('id', $id)->update($params);
	}

	public function getPostById($id, $col = ['*'])
	{
		return DB::table('posts')->select($col)->where('id', $id)->first();
	}

	public function insert_post($params, $id = false)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['created_at'] = $current_date;
		$params['updated_at'] = $current_date;
		if ($id === true) {
			$id = DB::table('posts')->insertGetId($params);
			return $id;
		}

		$status = Post::create($params);

		if ($status) {
			return true;
		}

		return false;
	}

	public function updatePostById($id, $params)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['updated_at'] = $current_date;
		return DB::table('posts')->where('id', $id)->update($params);
	}

	public function getBannerById($id, $col = ['*'])
	{
		return DB::table('banners')->select($col)->where('id', $id)->first();
	}

	public function insert_banner($params, $id = false)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['created_at'] = $current_date;
		$params['updated_at'] = $current_date;
		if ($id === true) {
			$id = DB::table('banners')->insertGetId($params);
			return $id;
		}
		$status = DB::table('banners')->insert($params);

		if ($status) {
			return true;
		}

		return false;
	}

	public function updateBannerById($id, $params)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['updated_at'] = $current_date;
		return DB::table('banners')->where('id', $id)->update($params);
	}

	// advertisements
	public function getAdvertisementById($id, $col = ['*'])
	{
		return DB::table('advertisements')->select($col)->where('id', $id)->first();
	}

	public function insert_advertisements($params, $id = false)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['created_at'] = $current_date;
		$params['updated_at'] = $current_date;
		if ($id === true) {
			$id = DB::table('advertisements')->insertGetId($params);
			return $id;
		}
		$status = DB::table('advertisements')->insert($params);

		if ($status) {
			return true;
		}

		return false;
	}

	public function updateadvertisementsById($id, $params)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['updated_at'] = $current_date;
		return DB::table('advertisements')->where('id', $id)->update($params);
	}

	////////////////////

	public function getStoryById($id, $col = ['*'])
	{
		return DB::table('stories')->select($col)->where('id', $id)->first();
	}

	public function insert_story($params, $id = false)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['created_at'] = $current_date;
		$params['updated_at'] = $current_date;
		if ($id === true) {
			$id = DB::table('stories')->insertGetId($params);
			return $id;
		}
		$status = DB::table('stories')->insert($params);

		if ($status) {
			return true;
		}

		return false;
	}

	public function storyDeleteById($id)
	{
		return DB::table('stories')->where('id', $id)->delete();
	}

	public function updateStoryById($id, $params)
	{
		$current_date = date('Y-m-d H:i:s');
		$params['updated_at'] = $current_date;
		return DB::table('stories')->where('id', $id)->update($params);
	}
}
