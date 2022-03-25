<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProgramRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProgramCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProgramCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Program::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/program');
        CRUD::setEntityNameStrings('program', 'programs');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('slug');
        CRUD::column('status');

        $this->crud->addFilter([
            'type'  => 'text',
            'name'  => 'name',
            'label' => 'Filter'
        ],
            false,
            function($value) {
                $this->crud->addClause('where', 'name', 'LIKE', "%$value%");
            }
        );
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProgramRequest::class);

        $this->crud->addFields([
            [
            'name'        => 'status',
            'label'       => "Status",
            'type'        => 'select_from_array',
            'options'     => ['' => '', 'Draft' => 'Draft', 'Live' => 'Live'],
            'allows_null' => false,
            'default'     => '',
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ]
        ]);

        CRUD::field('name')->label('Name')->type('text')->tab('Details');
        CRUD::field('slug')->label('Slug')->type('text')->tab('Details');
        CRUD::field('subtitle')->label('Subtitle')->type('text')->tab('Details');
        CRUD::field('description')->label('Description')->type('ckeditor')->tab('Details');
        CRUD::field('short description')->label('Short Description')->type('ckeditor')->tab('Details');

        $this->crud->addFields([   // Upload
            [
                'label'         => "Banner Image",
                'name'          => "banner_image",
                'type'          => 'image',
                'crop'          => true, // set to true to allow cropping, false to disable
                'aspect_ratio'  => 1, // omit or set to 0 to allow any aspect ratio
                'tab'           => 'Media',
            ]
        ]);

        $this->crud->addFields([   // Upload
            [
                'label'         => "Logo Image",
                'name'          => "logo_image",
                'type'          => 'image',
                'crop'          => true, // set to true to allow cropping, false to disable
                'aspect_ratio'  => 1, // omit or set to 0 to allow any aspect ratio
                'tab'           => 'Media',

            ]
        ]);

        $this->crud->addFields([   // Upload
            [
                'name'     => 'information',
                'label'    => "Information Tab",
                'type'     => 'checkbox',
                'fake'     => true,
                'store_in' => 'meta',// [optional]
                'tab'      => 'Resources'
            ],
            [
                'name'     => 'employers',
                'label'    => "Employers Tab",
                'type'     => 'checkbox',
                'fake'     => true,
                'store_in' => 'meta',
                'tab'       => 'Resources', // [optional]
            ],
            [
                'name'     => 'article',
                'label'    => "Articles Tab",
                'type'     => 'checkbox',
                'fake'     => true,
                'store_in' => 'meta',
                'tab'      => 'Resources', // [optional]
            ],
        ]);

        CRUD::field('about_banner')->label('About Banner')->type('ckeditor')->tab('Resources');
        CRUD::field('about_infos')->label('About Information')->type('ckeditor')->tab('Resources');
        CRUD::field('articles')->label('Articles')->type('text')->tab('Resources');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
