# eloquent-repository
A repository for eloquent models. Using this makes the model appear to have a single responsibility 
and thus makes them unit-testable in isolation

## Installation
Via composer:

```
require: "doctorbeat/eloquent-repository": "*"
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

## Relations
To get relations from the repository use these methods:
```php
   [Model:]           [Repository:]                             [result]
-  $model->parent     $repo->getAttribute($model, 'parent');    the parent model
-  $model->children   $repo->getAttribute($model, 'children');  the child models as a collection
-  $model->children() $repo->children($model);                  the child models as a relation
-  $model->children()->save($child)
                      $repo->children($model, $child);          add the child to the children of the model
-  $model->children()->saveMany($list)
                      $repo->children($model, $list);           add an array of new children to the children of the model
```
These examples assume that you have defined parent and children as relations on the model!

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
