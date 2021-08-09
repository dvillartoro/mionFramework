# mionFramework
### A tiny MVC framework made with PHP

The contents of each folder are briefly described below:

**APP:**
Contains each application in a different directory and also has a shared directory for sharing models, controllers, etc. Each application should contain the following directories:

 - **Controllers:** Contains the application controllers.
 - **Models:** Contains all models used in the application.
 - **Views:** Contains all views and layouts.

**ASSETS:**
Contains public files that are included in the different applications such as images, fonts, style sheets, etc... If the directory tree is based on applications or on file types, depends on the project. In any case, is a flexible system and it is a decision for developers.

**CONFIG:**
Contains all the configuration files. Files beginning with underscore are loaded first. Next, the rest of the files are loaded, except for those whose name matches with an application name(for example: web.php or admin.php). The configuration for the applications is loaded separately since it affects only that application.

**CORE:**
Contains all main files for the framework:

 - **Config.php**
Defines the class in charge of reading all configuration files.
 - **Router.php**
Defines the class in charge of routing and loading requested views.
 - **Database.php**
Defines the class in charge of creating database connection.
 - **Autoload.php**
It is responsible for loading all the files needed in the framework: core files, helpers, third-party packages and, finally, models and controllers from the different applications
 - **BaseController.php**
Defines the main controller and provides access to system configuration, connection to the routing system and rendering of views
 - **BaseModel.php**
Contains the main model and provides access to the database connection.

**DEV:**
Contains development files that are not included in production. It can contain anything but it is intended for files to be processed such as SASS or LESS files, JavaScript files to be compiled and minified, etc.

**HELPERS:**
Contains some static classes that can be useful in any part of the project: date handlers, string functions, etc.

**VENDOR:**
Folder reserved for third-party packages. Although the use of a package manager is recommended, it is not compulsory. In this case it will be needed to update autoload.php file.
