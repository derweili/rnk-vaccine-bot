<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Derweili\RnkBot\RequestDate;
use Derweili\RnkBot\Vaccines;
use Derweili\RnkBot\Centers;

final class RequestDateTest extends TestCase
{

    public function get_demo_center_id() {
        $centers = new Centers();
        $demo_center_id = $centers->get_all_ids()[0];
        return $demo_center_id;
    }

    public function get_demo_vaccine_id() {
        $vaccines = new Vaccines();
        $demo_vaccine_id = $vaccines->get_all_ids()[0];
        return $demo_vaccine_id;
    }

    public function get_data(string $filename) {
        $full_path = __DIR__ . '/data/' . $filename;

        $data = file_get_contents($full_path);
        $data = json_decode($data, true);

        return $data;
    }

    public function get_new_class_instance() {
        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        $request_date_object = new RequestDate( $demo_center_id, $demo_vaccine_id );

        return $request_date_object;
    }

    public function get_class_reflection_with_properties( $properties ) {

        $request_date_object = $this->get_new_class_instance();

        $reflection = new ReflectionClass($request_date_object);

        foreach ($properties as $name => $value) {
            $reflection_property = $reflection->getProperty($name);
            $reflection_property->setAccessible(true);
            $reflection_property->setValue($request_date_object, $value);
        }

        return $request_date_object;
    }

    /**
     * @test is_dates_request_successfull()
     */
    public function testShouldReturnFalseIfStatusIsNotOk() : void
    {

        $demo_request_response = [
            'status' => 'Error'
        ];


        $request_date_object = $this->get_new_class_instance();

        $is_request_successfull = $request_date_object->is_request_successfull(  $demo_request_response );

        $this->assertFalse($is_request_successfull);
    }

    /**
     * @test is_dates_request_successfull()
     */
    public function testShouldReturnFalseIfNoStatusFound() : void
    {
        $demo_request_response = [
            'items' => []
        ];

        $request_date_object = $this->get_new_class_instance();

        $is_request_successfull = $request_date_object->is_request_successfull(  $demo_request_response );

        $this->assertFalse($is_request_successfull);
    }

    /**
     * @test is_dates_request_successfull()
     */
    public function testShouldReturnFalseIfItemsNotSetFound() : void
    {
        $demo_request_response = [
            'status' => 'OK'
        ];

        $request_date_object = $this->get_new_class_instance();

        $is_request_successfull = $request_date_object->is_request_successfull(  $demo_request_response );

        $this->assertFalse($is_request_successfull);
    }

    public function testShouldReturnFalseIfNoItemsFound() : void
    {
        $demo_items = $this->get_data('noFreeDates.json')['items'];

        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['get_available_dates'])
            ->getMock();
    
        $mock->method('get_available_dates')
          ->willReturn( $demo_items );

        $has_dates = $mock->has_dates();

        $this->assertFalse($has_dates);
    }

    public function testShouldReturnTrueIfItemsFound() : void
    {   
        $demo_items = $this->get_data('freeDates.json')['items'];

        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['get_available_dates'])
            ->getMock();
    
        $mock->method('get_available_dates')
          ->willReturn(  $demo_items );

        $has_dates = $mock->has_dates();

        $this->assertTrue($has_dates);
    }

    public function testShouldBuildRequestData() : void
    {

        $demo_return_data = [];

        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_available_dates_request'])
            ->getMock();

        $request_data = $mock->get_request_data();

        $exprected_data = [
			'teststationId' => $demo_center_id,
			'vaccineId' => $demo_vaccine_id,
			'selfService' => true,
        ];

        $this->assertEquals($exprected_data, $request_data);
    }

    public function testShouldReturnAvailableDates() : void
    {   
        $demo_response = $this->get_data('freeDates.json');

        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_available_dates_request'])
            ->getMock();
    
        $mock->method('send_available_dates_request')
          ->willReturn(  $demo_response );

        $available_dates = $mock->get_available_dates();


        // should be same as input items
        $this->assertEquals($demo_response['items'], $available_dates);
    }

    public function testShouldReturnAlreadyStoredItems() : void
    {
        $test_available_dates = [
            'date1',
            'date2'
        ];

        // RequestDate
        $request_date_object = $this->get_class_reflection_with_properties([
            'requested_date' => true,
            'available_dates' =>  $test_available_dates
        ]);


        $available_dates = $request_date_object->get_available_dates();

        // should be same as input items
        $this->assertEquals($test_available_dates, $available_dates);
    }

    public function testShouldReturnAvailableTimes() : void
    {   
        $demo_response = $this->get_data('freeTimes.json');

        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_available_times_request'])
            ->getMock();
    
        $mock->method('send_available_times_request')
          ->willReturn(  $demo_response );

        $available_dates = $mock->get_available_times('30.12.2021');

        // should be same as input items
        $this->assertEquals($demo_response['items'], $available_dates);
    }

    public function testShouldReturnAlreadyStoredTimes() : void
    {
        $test_available_times = [
            '12:00',
            '13:00'
        ];

        // RequestDate
        $request_date_object = $this->get_class_reflection_with_properties([
            'requested_times' => true,
            'available_times' =>  $test_available_times
        ]);

        $available_times = $request_date_object->get_available_times('30.12.2021');

        // should be same as input items
        $this->assertEquals($test_available_times, $available_times);
    }

    public function testShouldReturnTrueIfTimesAvailable() {

        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['get_available_times'])
            ->getMock();
    
        $mock->method('get_available_times')
          ->willReturn( ['12:30', '14:15'] );

        $this->assertTrue($mock->has_times('30.12.2021'));
    }

    public function testShouldReturnFalseIfTimesNotAvailable() {
            
        $demo_center_id = $this->get_demo_center_id();
        $demo_vaccine_id = $this->get_demo_vaccine_id();

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['get_available_times'])
            ->getMock();
    
        $mock->method('get_available_times')
        ->willReturn( [] );

        $this->assertFalse($mock->has_times('30.12.2021'));
    }
}