class User {
	
  constructor(name) {
  	this._className =  'User';
    this.name = name;
  }
  
  /**
  * 
  * @param string name
  **/
  setName(name) {
  	this.name = name;
  }
  
  /**
 	*
  * @return string name
  **/
  getName () {
  	return this.name;
  }

}

User.prototype.reverseName = function () {
    if (typeof this.name == "undefined" || !this.name) return '';
    var rName = '';
    var nameArr = this.name.split(' ');
    /* nameArr.forEach(function (nam) {
       rName = nam + ' ' + rName;
    }); */
    
    // alternatively
    rName = nameArr.reverse().join(' ');
    
    return rName;
};

var user = new User();
user.setName('Jamiu Mojolagbe');
console.log('Object ', user._className, ' returns ', user.getName());
console.log(user.reverseName());