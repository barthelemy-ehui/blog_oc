
**Methods**

Only 2 types of http methods are allowed inside the method comments.
There are all compulsory when calling Route::all(urlname, controllerName), Route::get(...), Route::post(...)
- **get**
- **post**

The use of the http_method and auth are declared by adding comment on the class method.

```
/**\
*http_method=get
*auth=admin
*/
public function nameOfMyMethod() {

}
```


