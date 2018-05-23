
**PHP fixer**
Use php fixer to fix the code with the following command
*php-cs-fixer.phar fix /path/to/project*
**Methods**

Only 4 types of http methods are allowed inside the controller comments.
There are all compulsory when calling Route::all(urlname, controllerName), Route::get(...), Route::post(...)
- **get**
- **post**
- **delete**
- **put**

The use of the http_method and auth are declared by adding comment on the class method.

```
/**\
*http_method=get
*auth=admin
*/
public function nameOfMyMethod() {

}
```


