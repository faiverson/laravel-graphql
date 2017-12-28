<?php

use Folklore\Support\Field;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use Illuminate\Validation\Validator;

class MutationTest extends FieldTest
{
    protected function getFieldClass()
    {
        return UpdateExampleMutationWithInputType::class;
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('graphql.types', [
            'Example' => ExampleType::class,
            'ExampleValidationInputObject' => ExampleValidationInputObject::class,
            'ExampleNestedValidationInputObject' => ExampleNestedValidationInputObject::class,
        ]);
    }

    /**
     * Test get rules
     *
     * @test
     */
    public function testGetRules()
    {
        $class = $this->getFieldClass();
        $field = new $class();
        $rules = $field->getRules();

        $this->assertInternalType('array', $rules);
        $this->assertArrayHasKey('test', $rules);
        $this->assertArrayHasKey('test_with_rules', $rules);
        $this->assertArrayHasKey('test_with_rules_closure', $rules);
        $this->assertEquals($rules['test'], ['required']);
        $this->assertEquals($rules['test_with_rules'], ['required']);
        $this->assertEquals($rules['test_with_rules_closure'], ['required']);
        $this->assertEquals($rules['test_with_rules_input_object'], ['required']);
        $this->assertEquals(array_get($rules, 'test_with_rules_input_object.val'), ['required']);
        $this->assertEquals(array_get($rules, 'test_with_rules_input_object.nest'), ['required']);
        $this->assertEquals(array_get($rules, 'test_with_rules_input_object.nest.email'), ['email']);
        $this->assertEquals(array_get($rules, 'test_with_rules_input_object.list'), ['required']);
        $this->assertEquals(array_get($rules, 'test_with_rules_input_object.list.*.email'), ['email']);
    }

    /**
     * Test resolve
     *
     * @test
     */
    public function testResolve()
    {
        $class = $this->getFieldClass();
        $field = $this->getMockBuilder($class)
                    ->setMethods(['resolve'])
                    ->getMock();

        $field->expects($this->once())
            ->method('resolve');

        $attributes = $field->getAttributes();
        $attributes['resolve'](null, [
            'test' => 'test',
            'test_with_rules' => 'test',
            'test_with_rules_closure' => 'test',
            'test_with_rules_input_object' => [
                'val' => 'test',
                'nest' => ['email' => 'test@test.com'],
                'list' => [
                    ['email' => 'test@test.com'],
                ],
            ],
        ], [], null);
    }

    /**
     * Test resolve throw validation error
     *
     * @test
     * @expectedException Illuminate\Validation\ValidationException
     */
    public function testResolveThrowValidationError()
    {
        $class = $this->getFieldClass();
        $field = new $class();

        $attributes = $field->getAttributes();
        $attributes['resolve'](null, [], [], null);
    }

    /**
     * Test validation error
     *
     * @test
     */
    public function testValidationError()
    {
        $class = $this->getFieldClass();
        $field = new $class();

        $attributes = $field->getAttributes();

        try {
            $attributes['resolve'](null, [], [], null);
        } catch (Illuminate\Validation\ValidationException $e) {
            $messages = $e->errors();
            $this->assertTrue(array_key_exists('test', $messages));
        }
    }
}
