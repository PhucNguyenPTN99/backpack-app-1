<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\ImageManagerStatic as Images;


class Program extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'academy_programs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fakeColumns = ['meta', 'articles'];
    protected $fillable = ['id', 'meta', 'subtitle', 'name', 'slug', 'description', 'banner_image', 'logo_image', 'about_banner','about_infos', 'articles', 'status' ];
    protected $casts = [
    'meta' => 'array',
    'articles' => 'array',
    ];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            Storage::delete(Str::replaceFirst('storage/','public/', $obj->image));
        });
    }

    public function articles()
	{
		return $this->belongsToMany('App\Models\Article', 'articles', 'id');
	}

    public static function articleModule($empty = false)
    {
        $articles= article::with('articles')->get()->mapWithKeys(function ($article) {

			$article_id = $article->id;
			$article_name = $article->name;
			return [$article_id => $article_name];
        });
        if ($empty) {
            $articles = collect([0 => '-'])->union($articles);
        }
        var_dump($articles);
        return $articles->all();

    }

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        // destination path relative to the disk above
        $destination_path = "public/articles";

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            Storage::delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = Images::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';

            // 2. Store the image on disk.
            Storage::put($destination_path.'/'.$filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            Storage::delete(Str::replaceFirst('storage/','public/', $this->{$attribute_name}));

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = Str::replaceFirst('public/', 'storage/', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
        }
    }
}
