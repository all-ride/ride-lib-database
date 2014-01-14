<?php

namespace pallo\library\database\definition;

use \PHPUnit_Framework_TestCase;

class TableTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $name = 'table';

        $table = new Table($name);

        $this->assertEquals($name, $table->getName());
        $this->assertEquals(array(), $table->getFields());
        $this->assertEquals(array(), $table->getIndexes());
    }

    /**
     * @dataProvider providerConstructThrowsExceptionWhenInvalidNamePassed
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenInvalidNamePassed($value) {
        new Table($value);
    }

    public function providerConstructThrowsExceptionWhenInvalidNamePassed() {
        return array(
            array(''),
            array(array()),
            array($this),
        );
    }

    public function testAddField() {
        $table = new Table('table');
        $field = new Field('field', 'type');

        $table->addField($field);

        $fields = $table->getFields();

        $expected = array($field->getName() => $field);

        $this->assertEquals($expected, $fields);
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testAddFieldThrowsExceptionWhenTryingToAddAnotherFieldWithTheSameName() {
        $table = new Table('table');
        $field = new Field('field', 'type');

        $table->addField($field);
        $table->addField($field);
    }

    public function testSetField() {
        $table = new Table('table');
        $field = new Field('field', 'type');

        $table->addField($field);

        $field2 = new Field('field', 'type2');

        $table->setField($field2);

        $fields = $table->getFields();

        $expected = array($field2->getName() => $field2);

        $this->assertEquals($expected, $fields);
    }

    /**
     * @dataProvider providerGetFieldThrowsExceptionWhenInvalidNameProvided
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testGetFieldThrowsExceptionWhenInvalidNameProvided($name) {
        $table = new Table('table');
        $table->getField($name);
    }

    public function providerGetFieldThrowsExceptionWhenInvalidNameProvided() {
    	return array(
            array(''),
            array('name'),
            array($this),
    	);
    }

    public function testGetFields() {
        $table = new Table('table');
        $field1 = new Field('field1', 'type');
        $field2 = new Field('field2', 'type');

        $table->addField($field1);
        $table->addField($field2);

        $fields = $table->getFields();

        $this->assertEquals(array('field1' => $field1, 'field2' => $field2), $fields);
    }

    public function testHasField() {
        $table = new Table('table');
        $field = new Field('field', 'type');

        $table->addField($field);

        $this->assertTrue($table->hasField('field'));
        $this->assertFalse($table->hasField('unexistant'));
    }

    /**
     * @dataProvider providerHasFieldThrowsExceptionWhenInvalidValuePassed
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testHasFieldThrowsExceptionWhenInvalidValuePassed($value) {
        $table = new Table('table');
        $table->hasField($value);
    }

    public function providerHasFieldThrowsExceptionWhenInvalidValuePassed() {
        return array(
            array(''),
            array(array()),
            array($this),
        );
    }

    public function testGetPrimaryKeys() {
        $table = new Table('table');
        $field1 = new Field('field1', 'type');
        $field1->setIsPrimaryKey(true);
        $field2 = new Field('field2', 'type');
        $field2->setIsPrimaryKey(true);
        $field3 = new Field('field3', 'type');

        $table->addField($field1);
        $table->addField($field2);
        $table->addField($field3);

        $primaryKeys = $table->getPrimaryKeys();

        $this->assertEquals(array('field1' => $field1, 'field2' => $field2), $primaryKeys);
    }

    public function testSetForeignKey() {
        $table = new Table('table');
        $field = new Field('field', 'type');
        $foreignKey = new ForeignKey('field', 'table2', 'id');

        $table->addField($field);
        $table->setForeignKey($foreignKey);

        $this->assertEquals(array('field' => $foreignKey), $table->getForeignKeys());
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testSetForeignKeyThrowsExceptionWhenIndexFieldDoesNotExist() {
        $table = new Table('table');
        $field = new Field('field', 'type');
        $foreignKey = new ForeignKey('index', 'table2', 'id');

        $table->addField($field);
        $table->setForeignKey($foreignKey);
    }

    /**
     * @dataProvider providerGetForeignKeyThrowsExceptionWhenInvalidNameProvided
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testGetForeignKeyThrowsExceptionWhenInvalidNameProvided($name) {
        $table = new Table('table');
        $table->getForeignKey($name);
    }

    public function providerGetForeignKeyThrowsExceptionWhenInvalidNameProvided() {
        return array(
            array(''),
            array('name'),
            array($this),
        );
    }

    public function testHasForeignKey() {
        $table = new Table('table');
        $field = new Field('field', 'type');
        $foreignKey = new ForeignKey('field', 'table2', 'id');

        $table->addField($field);
        $table->setForeignKey($foreignKey);

        $this->assertTrue($table->hasForeignKey('field'));
        $this->assertFalse($table->hasForeignKey('unexistant'));
    }

    /**
     * @dataProvider providerHasForeignKeyThrowsExceptionWhenInvalidValuePassed
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testHasForeignKeyThrowsExceptionWhenInvalidValuePassed($value) {
        $table = new Table('table');
        $table->hasForeignKey($value);
    }

    public function providerHasForeignKeyThrowsExceptionWhenInvalidValuePassed() {
        return array(
            array(''),
            array(array()),
            array($this),
        );
    }

    public function testAddIndex() {
        $table = new Table('table');
        $field = new Field('field', 'type');
        $index = new Index('index', array($field));

        $table->addField($field);
        $table->addIndex($index);

        $indexes = $table->getIndexes();

        $expected = array($index->getName() => $index);

        $this->assertEquals($expected, $indexes);
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testAddIndexThrowsExceptionWhenAddingAnIndexWithTheSameName() {
        $table = new Table('table');
        $field = new Field('field', 'type');
        $index = new Index('index', array($field));

        $table->addField($field);
        $table->addIndex($index);
        $table->addIndex($index);
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testSetIndexThrowsExceptionWhenIndexFieldDoesNotExist() {
        $table = new Table('table');
        $field = new Field('field', 'type');
        $index = new Index('index', array(new Field('field2', 'type')));

        $table->addField($field);
        $table->setIndex($index);
    }

    /**
     * @dataProvider providerGetIndexThrowsExceptionWhenInvalidNameProvided
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testGetIndexThrowsExceptionWhenInvalidNameProvided($name) {
        $table = new Table('table');
        $table->getIndex($name);
    }

    public function providerGetIndexThrowsExceptionWhenInvalidNameProvided() {
        return array(
            array(''),
            array('name'),
            array($this),
        );
    }

    public function testHasIndex() {
        $table = new Table('table');
        $field = new Field('field', 'type');
        $index = new Index('index', array($field));

        $table->addField($field);
        $table->addIndex($index);

        $this->assertTrue($table->hasIndex('index'));
        $this->assertFalse($table->hasIndex('unexistant'));
    }

    /**
     * @dataProvider providerHasIndexThrowsExceptionWhenInvalidValuePassed
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testHasIndexThrowsExceptionWhenInvalidValuePassed($value) {
        $table = new Table('table');
        $table->hasIndex($value);
    }

    public function providerHasIndexThrowsExceptionWhenInvalidValuePassed() {
        return array(
            array(''),
            array(array()),
            array($this),
        );
    }

}