<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body',  'category_id',  'excerpt', 'slug'];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function category()
    {
    	return $this->belongsTo(Category::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeWithOrder($query,$order)
    {
    	switch ($order) {
    		case 'recent':
    			$query = $this->recent();
    			break;
    		
    		default:
 				$query = $this->recentReplied();
    			break;
    	}
    	# 防止预加载
    	return $query->with('user','category');
    }

    public function scopeRecent($query)
    {
    	#  最新发布
    	return $query->orderBy('created_at','desc');
    }

    public function scopeRecentReplied($query)
    {
    	# 最新回复
    	return $query->orderBy('updated_at','desc');
    }

    public function link($params=[])
    {
        return route('topics.show',array_merge([$this->id , $this->slug],$params));
    }

}
