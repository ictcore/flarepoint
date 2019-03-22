<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon;
use Session;
use Datatables;
use App\Http\Controllers\Controller;
class IctbroadcastController extends Controller
{

 public function __construct( )
    {
        $this->users = $users;
        $this->settings = $settings;
        $this->clients = $clients;
        $this->leads = $leads;
        $this->middleware('lead.create', ['only' => ['create']]);
        $this->middleware('lead.assigned', ['only' => ['updateAssign']]);
        $this->middleware('lead.update.status', ['only' => ['updateStatus']]);
       $this->middleware('lead.update.status', ['only' => ['ictbroadcast']]);

    }




public fuction index(){

echo "hello";

exit;
}
}
