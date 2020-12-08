<?php

namespace Modules\Pages\Http\Controllers;

use App\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Pages\Entities\Page;
use Illuminate\Support\Str;
use Log;
use Flash;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Modules\Locations\Entities\Location;
use Modules\Promotions\Entities\Promotion;

class PagesController extends Controller
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Pages';

        // module name
        $this->module_name = 'pages';

        // module icon
        $this->module_icon = 'fas fa-sitemap';
        
        // directory path of the module
        $this->module_path = 'pages';

        // module model name, path
        $this->module_model = "Modules\Pages\Entities\Page";
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'View';

        $$module_name = $module_model::paginate();

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        $pages =  $module_model::all();

        return view(
            // "pages::backend.index",
            "pages::backend.index_datatable",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', 'pages')
        );
    }

    public function index_data()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = $module_model::select('id', 'name', 'type', 'parent','status')->where('draft_parent', null);

        $data = $$module_name;

        return Datatables::of($$module_name)
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $module_title = $this->module_title;
                            return view('backend.includes.action_column', compact('module_title', 'module_name', 'data'));
                        })
                        ->editColumn('name', function ($data) {
                            return $data->name.' '.$data->status_formatted;
                        })
                        ->editColumn('parent', function ($data) {
                            if ($data->get_parent==null) {
                                return '---';
                            } else {
                                return $data->get_parent['name'];
                            }

                        })
                        ->rawColumns(['name','type', 'action'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }


    public function index_list(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $term = trim($request->q);

        if (empty($term)) {
            return response()->json([]);
        }

        $query_data = $module_model::where('name', 'LIKE', "%$term%")->limit(10)->get();

        $$module_name = [];

        foreach ($query_data as $row) {
            $$module_name[] = [
                'id'   => $row->id,
                'text' => $row->name,
            ];
        }

        return response()->json($$module_name);
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "pages::backend.create",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';


        switch ($request->input('action')) {

            case 'draft':
                $request['status']=0;
                $$module_name_singular = $module_model::create($request->all());
            break;
            
            case 'published':
                $request['status']=1;
                $$module_name_singular = $module_model::create($request->all());
            break;
        }

        Flash::success("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name");
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $$module_name_singular = $module_model::findOrFail($id);
        // $brands = $$module_name_singular->latest()->paginate();

        $activities = Activity::where('subject_type', '=', $module_model)
                                ->where('log_name', '=', $module_name)
                                ->where('subject_id', '=', $id)
                                ->latest()
                                ->paginate();
        
        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');
        
        return view(
            "pages::backend.show",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'activities')
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $pages = Page::pluck('name', 'id');

        $module_action = 'Edit';

        $draft_page = Page::checkDraftPage($id);
        // dd($draft_page);

        $type_of_page = '';

        if($draft_page){   
            $$module_name_singular = $draft_page;
            $type_of_page='Draft Version';
        }else{
            $$module_name_singular = $module_model::findOrFail($id);
            $type_of_page='Published Version';
        }
        
        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "pages::backend.edit",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular",'pages','type_of_page')
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        if(!isset($request['show_in_menu'])){
            $request['show_in_menu']='0';
        }

        switch ($request->input('action')) {

            case 'draft':

                $$module_name_singular = $module_model::where('id', $id)->where('status', '0')->first();
                
                if($$module_name_singular){
                    $request['status']=0;
                    $$module_name_singular->update($request->all());
                }else{
                    $request['draft_parent']=$id;
                    $request['status']=0;
                    $$module_name_singular = $module_model::create($request->all());
                }
                Flash::success("<i class='fas fa-check'></i> '".Str::singular($module_title)."' Draft Updated Successfully")->important();
                // dd($$module_name_singular);

            break;
            
            case 'published':
                // $draft_page = $module_model::checkDraftPage($id);
                $draft_page = $module_model::where('id', $id)->where('deleted_at', null)->where('status', 0)->first();

                if($draft_page){
                    if($draft_page->draft_parent){
                        $$module_name_singular = $module_model::findOrFail($draft_page->draft_parent);
                        $draft_page->forceDelete();
                    }else{
                        $$module_name_singular = $module_model::findOrFail($id);
                    }
                }else{
                    $$module_name_singular = $module_model::findOrFail($id);
                }
                $request['draft_parent']=null;
                $request['status']=1;
                $$module_name_singular->update($request->all());
                Flash::success("<i class='fas fa-check'></i> '".Str::singular($module_title)."' Updated Successfully")->important();
            break;

        }

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');
        return redirect()->back();
        // return redirect("admin/$module_name");
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'destroy';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->delete();

        Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Deleted Successfully!')->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.', ID:'.$$module_name_singular->id." ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name");
    }

    /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     *
     * @return Response
     */
    public function trashed()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Trash List';

        $$module_name = $module_model::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate();

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name);

        return view(
            "pages::backend.trash",
            compact('module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    /**
     * Restore a soft deleted entry.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function restore($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Restore';

        $$module_name_singular = $module_model::withTrashed()->find($id);
        
        $$module_name_singular->restore();

        Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Data Restoreded Successfully!')->important();

        Log::info(label_case($module_action)." '$module_name': '".$$module_name_singular->name.', ID:'.$$module_name_singular->id." ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name");
    }

    public function preview($page)
    {
        $draft_page = Page::checkDraftPage($page);

        $type_of_page = '';

        if($draft_page){   
            $page = $draft_page;
            $type_of_page='Draft Version';
        }else{
            $page = Page::findOrFail($page);
            $type_of_page='Published Version';
        }
        
        switch ($page->type) {
            case 'bingo':
                $featuredPromos = app('App\Http\Controllers\FrontController')->getFeaturedPromos();
                $rooms = app('App\Http\Controllers\FrontController')->getBingoRooms();
                return view('frontend.bingo', compact('page','rooms','featuredPromos'));        
            break;

            case 'locations': 
                $locations = Location::published();
                $homepage_locations = Location::homepageLocations();
                $featuredPromos =  app('App\Http\Controllers\FrontController')->getFeaturedPromos();
                return view('frontend.locations', compact('page','locations','featuredPromos','homepage_locations'));
            break;

            case 'slots': 
                $newSlots = app('App\Http\Controllers\FrontController')->getSlotsByCategory("New");
                $jackpotSlots = app('App\Http\Controllers\FrontController')->getSlotsByCategory("Jackpots");
                $topPickSlots = app('App\Http\Controllers\FrontController')->getSlotsByCategory("Top Picks");
                $featuredPromos =  app('App\Http\Controllers\FrontController')->getFeaturedPromos();
                return view('frontend.slots', compact('newSlots','jackpotSlots','topPickSlots','page','featuredPromos'));
            break;

            case 'promotions': 
                $externalPromos = Promotion::online();
                return view('frontend.promotions-online', compact('externalPromos','page'));
            break;
            
            default:
                return view("frontend.page", compact('page','type_of_page'));
            break;
        }
        
    }
}
