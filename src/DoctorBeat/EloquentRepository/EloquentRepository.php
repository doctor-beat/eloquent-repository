<?php
namespace DoctorBeat\EloquentRepository;

/**
 * @see Repository
 *
 */
class EloquentRepository implements Repository {
    protected $modelClassname;
    
    public function __construct($modelClassname = null) {
        if ($modelClassname == null) {
            throw new InvalidArgumentException('classname can not be null');
        }
        $this->modelClassname = $modelClassname;
    }


    public function all() {
        return call_user_func_array("{$this->modelClassname}::all", array());        
    }

    public function delete($entity) {
        return $entity->delete();        
    }
    
    public function push($entity) {
        return $entity->push();        
    }
    
    public function touch($entity) {
        if (method_exists($entity, 'touch')) {
            return $entity->touch();
        } else {
            //fallback:
            $entity->updated_at = new \DateTime;
            return $this->save($entity);
        }
    }
    
    public function find($id, $columns = array('*')) {
        return call_user_func_array("{$this->modelClassname}::find", array($id, $columns));
	}

    public function save($entity) {
        return $entity->save();
    }

    public function __call($method, $parameters) {
        //rewrite any non-implemented dynamic method to a static method
        return call_user_func_array("{$this->modelClassname}::{$method}", $parameters);
    }
    
}
