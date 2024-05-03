<?php

namespace Ruark\LaravelInn\Tests;

use Tests\TestCase;
use Ruark\LaravelInn\Exceptions\InnValidationException;
use Ruark\LaravelInn\InnValidator;

class InnValidatorTest extends TestCase
{
    /**
     * @return void
     * @throws InnValidationException
     */
    public function testInnValidation(): void
    {
        $validator = new InnValidator();

        // Яндекс
        $this->assertTrue($validator->validate('7736207543', []));
        $this->assertTrue($validator->validate('7736207543', ['l']));
        $this->assertFalse($validator->validate('7736207543', ['i']));
        $this->assertFalse($validator->validate('7736207540', []));

        // ИП Шевчук Ю.Ю.
        $this->assertTrue($validator->validate('780154550318', []));
        $this->assertTrue($validator->validate('780154550318', ['i']));
        $this->assertFalse($validator->validate('780154550318', ['l']));
        $this->assertFalse($validator->validate('780154550310', []));
    }

    /**
     * @return void
     * @throws InnValidationException
     */
    public function testInnValidationException(): void
    {
        $this->expectException(InnValidationException::class);

        $validator = new InnValidator();
        $validator->validate('7736207543', ['blablabla']);
    }
}
