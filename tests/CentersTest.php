<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Derweili\RnkBot\Centers;

final class CentersTest extends TestCase
{
    public function testShouldReturnArrayOfCenters() : void
    {
        $centers = Centers::get_centers();

        $this->assertIsArray($centers);
    }

    public function testShouldGetCenterById() : void
    {
        $center = Centers::get_center_by_id(51);

        // is string
        $this->assertIsString($center);

        $exprected_name = "Impfung Bammental - Vertusplatz 1, 69245 Bammental";

        $this->assertEquals($exprected_name, $center);
    }

    public function testShouldReturnFalseIfCenterIdInvalid() : void
    {
        $center = Centers::get_center_by_id(999999999);

        $this->assertFalse($center);
    }

    public function testShouldReturnArrayOfCenterIds() : void
    {
        $center_ids = (new Centers())->get_all_ids();

        $this->assertIsArray($center_ids);
        
        // test if is array of integers
        foreach ($center_ids as $id) {
            $this->assertIsInt($id);
        }
    }
}