<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Carbon;
use Session;
use Datatables;
use App\Models\Lead;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Repositories\Lead\LeadRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
use App\Http\Requests\Lead\UpdateLeadFollowUpRequest;
use App\Repositories\Client\ClientRepositoryContract;
use App\Repositories\Setting\SettingRepositoryContract;
use Illuminate\Support\Facades\Redirect;

use Input; 

class IctbroadcastcController extends Controller
{
    protected $leads;
    protected $clients;
    protected $settings;
    protected $users;

    public function __construct(
        LeadRepositoryContract $leads,
        UserRepositoryContract $users,
        ClientRepositoryContract $clients,
        SettingRepositoryContract $settings
    )
    {
        $this->users = $users;
        $this->settings = $settings;
        $this->clients = $clients;
        $this->leads = $leads;
        $this->middleware('lead.create', ['only' => ['create']]);
        $this->middleware('lead.assigned', ['only' => ['updateAssign']]);
        //$this->middleware('lead.update.status', ['only' => ['updateStatus']]);
        //$this->middleware('lead.update.status', ['only' => ['Ictbroadcastcampaign']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   /* public function index()
    {
        return view('leads.index');
    }*/


public function Ictbroadcast(){

    if(Session::get('id') == '' && empty($_POST)){

         $contact = Session::get('contact') ;
        if($contact =='clients'){

            return Redirect::to('/clients')->withErrors("Please Select Leads");
        }else{

            return Redirect::to('/leads')->withErrors("Please Select Leads");
        }
    }else{

    if(Session::get('id')==""){

        $idsary = $_POST['id'];
        $contact = $_POST['contact'];
        $ids = implode(',',$idsary);
    }else{

        $ids = Session::get('id');
        $contact = Session::get('contact');
        \Session::forget('id');
        // \Session::forget('contact');
    }
    $arguments = array();
    $result  = $this->broadcast_api('User_Extension_List', $arguments);
    if (is_object($result )) {
        $extention_data = '';
        $campaign_list ='';
        return view('leads.ictbroadcast',compact('ids','extention_data','campaign_list','contact'));
        exit;
    }
    $extention_data = '';
    if($result[0] == true) {
        $extention_data = $result[1];
    } 
    $arguments = array();
    $result  = $this->broadcast_api('Campaign_List_Permanent', $arguments);

    $campaign_list ='';

    if($result[0] == true) {
        $campaign_list = $result[1];
    } 

return view('leads.ictbroadcast',compact('ids','extention_data','campaign_list','contact'));
}
}
public function Ictbroadcastcampaign(){
            \Session::forget('id');
            \Session::forget('contact');
            $ids = $_POST['ids'];
            $contact = $_POST['contact'];
            $id_arry =  explode(",", $ids );
            $ary = array($id_arry);

            if($contact !='clients'){
                $leads = DB::table('leads')->whereIn('id',$ary[0] )->get();
                foreach($leads as $lead){
                 $client_id[] = $lead->id;
                }
                    $client = DB::table('clients')->whereIn('id',$client_id )->get();
            }else{
            $client = DB::table('clients')->whereIn('id',$ary[0]  )->get();
            }

            $campaing_type = $_POST['campaign_type'];
            $group = $_POST['group'];
            $json_data = array();
            
         if($_POST['camp']=='newc'){

            if($campaing_type !='' && $_POST['group']!=''){

                if(($_POST['campaign_type']=='voice' AND ($_FILES['fle']['type']=='audio/x-wav' OR $_FILES['fle']['type']=='audio/wav')) OR ($campaing_type == 'fax' AND ($_FILES['fle']['type']=='application/pdf' OR $_FILES['fle']['type']=='image/tiff' OR  $_FILES['fle']['type']=='image/x-tiff')) OR ($campaing_type =='voice_interactive' AND ($_FILES['fle']['type']=='audio/x-wav' OR $_FILES['fle']['type']=='audio/wav')) ){

                    $arguments = array('contact_group'=> array('name' => $group));
                    $result  = $this->broadcast_api('Contact_Group_Create', $arguments);
                    if($result[0] == true) {
                    $contact_group_id = $result[1];
                    // print_r($contact_id);
                    $json_data['group_id'] = $contact_group_id;
                    $json_data['campaign_type'] = $campaing_type;
                    }else{
                    $errmsg = $result[1];
                       \Session::put('id', $ids);
                        \Session::put('contact', $_POST['contact']);
                        Session()->flash('flash_message_warning', 'Some thing wrong:' . $errmsg);
                        return redirect()->back();
                   // return $errmsg;
                    }
                    foreach($client as $clients){

                    $contact = array(
                    'phone' => $clients->primary_number, 
                    'first_name'=>$clients->name, 
                    'last_name'=>'', 
                    'email'=> $clients->email
                    );
                    $arguments = array('contact'=>$contact, 'contact_group_id'=> $json_data['group_id']);
                    $result  = $this->broadcast_api('Contact_Create', $arguments);
                    if($result[0] == true) {
                    $contact_id = $result[1];
                    } else {
                    $errmsg = $result[1];
                    //return $errmsg;
                       \Session::put('id', $ids);
                        \Session::put('contact', $_POST['contact']);
                        Session()->flash('flash_message_warning', 'Some thing wrong:' . $errmsg);
                        return redirect()->back();
                    }
                    }
                }else{
                \Session::put('id', $ids);
                \Session::put('contact', $_POST['contact']);
                // return Redirect::to('/leads/ictbroadcastfrom')->withErrors("Please Choose Correct File According to Campaign Type.");
                Session()->flash('flash_message_warning', 'Please Choose Correct File According to Campaign Type');
                //Session()->flash('flash_message', 'Lead is completed');
                return redirect()->back();
                }
            if($campaing_type == 'voice' || $campaing_type=='fax' || $campaing_type =='voice_interactive' ){

            if($campaing_type=='voice'){

            $method = 'Recording_Create';
            $method_campaign =  'Campaign_Create';

            }elseif($campaing_type=='fax'){
            $method = 'Document_Create';
            $method_campaign =  'Campaign_Fax_Create';
            }elseif($campaing_type == 'voice_interactive'){
            $method = 'Recording_Create';

            $method_campaign =  'Campaign_Interactive_Create';
            }
            $mimetyper = $_FILES['fle']['type'];
            $m_file = curl_file_create($_FILES['fle']['tmp_name'], $mimetyper, basename($_FILES['fle']['name']));
            $arguments = array('title'=> str_replace(" ","_",$group), 'media'=>$m_file);
            $result = $this->broadcast_api($method, $arguments);
            if($result[0] == true) {
            $recording_id = $result[1];
            //print_r($recording_id);
            } else {
            $errmsg = $result[1];
            \Session::put('id', $ids);
            \Session::put('contact', $_POST['contact']);
            Session()->flash('flash_message_warning', 'Some thing wrong:' . $errmsg);
            return redirect()->back();
            //  print_r($errmsg);

            //Session()->flash('flash_message_warning', 'Some thing wrong:' . $errmsg);
            //return redirect()->back();
            }
            if($campaing_type == 'voice' || $campaing_type=='fax'){

            $campaign = array(
            'contact_group_id'  => $contact_group_id,     //  contact_group_id
            'message'           => $recording_id,     //  recording_id
            );
            }
            if($campaing_type =='voice_interactive'){

            $campaign = array(
            'contact_group_id'  => $contact_group_id,     //  contact_group_id
            'message'           => $recording_id,     //  recording_id
            'extension_key'     => '1',     // any value from 0 to 7 
            'extension_id'      => $request->get('extension'),     // extension_id 
            );

            }
            $arguments = array('campaign'=>$campaign);
            $result = $this->broadcast_api($method_campaign , $arguments);
            if($result[0] == true) {
            $campaign_id = $result[1];
            \Session::forget('id');
            Session()->flash('flash_message', 'Campaign Created');

            if($_POST['contact'] =='clients'){

                \Session::forget('contact');
            return Redirect::to('/clients');

            }else{
            return Redirect::to('/leads');
            }
        } else {
            $errmsg = $result[1];
            Session()->flash('flash_message_warning', 'Some thing wrong:' . $errmsg);
            return redirect()->back();
            } 

            }

            }else{
            \Session::put('id', $ids);
            \Session::put('contact', $_POST['contact']);
            Session()->flash('flash_message_warning', 'Enter Group or campaign type');
            return redirect()->back();
            }

    }elseif($_POST['camp']=='extc'){

            foreach($client as $clients){

                $contact = array(
                'phone' => $clients->primary_number, 
                'first_name'=>$clients->name, 
                'last_name'=>'', 
                'email'=> $clients->email
                );
                $arguments = array('contact'=>$contact, 'campaign_id'=>$_POST['campl']);
                $result  = $this->broadcast_api('Campaign_Contact_Create', $arguments);
                if($result[0] == true) {
                 $contact_id = $result[1];
                } else {
                 $errmsg = $result[1];
                //  return $errmsg;
                }
                $arguments = array('campaign_id'=>$_POST['campl']);
                $result = $this->broadcast_api('Campaign_Start', $arguments);
            }
    }
            \Session::forget('id');
            Session()->flash('flash_message', 'Campaign Started');
            if($contact =='clients'){

            \Session::forget('contact');
            return Redirect::to('/clients');
            }else{
            return Redirect::to('/leads');
            }
}


function broadcast_api($method, $arguments = array()) {
      // update following with proper access info
      //$api_username = 'admin';    // <=== Username at ICTBroadcast
     // $api_password = 'aDe4e832';  // <=== Password at ICTBroadcast
     // $service_url  = 'http://13.56.232.140/rest'; // <=== URL for ICTBroadcast REST APIs

    $ictbroadcast = DB::table('integrations')->where('name','ICTBroadcast' ) ->get();



     /* $api_username = 'zuha';    // <=== Username at ICTBroadcast
      $api_password = 'godisone';  // <=== Password at ICTBroadcast
      $service_url  = 'http://202.142.186.26/rest'; // <=== URL for ICTBroadcast REST APIs
      $post_data    = array(
        'api_username' => $api_username,
        'api_password' => $api_password
      );
      $api_url = "$service_url/$method";
      $curl = curl_init($api_url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);

      foreach($arguments as $key => $value) {
        if(is_array($value)){
          $post_data[$key] = json_encode($value);
        } else {
          $post_data[$key] = $value;
        }
      }
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
      // enable following line in case, having trouble with certificate validation
      // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      $curl_response = curl_exec($curl);
      curl_close($curl);
      return json_decode($curl_response); */




        $ipadrs = $ictbroadcast[0]->org_id;
        $token = $ictbroadcast[0]->api_key;

        $url = ($ipadrs!=''  ? $ipadrs : ''); // returns true
        $barer = ($token!=''  ? $token : ''); // returns true
         
       $service_url   = $url;

        $post_data    = array();
         /* $post_data    = array(
        'api_username' => $api_username,
        'api_password' => $api_password
          );*/
          $api_url = "$service_url/$method";
          $curl = curl_init($api_url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);

          foreach($arguments as $key => $value) {
        if(is_array($value)){
          $post_data[$key] = json_encode($value);
        } else {
          $post_data[$key] = $value;
        }
          }
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$barer));
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          // enable following line in case, having trouble with certificate validation
          // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          return json_decode($curl_response);  
    }








}