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
    public function __invoke(Request $request) {
        // page info
        $findPage = Page::where([['link', '=', $request->getPathInfo()], ['status', '=', 1]])->get()->first();
        $page = !empty($findPage) ? $findPage : self::PAGE_INFO;
        $students = User::leftjoin('teams', 'users.current_team_id','=','teams.id')->where('teams.id', '=', 1)->get(['users.*','teams.name as team_name']);
        $supervisors = User::leftjoin('teams', 'users.current_team_id','=','teams.id')->where('teams.id', '=', 2)->get(['users.*','teams.name as team_name']);
        //dd($users);
        return view(
                self::TEMPLATE_FILE,
                [
                    'students'=>$students,
                    'supervisors'=>$supervisors,
                    'app'  => new App(),
                    'page'=>$page
                ],
        );
    }
    
    

    }
