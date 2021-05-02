SVS Web App
==============

This web application is developed to provide access to Surface-Volume-Surface Electric Field Integral Equation (SVS-EFIE) solver. 
It is a web application that encapsules interaction and job submission to the solver. 



System Requirements
-------------------

Install the following softwares on your machine:

 * [LAMP Stack & Local Server (Ampps)](https://ampps.com/download)
 * [Git](https://git-scm.com/downloads)
 * `[SKIP]` [npm via Node.js](https://www.npmjs.com/get-npm) 
 


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
    
  * `[SKIP]` Navigate to the cloned directory, then run the command 
    ```bash 
    $ npm install 
    ```
    to install the API dependencies. [More details on this command..](https://docs.npmjs.com/cli/install) After several   installation steps, directory `node_modules` is created in the cloned directory.
  
  * Now, start your local server<br />
    Run the installed Ampps server by searching for it (Windows) or by navigating to `Applications` directory and find Ampps (Mac) and (double) clicking it.<br /><br />
  
  * Once the server has started running, the web app can be tested by visiting [http://localhost/svsweb/](http://localhost/svsweb/).

Usage
-----

Here is how you use it:

  * Click on `Submit a Job!` button and a popup will appear. Fill in the information by selecting sample files from samples directory location in `/Resources/samples/` path of your cloned repo.

#### TODO: Set up online server for the executable



# svsweb