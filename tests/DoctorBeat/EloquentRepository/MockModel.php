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
    );
    
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
        
        $query = m::mock('LavarelQuery');
        $query->shouldReceive('get')->andReturn(array($self));
        $query->shouldReceive('first')->andReturn($self);
        
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
}
