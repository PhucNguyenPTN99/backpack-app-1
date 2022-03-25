<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ModuleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Module;

use function PHPSTORM_META\type;

/**
 * Class ModuleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ModuleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Module::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/module');
        CRUD::setEntityNameStrings('module', 'modules');
    }

    protected function setupListOperation()
    {
        // $this->crud->column([
        //     'label' => 'Program',
        //     'type' => 'text',
        //     'name' => 'academy_program_id', // the db column for the foreign key
        //     // 'entity' => 'programs', // the method that defines the relationship in your Model
        //     // 'attribute' => 'name', // foreign key attribute that is shown to user
        //     // 'model' => 'App\Models\Program' // foreign key model
        // ]);
        CRUD::column('programs.name')->label('Program')->type('text')->attribute('name')->model('App\Models\Program')->entity('programs');
        CRUD::column('name');

        $this->crud->addColumn([
            'label' => 'Order',
            'type' => 'closure',
            'function' => function($entry) {
                $order = []; //[]
                $order[] = $entry->order; ///1 2
                while ($entry-> parent){ //null
                    $entry = $entry->parent;
                    $order[] = $entry->order; //1
                }
                return !empty($order) ? implode('.', array_reverse($order)) : ' - ';
            }
        ]);

        CRUD::column('slug');

        $this->crud->addColumn([
            'label'     => 'Parent Modules',
            'type'      => 'closure',
            'function' => function($entry){
                $parent_module = [];
                while($entry->parent){
                    $entry = $entry->parent;
                    $parent_module[]=$entry->name;
                }
                return !empty($parent_module) ? implode(' > ', array_reverse($parent_module)) : ' - ';
            }
        ]);

        $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'name',
            'label' => 'Program',
            'attribute' => 'name',
            'model' => 'App\Models\Program',
            'entity' => 'programs'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'LIKE', "%$value%");
            }
        );
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ModuleRequest::class);

        $this->crud->addField([
            'label' => 'Program',
            'type' => 'select2',
            'name' => 'academy_program_id', // the db column for the foreign key
            'entity' => 'programs', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Models\Program' // foreign key model
        ]);

        $this->crud->addField([
            'name' => 'parent_id',
            'label' => 'Parent Module',
            'type' => 'select2_from_array',
            'options' => Module::parentModule(true, $this->crud->getCurrentEntry() ? $this->crud->getCurrentEntry()->name : null),
            'allows_null' => false,
            'default'     => ' - ',
        ]);

        CRUD::field('order')->label('Order')->type('number')->tab('Details');
        CRUD::field('name')->label('Name')->type('text')->tab('Details');
        CRUD::field('slug')->label('Slug')->type('text')->tab('Details');
        CRUD::field('description')->label('Description')->type('ckeditor')->tab('Details');
        CRUD::field('sub_modules_intro')->label('Sub-Modules Intro')->type('ckeditor')->tab('Details');

        $this->crud->addFields([   // Upload
            [
                'label' => "Banner Image",
                'name' => "banner_image",
                'type' => 'image',
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
                'tab'             => 'Media',

            ]
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
