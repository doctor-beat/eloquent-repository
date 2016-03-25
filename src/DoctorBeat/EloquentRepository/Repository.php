<?php
namespace DoctorBeat\EloquentRepository;

/**
 * a 'adapter' that maps the methods that perform database interactions 
 * to the underlying eloquent-based model.
 *
 */
interface Repository {
    function save($entity);
    function delete($entity);
    function push($entity);
    function touch($entity);
    function all();
    function find($id);
    function getAttribute($entity, $key);
    function associate($relation, $entity);
    /**
     * perform a mass delete on all rows selected by the where conditions
     * @param ... where conditions as per where()     
     */
    function deleteWhere();
}
