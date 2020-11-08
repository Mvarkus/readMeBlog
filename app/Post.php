<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pipeline\Pipeline;

use App\QueryFilters\Post\ {
    Category as CategoryFilter,
    Order as OrderFilter
};

class Post extends Model
{
    protected $guarded = [];
    protected $with = ['user', 'category'];

    public function specificResourcePath(string $prefix = '')
    {
        return $prefix . '/posts/' . $this->id;
    }

    public function deleteImageFile()
    {
        $storage = env('APP_ENV') === 'testing' ? 'test' : 'public';

        if ($exists = Storage::disk($storage)->exists($this->image)) {
            return Storage::disk($storage)->delete($this->image);
        }

        return false;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function userVotes()
    {
        return $this->hasMany('App\Vote');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public static function recentPosts(int $amount)
    {
        return self::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit($amount)
            ->get();
    }

    public static function mostPopular(int $amount)
    {
        return self::where('status', 1)
            ->orderBy('votes', 'desc')
            ->limit($amount)
            ->get();
    }

    public static function getFilteredPosts(array $data = [])
    {
        $validData = Post::validateFilterData($data);

        return app(Pipeline::class)
            ->send([
                'query' => Post::query(),
                'filters' => $validData
            ])
            ->through([
                CategoryFilter::class,
                OrderFilter::class
            ])
            ->thenReturn()['query'];
    }
    
    public static function validateFilterData(array $data)
    {
        $validData = [];

        if ($data['categories'] ?? false) {
            
            foreach($data['categories'] as $categoryId) {

                if (is_numeric($categoryId)) {
                    $validData['categories'][] = $categoryId;
                }

            }
        }

        if ($data['order'] ?? null) {

            if (in_array($data['order']['by'], self::allowedFieldsForOrder())) {
                $validData['order']['by'] = $data['order']['by'];
            } else {
                return $validData;
            }

            if (in_array($data['order']['type'], ['desc', 'asc'])) {
                $validData['order']['type'] = $data['order']['type'];
            } else {
                 $validData['order']['type'] = 'desc';
            }
            
        }

        return $validData;
    }

    private static function allowedFieldsForOrder()
    {
        return ['votes', 'created_at', 'user_id'];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return substr($this->created_at, 0, 10);
    }
}
