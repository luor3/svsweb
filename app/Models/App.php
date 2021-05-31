<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings;

class App extends Model
{
    use HasFactory;
    
    /**
     * DB key for entries
     */
    const DB_KEY = 'APP_SETTINGS:';
    
    /**
     *
     * @var string 
     */
    protected $author = 'SVS-EFIE Solver';
    
    /**
     *
     * @var string 
     */
    protected $name = 'SVS Web';
    
    /**
     *
     * @var string 
     */
    protected $description = 'Solver description goes here';
    
    /**
     *
     * @var string 
     */
    protected $phone = '0000-000-0000';
    
    /**
     *
     * @var string 
     */
    protected $address = 'Winnipeg, Manitoba, Canada.';
    
    /**
     *
     * @var string 
     */
    protected $facebookpage = 'https://www.facebook.com';
    
    /**
     *
     * @var string 
     */
    protected $twitterpage = 'https://www.twitter.com';
      
    /**
     *
     * @var string 
     */
    protected $youtubepage = 'https://www.youtube.com';
    
    /**
     *
     * @var string 
     */
    protected $linkedinpage = 'https://www.linkedin.com';
    
    /**
     *
     * @var string 
     */
    protected $facebookpageid = '000000000';
    
    /**
     *
     * @var string 
     */
    protected $twitterpageid = '00000000';
    
    /**
     *
     * @var string 
     */
    protected $webpush_private = 'PRIVATE_KEY';
    
    /**
     *
     * @var string 
     */
    protected $webpush_public = 'PUBLIC_KEY';
    
    /**
     *
     * @var string 
     */
    protected $email = 'info@svsweb.net';
    
    /**
     *
     * @var string Site banner
     */
    protected $banner = '/images/banner.jpg';
    
    /**
     *
     * @var string Site banner
     */
    protected $noimage = '/images/no-image-1.jpg';

    /**
     * Class constructor
     * 
     */
    public function __construct() {
        parent::__construct();
        
        $settings = Settings::selectRaw('`name`, `value`')
                ->whereRaw(sprintf("TRIM(`name`) LIKE '%s'", self::DB_KEY.'%'))
                ->pluck('value', 'name');
        
        if (!empty($settings)) {
            foreach ($settings as $name => $value) {
                $varname = strtolower(str_replace(self::DB_KEY, '', $name));
                $this->$varname = $value;
            }
        }
    }
    
    /**
     * Magic getter
     * 
     * @param string $property
     * @return string
     */
    public function __get($property) 
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            return $this->getAttribute($property);
        }
    }
    
    /**
     * Magic setter
     * 
     * @param string $property
     * @param string $value
     */
    public function __set($property, $value) 
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        } else {
            $this->setAttribute($property, $value);
        }
    }
}
