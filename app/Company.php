<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function account(){
        return $this->belongsTo(Account::class);
    }    
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function location()
    {
        return $this->hasMany(Location::class);
    }
    public function scopeFilter($query, $params) {
		$companyFilter = $query;
		if (isset($params['query'])) {
			foreach (['name', 'website', 'address', 'post_code', 'type', 'phone_number'] as $column)
	        {
				$companyFilter->orWhere($column,'like' ,"%{$params['query']}%");
	        }
		}
		if (isset($params['account_name'])) {
			$companyFilter->whereHas('account', function ($q) use($params) {
				$q->where('name', 'like', "'%". $params['account_name'] ."%'");
			});
		}
		if (isset($params['name'])) {
			$companyFilter->where('name', 'like', "%{$params['name']}%");
		}
		if (isset($params['website'])) {
			$companyFilter->where('website', 'like', "%{$params['website']}%");
		}
		if (isset($params['address'])) {
			$companyFilter->where('address', 'like', "%{$params['address']}%");
		}
		if (isset($params['post_code'])) {
			$companyFilter->where('post_code', 'like', "%{$params['post_code']}%");
		}
		if (isset($params['type'])) {
			$companyFilter->where('type', 'like', "%{$params['type']}%");
		}
		if (isset($params['phone_number'])) {
			$companyFilter->where('phone_number', 'like', "%{$params['phone_number']}%");
		}
		return $companyFilter;
	}
}
