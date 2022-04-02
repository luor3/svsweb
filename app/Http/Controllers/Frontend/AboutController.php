<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\App;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Str;

class AboutController extends Controller {

    /**
     * 
     * @var array Page info
     */
    const PAGE_INFO = [
        'title' => "About us",
        'description' => "Our members' information is shown here",
        'keywords' => "us, team, about us, about, members",
        'content' => '',
    ];

    /**
     * @var string
     */
    const TEMPLATE_FILE = 'about';

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        // page info
        $findPage = Page::where([['link', '=', $request->getPathInfo()], ['status', '=', 1]])->get()->first();
        $page = !empty($findPage) ? $findPage : self::PAGE_INFO;
        $students = User::leftjoin('teams', 'users.current_team_id', '=', 'teams.id')->where('teams.id', '=', 1)->get(['users.*', 'teams.name as team_name']);
        $supervisors = User::leftjoin('teams', 'users.current_team_id', '=', 'teams.id')->where('teams.id', '=', 2)->get(['users.*', 'teams.name as team_name']);
        //dd($users);
        return view(
                self::TEMPLATE_FILE,
                [
                    'students' => $students,
                    'supervisors' => $supervisors,
                    'app' => new App(),
                    'page' => $page
                ],
        );
    }

    public function getBio(Request $request, $name, $id) {
        // page info
        $findPage = Page::where([['link', '=', $request->getPathInfo()], ['status', '=', 1]])->get()->first();
        $page = !empty($findPage) ? $findPage : self::PAGE_INFO;
        $user = User::find($id);
        return view('bio',
                [
                    'user' => $user,
                    'app' => new App(),
                    'page' => $page
                ],
        );
    }

    public function downloadVCard($name, $id) {
        $vcard = new VCard();
        $user = User::find($id);
        // define variables
        $lastname = '';
        $firstname = $user->name;
        $additional = '';
        $prefix = '';
        $suffix = '';

        // add personal data
        $vcard->addName($lastname, $firstname, $additional, $prefix, $suffix);

        // add work data
        //$vcard->addCompany($user->company);
        //$vcard->addJobtitle($user-job);
        //$vcard->addRole('');
        $vcard->addEmail($user->email);
        //$vcard->addPhoneNumber(111, 'PREF;WORK');
        //$vcard->addPhoneNumber(111, 'WORK');
        //$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
        //$vcard->addLabel('street, worktown, workpostcode Belgium');
        $vcard->addURL($user->linkedin);
        //$vcard->addPhoto(__DIR__ . '/landscape.jpeg');
        $username = Str::slug($user->name,'-');
        if (empty($user->profile_photo_path)){
        $vcard->addPhoto("https://ui-avatars.com/api/?name={$username}&color=7F9CF5&background=EBF4FF");
        
        }
        
        else{
        $src = asset('storage/'.$user->profile_photo_path);
        
        $vcard->addPhoto($src);
        }
        //// return vcard as a string
        //return $vcard->getOutput();
        // return vcard as a download
        return $vcard->download();
    }

}
