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

```
/**\
*http_method=get\
*/

```

**Test**

The following command allow testing

```phpunit --bootstrap vendor/autoload.php tests/fileName.php```

**Validator**

Validator declaration with 

```$validator = new \App\validation\Validator();```
Adding Rule with:

``$validator->addRule([
      'firstname' => \App\validation\Validator::REQUIRED,
      'lastname' => \App\validation\Validator::REQUIRED,
      'email' => \App\validation\Validator::REQUIRED_EMAIL,
      'password' => \App\validation\Validator::REQUIRED,
  ]);``
  
  **Call the result**
  
``if($result = $validator->validate()) {
    var_dump($result);
    } else {
        $validator->getErrors();
    }
``


