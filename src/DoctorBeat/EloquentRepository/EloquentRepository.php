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

    /**
     * Get all of the models from the database.
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all() {
        return call_user_func_array("{$this->modelClassname}::all", array());        
    }

	/**
	 * Delete the model from the database.
	 *
	 * @return bool|null
	 * @throws \Exception
	 */
    public function delete($entity) {
        return $entity->delete();        
    }
    
	/**
	 * Save the model and all of its relationships.
	 *
	 * @return bool
	 */
    public function push($entity) {
        return $entity->push();        
    }
    
	/**
	 * Update the model's update timestamp.
	 *
	 * @return bool
	 */
    public function touch($entity) {
        if (method_exists($entity, 'touch')) {
            return $entity->touch();
        } else {
            //fallback:
            $entity->updated_at = new \DateTime;
            return $this->save($entity);
        }
    }
    
	/**
	 * Find a model by its primary key.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return \Illuminate\Support\Collection|static|null
	 */
    public function find($id, $columns = array('*')) {
        return call_user_func_array("{$this->modelClassname}::find", array($id, $columns));
	}

	/**
	 * Save the model to the database.
	 *
	 * @param  array  $options
	 * @return bool
	 */
    public function save($entity) {
        return $entity->save();
    }

    /**
     * Method to retrieve the dynamic relationship properteis from the eloquent model.
     * So instead of using $model->comments do $repo->getAttribute($model, 'comments')
     * @param Model $entity
     * @param string $key
     * @return mixed the requested value
     */
    public function getAttribute($entity, $key) {
		return $entity->getAttribute($key);
	}

    /**
     * Method to attach a related model, the equivalent of $post->comments()->save($comment)
     * which translates to $repo->addTo($post->comments, $comment)
     * @param HasOneOrMany $relation
     * @param Model or array() of Models $entities
     * @return mixed
     */
    public function addTo($relation, $entities) {
        if (is_array($entities)) {
            return $relation->saveMany($entities);
        } else {
            return $relation->save($entities);
        }
	}
    
    /**
     * Method to update a belongsTo relationship, the equivalent of $relation->associate($entity)
     * Unlike other methods in this class this method is save to call directly on the model as it does
     * not do a $model->save() internally.
     * @param BelongsTo $relation
     * @param Model $entity
     * @return Model the parent of the relation
     */
    public function associate($relation, $entity) {
        return $relation->associate($entity);
    }


    public function __call($method, $parameters) {
        //rewrite any non-implemented dynamic method to a static method
        return call_user_func_array("{$this->modelClassname}::{$method}", $parameters);
    }
    

    
}
