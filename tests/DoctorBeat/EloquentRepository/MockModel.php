<?php
namespace DoctorBeat\EloquentRepository;

use Mockery as m;

/**
 * Description of ModelModel
 *
 * @author ronald
 */
class MockModel {
    protected $callCount = array(
        'save'=> 0,
        'delete'=> 0, 
        'myRelation' => 0,
    );
    
    protected $relation;
    protected static $query = null;
    
    function setRelation($relation) {
        $this->relation = $relation;
    }
    
    public static function all() {
        $list = array();
        
        $self = new self();
        $self->id = 5;
        $self->name = 'first';
        $list[] = $self;
        return $list;
    }

    public static function find($id, $columns = array()) {
        
        $self = new self();
        $self->id = $id;
        $self->name = 'found ' . $id;
        return $self;
    }

    public static function where_name($name, $columns = array()) {
        
        $self = new self();
        $self->id = rand(0, 100);
        $self->name = $name;
        
        $query = self::mockQuery();
        $query->shouldReceive('get')->andReturn(array($self));
        $query->shouldReceive('first')->andReturn($self);
        
        return $query;
    }
    public static function where($x, $y, $z = true) {
        $query = self::mockQuery();
        
        return $query;
    }
    
    public function save() {
        $this->callCount['save']++;
        return true;
    }
    
    public function delete() {
        $this->callCount['delete']++;
        return true;
    }
    
    public function getCallCount($method){
        return $this->callCount[$method];
    }
    
    public function getAttribute($key) {
        return 'value:' . $key;
    }
    
    public function myRelation() {
        $this->callCount['myRelation']++;
        return $this->relation;
    }

    /**
     * init&get the mock-query-object
     * @return m\MockInterface
     */
    public static function mockQuery() {
        if (self::$query == null) {
            self::$query = m::mock('LavarelQuery');
        }
        return self::$query;
    }
}
