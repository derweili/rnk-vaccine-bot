<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Derweili\RnkBot\Vaccines;

final class VaccinesTest extends TestCase
{
    public function testShouldGetArrayOfVaccines() : void
    {
        $vaccines = Vaccines::get_vaccines();

        $this->assertIsArray($vaccines);
    }

    public function testShouldGetVaccineById() : void
    {
        $vaccine = Vaccines::get_vaccine_by_id(2);

        $this->assertIsString($vaccine);

        $exprected_name = "Biontech";

        $this->assertEquals($exprected_name, $vaccine);
    }

    public function testShouldReturnFalseIfVaccineIdInvalid() : void
    {
        $vaccine = Vaccines::get_vaccine_by_id(99);

        $this->assertFalse($vaccine);
    }

    public function testShouldReturnArrayOfCenterIds() : void
    {
        $vaccine_ids = (new Vaccines())->get_all_ids();

        $this->assertIsArray($vaccine_ids);
        
        // test if is array of integers
        foreach ($vaccine_ids as $id) {
            $this->assertIsInt($id);
        }
    }
}