# eloquent-repository
A repository for eloquent models. Using this makes the model appear to have a single responsibility 
and thus makes them unit-testable in isolation

## Installation
Via composer:

```
"doctorbeat/eloquent-repository": "dev-master"
```

## Why a respository?
Eloquent is great for its simplicity but this simplicity makes that Eloquent models breaks 
the 'single responsibility" principle. An Eloquent model is both a entity (property bag) AND dealing with database interaction 
(select, insert, update, delete). And this makes that you can not mock your models in isolation. You need the entity functionality
but want to mock the database interaction.

This problem is further shown in the fact that Eloquent models use static functions like find(), all() and where().

In comes the Eloquent Repository which make you able to use a model as an entity/property bag moves all database interaction
methods to the repository. The repository comes with an interface which can be mocked.

## Methods and api
These methods are available on the repository and have an api equal to the corresponding Eloquent method:
- save
- delete
- push
- touch
- all
- find
- where*
- all other static methods

## Usage
```php
        $repo = new EloquentRepository('App\\Person');
        
        $ent = new Person();
        $ent->name = 'xyz';
        $repo->save($ent);
        $repo->delete($ent);
        
        $all = $repo->all();
        $person4 = $repo->find(4);
        $personXyz = $repo->whereName('xyz')->get();
```
