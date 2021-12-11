<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Derweili\RnkBot\Vaccines;
use Derweili\RnkBot\Centers;
use Derweili\RnkBot\RequestDate;
use Derweili\RnkBot\Bot;

final class BotTest extends TestCase
{

    public function get_data(string $filename) {
        $full_path = __DIR__ . '/data/' . $filename;

        $data = file_get_contents($full_path);
        $data = json_decode($data, true);

        return $data;
    }

    public function testShouldAddAvailableAppointmentForEachAvailableCenter() {
        $centers = new Centers();

        $vaccines = new Vaccines();

        // number of centers
        $number_of_centers = count($centers->get_all_ids());

        // number of vaccines
        $number_of_vaccines = count($vaccines->get_all_ids());

        $demo_free_dates_response = $this->get_data('freeDates.json');

        // create request date mock
        $request_date_mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs([1, 1])
            ->setMethods(['send_available_dates_request'])
            ->getMock();

        $request_date_mock->method('send_available_dates_request')
            ->willReturn( $demo_free_dates_response );

        
        // create bot mock
        $botMock = $this->getMockBuilder(Bot::class)
            ->setConstructorArgs([$centers, $vaccines])
            ->setMethods(['get_request_date_instance', 'add_available_appointment', 'sleep'])
            ->getMock();

        // don't wait between calls so test runs faster
        $botMock->method('sleep')
            ->willReturn(null);

        $botMock->method('get_request_date_instance')
            ->willReturn($request_date_mock);

        /**
         * Should send notification for each center and vaccine because all are available in our test
         */
        $botMock->expects($this->exactly($number_of_centers * $number_of_vaccines))
            ->method('add_available_appointment');

        $botMock->run();
    }

    public function testShouldCallMaybeNotify() {
        $centers = new Centers();

        $vaccines = new Vaccines();

        // number of centers
        $number_of_centers = count($centers->get_all_ids());

        // number of vaccines
        $number_of_vaccines = count($vaccines->get_all_ids());

        $demo_free_dates_response = $this->get_data('freeDates.json');

        // create request date mock
        $request_date_mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs([1, 1])
            ->setMethods(['send_available_dates_request'])
            ->getMock();

        $request_date_mock->method('send_available_dates_request')
            ->willReturn( $demo_free_dates_response );

        
        // create bot mock
        $botMock = $this->getMockBuilder(Bot::class)
            ->setConstructorArgs([$centers, $vaccines])
            ->setMethods(['get_request_date_instance', 'maybe_notify', 'sleep'])
            ->getMock();

        // don't wait between calls so test runs faster
        $botMock->method('sleep')
            ->willReturn(null);

        $botMock->method('get_request_date_instance')
            ->willReturn($request_date_mock);

        /**
         * Should send notification for each center and vaccine because all are available in our test
         */
        $botMock->expects($this->once())
            ->method('maybe_notify');

        $botMock->run();
    }

    public function testShouldNeverCallAddAppointmentWhenDatesNotAvailable() {
        $centers = new Centers();

        $vaccines = new Vaccines();

        // number of centers
        $number_of_centers = count($centers->get_all_ids());

        // number of vaccines
        $number_of_vaccines = count($vaccines->get_all_ids());

        $demo_free_dates_response = $this->get_data('noFreeDates.json');

        // create request date mock
        $request_date_mock = $this->getMockBuilder(RequestDate::class)
            ->setConstructorArgs([1, 1])
            ->setMethods(['send_available_dates_request'])
            ->getMock();

        $request_date_mock->method('send_available_dates_request')
            ->willReturn( $demo_free_dates_response );

        
        // create bot mock
        $botMock = $this->getMockBuilder(Bot::class)
            ->setConstructorArgs([$centers, $vaccines])
            ->setMethods(['get_request_date_instance', 'testShouldAddAvailableAppointmentForEachAvailableCenter', 'sleep'])
            ->getMock();

        // don't wait between calls so test runs faster
        $botMock->method('sleep')
            ->willReturn(null);

        $botMock->method('get_request_date_instance')
            ->willReturn($request_date_mock);

        $botMock->expects($this->never())
            ->method('testShouldAddAvailableAppointmentForEachAvailableCenter');

        $botMock->run();
    }

    public function testShouldSetAndReturnAvailableAppointments() {
        $centers = new Centers();

        $vaccines = new Vaccines();

        $bot = new Bot($centers, $vaccines);

        // should add initial center
        $bot->add_available_appointment(1, 2);
        $this->assertEquals([1 => [2]], $bot->get_available_appointments());
        
        // should add another center
        $bot->add_available_appointment(2, 3);
        $this->assertEquals([1 => [2], 2 => [3]], $bot->get_available_appointments());

        // should add another vaccine to center 1
        $bot->add_available_appointment(1, 4);
        $this->assertEquals([1 => [2, 4], 2 => [3]], $bot->get_available_appointments());
    }

    public function testShouldSendCombinednotificationWhenAppointmentsAvailable() {
        $centers = new Centers();

        $vaccines = new Vaccines();

        // create bot mock
        $botMock = $this->getMockBuilder(Bot::class)
            ->setConstructorArgs([$centers, $vaccines])
            ->setMethods(['send_combined_notification'])
            ->getMock();

        $botMock->expects($this->once())
            ->method('send_combined_notification');
        
        $botMock->add_available_appointment(1, 2);

        $botMock->maybe_notify();
    }

    public function testShouldNotSendCombinednotificationWhenNoAppointmentsAvailable() {
        $centers = new Centers();

        $vaccines = new Vaccines();

        // create bot mock
        $botMock = $this->getMockBuilder(Bot::class)
            ->setConstructorArgs([$centers, $vaccines])
            ->setMethods(['send_combined_notification'])
            ->getMock();

        $botMock->expects($this->never())
            ->method('send_combined_notification');
        

        $botMock->maybe_notify();
    }
}