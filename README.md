**PHP fixer**

Use php fixer to fix the code with the following command
*php-cs-fixer.phar fix /path/to/project*

**Methods**

Only 4 types of http methods are allowed inside the controller comments.
There are all compulsory when calling Route::all($urlname, $controllerName)
- **get**
- **post**
- **delete**
- **put**

The use of the http_method is declared by adding comment on the function.

For instance

/**\
*http_method=get\
*/ 
