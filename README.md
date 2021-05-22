SVS Web App
==============

This web application is developed to provide access to Surface-Volume-Surface Electric Field Integral Equation (SVS-EFIE) solver. 
It is a web application that encapsules interaction and job submission to the solver. 


Programming Languages & Frameworks
-----------------------------------
 * [PHP (Laravel 8.41.0)](https://laravel.com/docs/8.x/)
 * [Laravel Jetstream](https://jetstream.laravel.com/2.x/introduction.html)
 * [Alpine JS](https://laravel-livewire.com/docs/2.x/alpine-js)
 * [Tailwind CSS](https://tailwindcss.com/docs)


System Requirements
-------------------

Install the following softwares on your machine:

 * [LAMP Stack & Local Server (Ampps)](https://ampps.com/download)
 * [Git](https://git-scm.com/downloads)
 * [npm via Node.js](https://www.npmjs.com/get-npm)
 * [Composer](https://getcomposer.org/download/) (Download and install the executable)
 


Installation on a Local Machine
-----------------------------------

  * Navigate to the installation directory of your Ampps server. 
  	Open terminal/PowerShell/command window and navigate to the `www` folder.<br /><br />
  
  * [Clone this API repository](https://mojolagm@bitbucket.org/computational-physics/svsweb.git) 
  
    by typing the following command: 
    ```bash
    $ git clone https://mojolagm@bitbucket.org/computational-physics/svsweb.git
    ```
    In order to use command `git`, [Git](https://git-scm.com/downloads) must first be installed on your machine.
    
  * Navigate to the cloned directory, then run the command 
    ```bash 
    $ npm install 
    ```
    to install the javascript dependencies. [More details on this command..](https://docs.npmjs.com/cli/install) After several   installation steps, directory `node_modules` is created in the cloned directory.
	
  * In the same directory/path, build your javascript resources by running the following command
	```bash 
    $ npm run dev 
    ```
	**NOTE:** This command and others are included in `package.json` file
	
  * Next, install PHP packages included in `composer.json` file by running the following command
	```bash 
    $ composer install
    ```
    
  * Setup a database and user for the project by opening your [phpMyAdmin](http://localhost/phpmyadmin/server_privileges.php?viewing_mode=server) and 
    create a database and a user. Make sure the user is assigned to the database and granted all privileges.<br /><br />
  
  * Create a local configuration file `.env` by copying `.env.example` and rename it to `.env`.
    Open this `.env` file and set up the database section of the file by adding the database, username, and password.
    **NOTE:** At a point you will be asked to set up `APP KEY`.<br /><br />
    
  * Now, set up the database schema by running the migrations using the following command
	```bash 
    $ php artisan migrate
    ```
    and this will set up the database with default tables
  
  * Now, start your local server<br />
    Run the installed Ampps server by searching for it (Windows) or by navigating to `Applications` directory and find Ampps (Mac) and (double) clicking it.<br /><br />
    
  * Laravel has inbuilt server that needs to be started, thus, run the following artisan command
	```bash 
    $ php artisan serve
    ```
    and this will start the local server in the window and generate a local URL to access the app in the browser. <br /> <br />
    Usually, it starts the server at the default port `8000` thus the url will look like [http://localhost:8000/](http://localhost:8000/). However, there is no guarantee
    that this port will be free so Laravel can pick any available port.
  
  * Once the server has started running, the web app can be tested by visiting [http://localhost:8000/](http://localhost:8000/) or whatever link `php artisan serve` generates.

Usage
-----

Here is how you use it:

  * Click on `Submit a Job!` button and a popup will appear. Fill in the information by selecting sample files from samples directory location in `/Resources/samples/` path of your cloned repo.

#### TODO: Set up online server for the executable



# svsweb