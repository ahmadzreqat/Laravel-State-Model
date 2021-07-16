# StateMM
<div class="row" align="center">
<img src="https://img.shields.io/github/issues/ahmadzreqat/stateMM" >
<img src="https://img.shields.io/github/stars/ahmadzreqat/stateMM" >
<img src="https://img.shields.io/github/license/ahmadzreqat/stateMM" >
<img src="https://img.shields.io/github/watchers/ahmadzreqat/stateMM?style=social" >
 </div>

## Overview
* This is a Laravel package to manage model state 



## Installation :
You can install `asz/generator` via Composer by adding `"asz/generator": "^1.1"` 
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
```bash

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
