<?php

namespace App\Modules;
/**
* ServiceProvider
*
* The service provider for the modules. After being registered
* it will make sure that each of the modules are properly loaded
* i.e. with their routes, views etc.
*
* @author Kamran Ahmed <kamranahmed.se@gmail.com>
* @package App\Modules
*/
class ModulesServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Will make sure that the required modules have been fully loaded
     * @return void
     */
    public function boot()
    {
        // For each of the registered modules, include their routes and Views
        $modules = config("modules.modules");

        // echo "Modules Set : \n";

        foreach($modules as $key => $module){

            // echo $key."\n";

            foreach($module as $key2 => $submodule){

                // echo "-----".$submodule."\n";

                view()->addNamespace($key.'.'.$submodule,__DIR__.'/'.$key.'/'.$submodule);

                // Load the routes for each of the modules
                if(file_exists(__DIR__.'/'.$key.'/'.$submodule.'/routes.php')) {
                    include __DIR__.'/'.$key.'/'.$submodule.'/routes.php';
                }  
                  
                // Load the views
                if(is_dir(__DIR__.'/'.$key.'/'.$submodule.'/Views')) {
                    $this->loadViewsFrom(__DIR__.'/'.$key.'/'.$submodule.'/Views', $submodule);
                }

            }

        }
        
    }

    public function register() {}

}

?>