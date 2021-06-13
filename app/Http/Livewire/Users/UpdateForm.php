<?php

namespace App\Http\Livewire\Users;

use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
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
     * @var string
     */
    public $role;

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


    public function mount(Request $request)
    {
        //$this->searchTerm = $request->input('searchTerm');
        //$this->role = $request->input('role');
        //$this->pageNum = $request->input('pageNum');
    }

    /**
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required|max:255',
            'email'=> 'required|email|unique:users,email,'. $this->user_id,
            'current_team_id' => 'required|numeric',
            'role' =>'required'
        ];
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
        $this->validate();

        $user = User::find($this->user_id);
        if ($user) {
            $user->name = $this->name;
            $user->email = $this->email;
            $user->current_team_id = $this->current_team_id;
            $user->role = $this->role;


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
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->current_team_id = $user->current_team_id;
        $this->role = $user->role;

        if($delete)
            $this->confirmingSettingDeletion = true;
        else{
            $this->confirmingSettingUpdation = true;
        }
    }
    

    /**
     * delete user
     * 
     * @return void
     */
    public function delete()
    {           
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
        $users = User::where('name', 'like', '%'.$this->searchTerm.'%');
        if(strtolower($this->searchRole) != "all"){
            $users = $users->Where('role', strtolower($this->searchRole));
        }
        $users = $users->paginate($this->pageNum)->appends(InputRequest::except('page'));

        return view('users.update-form',['users' => $users]);
    }
}
