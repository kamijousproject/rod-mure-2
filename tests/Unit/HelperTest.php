<?php
/**
 * Helper Functions Unit Tests
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * Test format_price function
     */
    public function testFormatPrice(): void
    {
        $this->assertEquals('฿1,000,000', format_price(1000000));
        $this->assertEquals('฿500,000', format_price(500000));
        $this->assertEquals('฿0', format_price(0));
    }
    
    /**
     * Test format_number function
     */
    public function testFormatNumber(): void
    {
        $this->assertEquals('1,000,000', format_number(1000000));
        $this->assertEquals('50,000', format_number(50000));
    }
    
    /**
     * Test slugify function
     */
    public function testSlugify(): void
    {
        $this->assertEquals('hello-world', slugify('Hello World'));
        $this->assertEquals('toyota-camry-2022', slugify('Toyota Camry 2022'));
        $this->assertEquals('test-123', slugify('Test 123'));
    }
    
    /**
     * Test str_limit function
     */
    public function testStrLimit(): void
    {
        $text = 'This is a very long text that needs to be truncated';
        
        $this->assertEquals('This is a...', str_limit($text, 10));
        $this->assertEquals($text, str_limit($text, 100));
    }
    
    /**
     * Test e (escape) function
     */
    public function testEscapeHtml(): void
    {
        $this->assertEquals('&lt;script&gt;', e('<script>'));
        $this->assertEquals('&amp;', e('&'));
        $this->assertEquals('Hello World', e('Hello World'));
    }
    
    /**
     * Test year_options function
     */
    public function testYearOptions(): void
    {
        $years = year_options(2020);
        
        $this->assertIsArray($years);
        $this->assertArrayHasKey(2020, $years);
        $this->assertArrayHasKey((int)date('Y'), $years);
    }
}
