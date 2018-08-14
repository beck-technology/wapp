<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function account()
	{
		return $this->belongsTo('App\Account');
	}
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	public function company()
	{
		return $this->belongsTo('App\Company');
	}
	public function scopeFilter($query, $params) {
		$locationFilter = $query;
		if (isset($params['query'])) {
			foreach (['address', 'post_code', 'phone_number'] as $column)
	        {
				$locationFilter->orWhere($column,'like' ,"%{$params['query']}%");
	        }
		}
		if (isset($params['account_name'])) {
			$locationFilter->whereHas('account', function ($q) use($params) {
				$q->where('name', 'like', "'%". $params['account_name'] ."%'");
			});
		}
		if (isset($params['name'])) {
			$locationFilter->where('name', 'like', "%{$params['name']}%");
		}
		if (isset($params['website'])) {
			$locationFilter->where('website', 'like', "%{$params['website']}%");
		}
		if (isset($params['address'])) {
			$locationFilter->where('address', 'like', "%{$params['address']}%");
		}
		if (isset($params['post_code'])) {
			$locationFilter->where('post_code', 'like', "%{$params['post_code']}%");
		}
		if (isset($params['type'])) {
			$locationFilter->where('type', 'like', "%{$params['type']}%");
		}
		if (isset($params['phone_number'])) {
			$locationFilter->where('phone_number', 'like', "%{$params['phone_number']}%");
		}
 		return $locationFilter;
	}
}
