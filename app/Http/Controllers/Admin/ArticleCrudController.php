<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('article', 'articles');
        $this->crud->addButtonFromView('line', 'moderate', 'moderate', 'beginning');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('slug');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ArticleRequest::class);

        CRUD::field('title')->label('Title')->type('text')->tab('Details');
        CRUD::field('slug')->label('Slug')->type('text')->tab('Details');

        CRUD::field('description')->label('Description')->type('ckeditor')->tab('Details');
        $this->crud->addFields([
            [
            'name'        => 'status',
            'label'       => "Status",
            'type'        => 'select_from_array',
            'options'     => ['' => '', 'Draft' => 'Draft', 'Live' => 'Live'],
            'allows_null' => false,
            'default'     => '',
            'tab'         => 'Details',
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ]
        ]);

        CRUD::field('description')->label('Description')->type('ckeditor')->tab('Details');
        $this->crud->addFields([
            [   // date_picker
                'name'  => 'publish_date',
                'type'  => 'date_picker',
                'label' => 'Publish Date',
                // optional:
                'date_picker_options' => [
                   'todayBtn' => 'linked',
                   'format'   => 'yyyy-mm-dd',
                   'language' => 'en'
                ],
                'tab'         => 'Details'
             ],
        ]);

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
                'label'         => "Mobile Banner Image",
                'name'          => "mobile_banner_image",
                'type'          => 'image',
                'crop'          => true, // set to true to allow cropping, false to disable
                'aspect_ratio'  => 1, // omit or set to 0 to allow any aspect ratio
                'tab'           => 'Media',

            ]
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
