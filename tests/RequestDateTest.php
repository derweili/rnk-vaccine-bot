<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Derweili\RnkBot\RequestDate;
use Derweili\RnkBot\Vaccines;
use Derweili\RnkBot\Centers;

final class RequestDateTest extends TestCase
{
    public function testShouldReturnTrueIfItemsFound() : void
    {

        $demo_return_data = [
            'status' => 'OK',
            'items' => [
                'demo_item'
            ]
        ];

        $centers = new Centers();
        $vaccines = new Vaccines();

        $demo_center_id = $centers->get_all_ids()[0];
        $demo_vaccine_id = $vaccines->get_all_ids()[0];

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_request'])
            ->getMock();
    
        $mock->method('send_request')
          ->willReturn( $demo_return_data );

        $has_dates = $mock->has_dates();

        $this->assertTrue($has_dates);
    }

    public function testShouldReturnFalseIfNoItemsFound() : void
    {

        $demo_return_data = [
            'status' => 'OK',
            'items' => []
        ];

        $centers = new Centers();
        $vaccines = new Vaccines();

        $demo_center_id = $centers->get_all_ids()[0];
        $demo_vaccine_id = $vaccines->get_all_ids()[0];

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_request'])
            ->getMock();
    
        $mock->method('send_request')
          ->willReturn( $demo_return_data );

        $has_dates = $mock->has_dates();

        $this->assertFalse($has_dates);
    }

    public function testShouldReturnFalseIfStatusIsNotOk() : void
    {

        $demo_return_data = [
            'status' => 'Error'
        ];

        $centers = new Centers();
        $vaccines = new Vaccines();

        $demo_center_id = $centers->get_all_ids()[0];
        $demo_vaccine_id = $vaccines->get_all_ids()[0];

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_request'])
            ->getMock();
    
        $mock->method('send_request')
          ->willReturn( $demo_return_data );

        $has_dates = $mock->has_dates();

        $this->assertFalse($has_dates);
    }

    public function testShouldReturnFalseIfNoStatusFound() : void
    {

        $demo_return_data = [];

        $centers = new Centers();
        $vaccines = new Vaccines();

        $demo_center_id = $centers->get_all_ids()[0];
        $demo_vaccine_id = $vaccines->get_all_ids()[0];

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_request'])
            ->getMock();
    
        $mock->method('send_request')
          ->willReturn( $demo_return_data );

        $has_dates = $mock->has_dates();

        $this->assertFalse($has_dates);
    }

    public function testShouldBuildRequestData() : void
    {

        $demo_return_data = [];

        $centers = new Centers();
        $vaccines = new Vaccines();

        $demo_center_id = $centers->get_all_ids()[0];
        $demo_vaccine_id = $vaccines->get_all_ids()[0];

        // setup mock
        $mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs( [ $demo_center_id, $demo_vaccine_id ] )
            ->setMethods(['send_request'])
            ->getMock();

        $request_data = $mock->get_request_data();

        $exprected_data = [
			'teststationId' => $demo_center_id,
			'vaccineId' => $demo_vaccine_id,
			'selfService' => true,
        ];

        $this->assertEquals($exprected_data, $request_data);
    }
}