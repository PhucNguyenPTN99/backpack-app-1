<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Images;

class Module extends Model
{
	use CrudTrait;

	protected $table = 'academy_modules';
	protected $guarded = ['id'];
	protected $fillable = [];

	public function programs()
	{
		return $this->belongsTo('App\Models\Program', 'academy_program_id', 'id');
	}

	public function parent()
	{
		return $this->belongsTo(Module::class, 'parent_id');
	}

	public function program()
	{
		return $this->belongsTo('App\Models\Program', 'academy_program_id', 'id');
	}

	// [
	//     'id' => 'program_name:module_name',
	//     '1' => 'Finlit:Making Money',
	//     '2' => 'Finlit:Money Smart'
	//     ...
	//    ]

	public static function parentModule($empty = false, $current_module_name = null)
    {
        $modules= Module::with('program')->get()->mapWithKeys(function ($module) use ($current_module_name) {
			$module_name = [$module->name];
			$module_id = $module->id;
			$program_name = $module->program->name;
			while ($module->parent) {
				$module = $module->parent;
				$module_name[] = $module->name;
			}
			if(in_array($current_module_name, $module_name)) return [];
			$module_name = !empty($module_name) ? implode(' > ', array_reverse($module_name)) : '-';
			return [$module_id => $program_name . ' : ' . $module_name];
        });
        if ($empty) {
            $modules = collect([0 => '-'])->union($modules);
        }
        return $modules->all();
    }
}
