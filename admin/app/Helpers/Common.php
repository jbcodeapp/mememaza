<?php
use Cache as storeCache;
/* Start Redis */
function setRedis($key, $data) {
	//$redis = \Redis::connection();
	//$redis->set($key, json_encode($data));
}
function getRedis($key) {
	//$redis = \Redis::connection();
	//$response = $redis->get($key);
	//dd($response);
	//return json_decode($response);
}
function deleteRedis($key) {
	//$redis = \Redis::connection();
	//$redis->del($key);
}
/* End Redis */

/* Start Cashe */
function deleteCache($key)
{
	if(IS_CASHE === false)
	{
		return IS_CASHE;
	}
	storeCache::forget($key);
}
function setCacheData($key, $data, $time)
{
	if(IS_CASHE === false)
	{
		return $data;
	}
	$sdata = storeCache::remember($key, $time, function () use ($data) {
		return $data;
	});
	return $sdata;
}
function getCacheData($key)
{
	if(IS_CASHE === false)
	{
		return IS_CASHE;
	}
	if (storeCache::has($key))
	{
		return storeCache::get($key);
	}
	return false;
}
/* End Cashe */


// global CDN link helper function
function cdn( $asset ){

    // Verify if KeyCDN URLs are present in the config file
    if( !Config::get('app.cdn') )
        return asset($asset);

    // Get file name incl extension and CDN URLs
    $cdns = Config::get('app.cdn');
    $assetName = basename( $asset );

    // Remove query string
    $assetName = explode("?", $assetName);
    $assetName = $assetName[0];

    // Select the CDN URL based on the extension
    foreach( $cdns as $cdn => $types ) {
        if( preg_match('/^.*\.(' . $types . ')$/i', $assetName) )
            return cdnPath($cdn, $asset);
    }

    // In case of no match use the last in the array
    end($cdns);
    return cdnPath( key( $cdns ) , $asset);

}

function cdnPath($cdn, $asset) {
    return  "//" . rtrim($cdn, "/") . "/" . ltrim( $asset, "/");
}

/* function get_category_file_path($file, $folder)
{
	switch($folder) {
		case FILE_PRODUCT_HOME_LIST:
			return cdn('stroage/images/category/home/'.$file);
			return 'home';
		break;
		
		case FILE_PRODUCT_HOME_BIG_LIST:
			return cdn('stroage/images/category/homebig/'.$file);
			return 'homebig';
		break;
		
		case FILE_MINI_THUMBNAIL_TYPE:
			return cdn('stroage/images/category/homebig/'.$file);
			return 'homebig';
		break;
		
		case FILE_ORIGINAL:
			return cdn('stroage/images/category/original/'.$file);
			return 'original';
		break;
		
		case CATEGORY_BANNER:
			return cdn('stroage/images/category/'.$file);
			return 'original';
		break;
	}
} */

function set_status($status) {
	$message = match ($status) {
		1 => 'Active',
		2 => 'InActive',
		3 => 'Blocked',
		4 => 'Delete',
		5 => 'Pending',
		6 => 'Successful',
		7 => 'Fail',
		8 => 'User Cancel',
		9 => 'Return',
		10 => 'Delivery',
		11 => 'Payment Failed',
		12 => 'Bounced',
		13 => 'User Dropped',
		default => 'unknown status code',
	};
	
	return $message;
}

function get_product_file_path($product_id, $file, $type)
{
		//https://stitcher.io/blog/php-8-match-or-switch
	$path = 'products/'.$product_id.'/';
	$imageManagerObj = \App\Components\Admin\ImageManager::getInstance();
	$name = $imageManagerObj->getImageTypeDir($type);
	switch($type) {
		case FILE_PRODUCT_HOME_LIST:
			return cdn($path.$name.'_'.$file);
		break;
		
		case FILE_PRODUCT_DETAIL:
			return cdn($path.$name.'_'.$file);
		break;
		
		case FILE_PRODUCT_CAROUSEL:
			return cdn($path.$name.'_'.$file);
		break;
		
		case FILE_PRODUCT_CART:
			return cdn($path.$name.'_'.$file);
		break;
		
		case FILE_ORIGINAL:
			return cdn($path.$name.'_'.$file);
		break;
	}
}