<?php

namespace DoctorBeat\EloquentRepository;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use stdClass;

class EloquentRepositoryTest extends PHPUnit_Framework_TestCase {
    
    const MODEL_NAME = 'DoctorBeat\\EloquentRepository\\MockModel';

    /**
     * @var EloquentRepository
     */
    protected $object;
    protected $query;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->query = 
        $this->object = new EloquentRepository(self::MODEL_NAME);
    }

    public function testAll() {
        $all = $this->object->all();
        
        $this->assertCount(1, $all);
        $this->assertClassName($all[0]);
        $this->assertSame('first', $all[0]->name);
    }

    public function testFind() {
        $id = 345;
        $found = $this->object->find($id);
        
        $this->assertSame($id, $found->id);
        $this->assertClassName($found);
        $this->assertSame('found ' . $id, $found->name);
    }

    public function testWhere() {
        $name = 'my name';
        $found = $this->object->where_name($name)->get();
        
        $this->assertCount(1, $found);
        $this->assertSame($name, $found[0]->name);
        $this->assertClassName($found[0]);

        $first = $this->object->where_name($name)->first();
        $this->assertSame($name, $first->name);
   }
   
   public function testSave() {
       $modelName = self::MODEL_NAME;
       $entity = new $modelName();      /** @var MockModel Description */
       
       $result = $this->object->save($entity);
       $this->assertTrue($result);
       $this->assertSame(1, $entity->getCallCount('save'));
   }

   public function testDelete() {
       $modelName = self::MODEL_NAME;
       $entity = new $modelName();      /** @var MockModel Description */
       
       $result = $this->object->delete($entity);
       $this->assertTrue($result);
       $this->assertSame(1, $entity->getCallCount('delete'));
   }

   public function testGetAttribute() {
       $modelName = self::MODEL_NAME;
       $entity = new $modelName();      /** @var MockModel Description */
       
       $key = 'relation';
       $result = $this->object->getAttribute($entity, $key);
       $this->assertSame('value:'.$key, $result);
   }

   public function testGetRelation() {
       $modelName = self::MODEL_NAME;
       $entity = new $modelName();      /** @var MockModel Description */
       
       $mock = m::mock('LaravelRelation');
       $entity->setRelation($mock);

       $result = $this->object->myRelation($entity);
       $this->assertSame($mock, $result);
       $this->assertSame(1, $entity->getCallCount('myRelation'));
   }
   
   public function testAddTo() {
       $modelName = self::MODEL_NAME;
       $entity = new $modelName();      /** @var MockModel Description */
       
       $obj = new \stdClass();
       $obj->name = rand(1, 10000);
       
       $mock = m::mock('LaravelRelation');
       $mock->shouldReceive('save')->once()->with($obj)->andReturn($obj);
       $entity->setRelation($mock);
       
       //add the object to the relation 'myRelation':
       $result = $this->object->myRelation($entity, $obj);  
       
       $this->assertSame(1, $entity->getCallCount('myRelation'));
       $this->assertSame($obj, $result);
   }

   public function testAddToList() {
       $modelName = self::MODEL_NAME;
       $entity = new $modelName();      /** @var MockModel Description */
       
       $objs[] = new \stdClass();
       $objs[] = new \stdClass();
       
       $mock = m::mock('LaravelRelation');
       $mock->shouldReceive('saveMany')->once()->with($objs)->andReturn($objs);
       $entity->setRelation($mock);

       //add the object to the relation 'myRelation':
       $result = $this->object->myRelation($entity, $objs);  
       
       $this->assertSame(1, $entity->getCallCount('myRelation'));
       $this->assertSame($objs, $result);
   }
    
    public function testCanWeMockIt() {
        $mock = m::mock('DoctorBeat\\EloquentRepository\\Repository');
        
        $user = new MockModel();
        $mock->shouldReceive('save')->with($user)->andReturn(true)->once();
        $mock->shouldReceive('where_email')->with('xyz')->andReturn($user)->once();
        
        $this->assertTrue($mock->save($user));
        $this->assertSame($user, $mock->where_email('xyz'));
    }

    public function testDeleteWhere() {
        $mockQuery = MockModel::mockQuery();
        $mockQuery->shouldReceive('delete')->once();

        $this->object->deleteWhere('1', '=', '1');
    }

    public function testDeleteWhereName() {
        $mockQuery = MockModel::mockQuery();
        $mockQuery->shouldReceive('delete')->once();

        $this->object->deleteWhere_name('xyz');
    }
    
    private function assertClassName($object) {
        $this->assertSame(self::MODEL_NAME, get_class($object));
    }

}
