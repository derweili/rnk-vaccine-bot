<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Derweili\RnkBot\PushoverNotification;
use Derweili\RnkBot\Vaccines;
use Derweili\RnkBot\Centers;


/**
 * @runTestsInSeparateProcesses
 * 
 * We need to run the tests in separate processes,
 * because we ne define API keys via constants
 * and we need to test cases with different API key settins
 */
final class PushoverNotificationTest extends TestCase
{

    public function setup_app_token() {
        define( 'PUSHOVER_APP_TOKEN', 'XXX' );
    }

    public function setup_user_key() {
        define( 'PUSHOVER_USER_KEY', 'XXX' );
    }

    public function setup_api_key() {
        $this->setup_app_token();
        $this->setup_user_key();
    }

    public function get_data(string $filename) {
        $full_path = __DIR__ . '/data/' . $filename;

        $data = file_get_contents($full_path);
        $data = json_decode($data, true);

        return $data;
    }

    public function get_test_center_id() {
        $centers = new Centers();
        $demo_center_id = $centers->get_all_ids()[0];
        return $demo_center_id;
    }

    public function get_test_vaccine_id() {
        $vaccines = new Vaccines();
        $demo_vaccine_id = $vaccines->get_all_ids()[0];
        return $demo_vaccine_id;
    }

    public function testShouldReturnFalseIfApiTokensAreNotConfigured() {
        $is_configured = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id() ) )->is_configured();

        $this->assertFalse($is_configured);
    }

    public function testShouldReturnFalseIfUserKeyIsNotConfigured() {
        $this->setup_app_token();

        $is_configured = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id() ) )->is_configured();

        $this->assertFalse($is_configured);
    }

    public function testShouldReturnFalseIfApiTokenIsNotConfigured() {
        $this->setup_user_key();

        $is_configured = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id() ) )->is_configured();

        $this->assertFalse($is_configured);
    }

    public function testShouldReturnTrueIfApiKeysAreConfigured() {
        $this->setup_api_key();

        $is_configured = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id() ) )->is_configured();

        $this->assertTrue($is_configured);
    }

    public function testShoudReturnNotificationMessage() {
        $this->setup_api_key();

        $message = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id() ) )->get_available_vaccine_message();

        $this->assertNotEmpty($message);
        $this->assertIsString($message);
    }

    public function testShouldReturnArrayOfStrings() {
        $this->setup_api_key();

        $available_dates = $this->get_data('freeDates.json')['items'];

        $dates = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id(), $available_dates ) )->available_dates_to_date_array();

        $this->assertIsArray($dates);
        $this->assertIsString($dates[0]);
    }

    public function testShouldReturnEmptyArrayWhenAvailableDatesNotSet() {
        $this->setup_api_key();

        $dates = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id() ) )->available_dates_to_date_array();

        $this->assertIsArray($dates);
        $this->assertEmpty($dates);
    }

    public function testShouldReturnFalseWhenAvailableDatesNotSet() {
        $this->setup_api_key();

        $available_dates_message = (new PushoverNotification($this->get_test_center_id(), $this->get_test_vaccine_id() ) )->get_available_dates_message();

        $this->assertFalse($available_dates_message);
    }

    public function testShouldReturnMessageWhenAvailableDatesSet() {
        $this->setup_api_key();

        $available_dates = $this->get_data('freeDates.json')['items'];

        // setup mock
        $mock = $this->getMockBuilder(PushoverNotification::class)
        ->setConstructorArgs( [
            $this->get_test_center_id(),
            $this->get_test_vaccine_id(),
            $available_dates
        ] )
        ->setMethods(['available_dates_to_date_array'])
        ->getMock();

        $mock->method('available_dates_to_date_array')
            ->willReturn( ['Mo 12.01.2022', 'Di 13.01.2022'] );

        $message = $mock->get_available_dates_message();

        $this->assertNotEmpty($message);
        $this->assertIsString($message);
    }
}