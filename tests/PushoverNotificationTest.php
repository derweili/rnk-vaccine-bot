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
}