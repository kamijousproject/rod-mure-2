<?php
/**
 * Car Model Unit Tests
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Car;

class CarTest extends TestCase
{
    /**
     * Test slug generation
     */
    public function testSlugGeneration(): void
    {
        // Test basic slug
        $title = "Toyota Camry 2.5 HV Premium 2022";
        $slug = slugify($title);
        
        $this->assertEquals('toyota-camry-25-hv-premium-2022', $slug);
    }
    
    /**
     * Test transmission constants
     */
    public function testTransmissionConstants(): void
    {
        $this->assertArrayHasKey('auto', Car::TRANSMISSIONS);
        $this->assertArrayHasKey('manual', Car::TRANSMISSIONS);
        $this->assertEquals('อัตโนมัติ', Car::TRANSMISSIONS['auto']);
        $this->assertEquals('ธรรมดา', Car::TRANSMISSIONS['manual']);
    }
    
    /**
     * Test fuel type constants
     */
    public function testFuelTypeConstants(): void
    {
        $this->assertArrayHasKey('gasoline', Car::FUELS);
        $this->assertArrayHasKey('diesel', Car::FUELS);
        $this->assertArrayHasKey('hybrid', Car::FUELS);
        $this->assertArrayHasKey('electric', Car::FUELS);
        $this->assertArrayHasKey('lpg', Car::FUELS);
    }
    
    /**
     * Test status constants
     */
    public function testStatusConstants(): void
    {
        $this->assertArrayHasKey('draft', Car::STATUSES);
        $this->assertArrayHasKey('pending', Car::STATUSES);
        $this->assertArrayHasKey('published', Car::STATUSES);
        $this->assertArrayHasKey('sold', Car::STATUSES);
        $this->assertArrayHasKey('rejected', Car::STATUSES);
    }
}
