<?php

namespace App\Http\Livewire\Users;

use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Request as InputRequest;


class UpdateForm extends Component
{
    
    use WithPagination;
    
    /**
     * @var string component template path
     */
    const COMPONENT_TEMPLATE = 'users.update-form';
    
    /**
     * @var string Redirect parent route name
     */
    const REDIRECT_ROUTE = 'users';
    
    


    /**
     * 
     * @var integer
     */
    public $user_id;


    /**
     * 
     * @var boolean
     */
    public $status;

    /**
     * 
     * @var string
     */
    public $name;


    /**
     * 
     * @var string
     */
    public $email;

    /**
     * 
     * @var string
     */
    public $current_team_id;
    

    /**
     * 
     * @var array
     */
    public $teamList = [];


    public $currentOrderProperty;


    public $currentOrder = 'asc';

    /**
     * 
     * @var string
     */
    public $role;


    /**
     * 
     * @var array
     */
    public $permission = [
        "edit_user" => false,
        "delete_user" => false,
        "edit_demo" => false,
        "delete_demo" => false,
    ];

    /**
     * 
     * @var string
     */
    public $searchRole="all";

    /**
     * 
     * @var boolean
     */
    public $confirmingSettingDeletion = false;

    /**
     * 
     * @var boolean
     */
    public $confirmingSettingUpdation = false;

    /**
     * 
     * @var boolean
     */
    public $confirmingPermissionUpdation = false;

    /**
     * 
     * @var String
     */
    public $searchTerm;

    /**
     * 
     * @var int
     */
    public $pageNum = 5;



    /**
     * 
     * @var array
     */
    protected $queryString = [
        'searchRole',
        'searchTerm' => ['except' => ''],
        'pageNum',
    ];


    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required|max:255',
            'email'=> 'required|email|unique:users,email,'. $this->user_id,
            'current_team_id' => 'nullable|numeric',
            'role' =>'required',
            'status'=>'required|boolean'
        ];
    }


    public function mount()
    {
        $teams = Team::all();
        foreach ($teams as $team){
            $this->teamList[$team->id] =  $team->name;
        }
    }



    /**
     * reset page number before updating searchTerm
     * 
     * @return void
     */
    public function updatingSearchTerm()
    {
        $this->resetPage();
    }


    /**
     * reset page number before updating pageNum
     * 
     * @return void
     */
    public function updatingPageNum()
    {
        $this->resetPage();
    }


    /**
     * reset page number before updating searchRole
     * 
     * @return void
     */
    public function updatingSearchRole()
    {
        $this->resetPage();
    }

    /**
     * update user
     * 
     * @return void
     */
    public function update()
    {

        if(!auth()->user()->hasPermission(0)){
            session()->flash('flash.banner', "You do not have permission to edit user!");
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::REDIRECT_ROUTE);
        }

        $this->validate();
        $user = User::find($this->user_id);

        if ($user) {
            $user->name = $this->name;
            $user->email = $this->email;
            $user->current_team_id = $this->current_team_id;
            $user->role = $this->role;
            $user->status = $this->status;

            $status = $user->save();
            
            $this->emit('saved', $status);
        }
    }

    /**
     * update user
     * 
     * @return void
     */
    public function updatePermission()
    {
        $user = User::find($this->user_id);
        if ($user) {
            $user->permission = $this->encodePermission();

            $status = $user->save();
            
            $this->emit('saved', $status);
        }
    }

    /**
     * register user for updation or deletion
     * 
     * @return void
     */
    public function registerUser(User $user, $delete)
    {
        if($delete){
            if(!auth()->user()->hasPermission(1)){
                session()->flash('flash.banner', "You do not have permission to delete user!");
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::REDIRECT_ROUTE);
            }
        }
        else{
            if(!auth()->user()->hasPermission(0)){
                session()->flash('flash.banner', "You do not have permission to edit user!");
                session()->flash('flash.bannerStyle', 'danger');
                return redirect()->route(self::REDIRECT_ROUTE);
            }
        }

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->current_team_id = $user->current_team_id;
        $this->role = $user->role;
        $this->status = $user->status;
        if($delete){
            $this->confirmingSettingDeletion = true;
        }
        else{
            $this->confirmingSettingUpdation = true;
        }
    }

    /**
     * register user for updation or deletion
     * 
     * @return void
     */
    public function registerUserPermission(User $user)
    {
        $this->user_id = $user->id;
        $permissionNumber = $user->permission;
        $this->confirmingPermissionUpdation = true;

        $this->permission = [
            "edit_user" => false,
            "delete_user" => false,
            "edit_demo" => false,
            "delete_demo" => false,
        ];

        $keys = array_keys( $this->permission );
        $i = 0;
        while ($permissionNumber > 0)
        {
            $this->permission[$keys[$i]] = $permissionNumber % 2;
            $permissionNumber = (int)($permissionNumber / 2);
            $i++;
        }

    }
    

    /**
     * delete user
     * 
     * @return void
     */
    public function delete()
    {           
        if(!auth()->user()->hasPermission(1)){
            session()->flash('flash.banner', "You do not have permission to delete user!");
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route(self::REDIRECT_ROUTE);
        }

        if ($this->user_id) {
            
            $deleted = User::where('id', $this->user_id)->delete();
            
            $msg =  $deleted ? 'Setting successfully deleted!' : 'Ooops! Something went wrong.';
            $flag = $deleted ? 'success' : 'danger';

            session()->flash('flash.banner', $msg);
            session()->flash('flash.bannerStyle', $flag);
            
            if ($deleted) {    
                return redirect()->route('users');
            }
        }      
    }

    /**
     * filter users and render the component
     * 
     * @return void
     */
    public function render()
    {
        $users = User::leftjoin('teams', 'users.current_team_id','=','teams.id')->where('users.name', 'like', '%'.$this->searchTerm.'%');
        if(strtolower($this->searchRole) != "all"){
            $users = $users->Where('role', strtolower($this->searchRole));
        }

        if(!is_null($this->currentOrderProperty) && $this->currentOrder !== '')
        {
            $users = $users -> orderBy($this->currentOrderProperty,$this->currentOrder);  
        }


        $users = $users->paginate($this->pageNum,['users.*','teams.name AS team_name'])->appends(InputRequest::except('page'));

        return view('users.update-form',['users' => $users]);
    }

    /**
     * change order status
     * 
     * @return void
     */
    public function demoOrder($property)
    {
        if($property === $this->currentOrderProperty)
        {
           $this->currentOrder = ($this->currentOrder === 'asc') ? 'desc' : (($this->currentOrder === 'desc') ? '' : 'asc');
        }
        else
        {
            $this->currentOrder = 'asc';
            $this->currentOrderProperty = $property;
        }
    }

    private function encodePermission(){
        $encodedPermission = 0;
        $count = 0;
        foreach($this->permission as $key=>$value){
            if($value){
                $encodedPermission += pow(2, $count);
            }
            $count++;
        }
        return $encodedPermission;
    }
   
}
