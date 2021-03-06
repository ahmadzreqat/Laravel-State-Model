# Laravel Model State
<div class="row" align="center">
<img src="https://img.shields.io/github/issues/ahmadzreqat/stateMM" >
<img src="https://img.shields.io/github/stars/ahmadzreqat/stateMM" >
<img src="https://img.shields.io/github/license/ahmadzreqat/stateMM" >
<img src="https://img.shields.io/github/watchers/ahmadzreqat/stateMM?style=social" >
 </div>

## Overview
* This is a Laravel package to manage model state 



## Installation :
You can install ` asz/statemm` via Composer by adding `" asz/statemm": "^1.1"` 
as requirement to your composer.json. 
OR : 
```bash
composer require asz/statemm
```
* Then :
```bash
composer dump-autoload
```

### Service Provider:

go to your config/app.php file and add : 
```bash
 statemm\StateServiceProvider::class ,
```
#### adding interface to model 

```bash 
class Product extends Model implements hasState
```


#### use command line to create a new state for your model


```bash 
$ php artisan state:make activated --dir=productStateContainer
```

```bash

add method stubs in your model:
 public function initialState()
    {
         //set your initial state after generated 
        // TODO: Implement initialState() method.
        
        return new ActivatedState();
    }
```


## Test Unit
```bash
    public function testCanDeactivateProduct()
    {

        $product = new product();
       
       // the product is active and the initial state will check to 
       // $this->transitionTo( new DeactivatedState()); 
       
        $context = new Context($product->where(['id' => 4])->first()); 
       
       // if true the proceed function will go to next state 
       // which is deactivatedState and execute the query or what 
       // you want todo
       // the deactivated has query to deactivate the given product
       
       $context->proceed();
       self::assertEquals(StateEnum::DEACTIVATED_STATE, $context->getModel()->state);
    }
    
    ```
