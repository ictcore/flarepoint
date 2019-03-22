<?php
namespace App\Http\Controllers;
use DB;
use App\Http\Requests;
use App\Models\Integration;
use Illuminate\Http\Request;
class empty_set{

}
class IntegrationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('user.is.admin', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $Dinero = Integration::where('name','Dinero')->first();
            $Billy = Integration::where('name','Billy')->first();
            $ictbroadcast = DB::table('integrations')->where('name','ICTBroadcast' )->first();
            if(empty($ictbroadcast)){
              $ictbroadcast = new empty_set;
               $ictbroadcast->api_key=''; 
               $ictbroadcast->org_id=''; 
               $ictbroadcast->ictapi=''; 
               $ictbroadcast->id=''; 
            }
            if(empty($Dinero)){
              $Dinero = new empty_set;
               $Dinero->api_key=''; 
               $Dinero->org_id=''; 
               $Dinero->ictapi=''; 
               $Dinero->id=''; 
            }
            if(empty($Billy)){
              $Billy = new empty_set;
               $Billy->api_key=''; 
               $Billy->org_id=''; 
               $Billy->ictapi=''; 
               $Billy->id=''; 
            }

        return view('integrations.index',compact('ictbroadcast','Dinero','Billy'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
      //echo "<pre>";print_r($input);exit;
        /*$existing = Integration::where([
        $existing = Integration::where([
            // 'user_id' => $request->post['user_id'] ? $userId : null,
            'api_type' => $request->api_type,
            'ictapi'  =>$request->ictapi
        ])->get();
        $existing = isset($existing[0]) ? $existing[0] : null;

        if ($existing) {
            $existing->fill($input)->save();
        } else {
            Integration::create($input);
        }*/

        if($request->id==''){
            $existing =new Integration;
        }else{
            $existing = Integration::find($request->id);
        }
        $existing->user_id=auth()->id();
        $existing->api_key=$request->api_key;
        $existing->name=$request->name;
        $existing->org_id=$request->org_id;
        $existing->save();

        return $this->index();
    }
}
