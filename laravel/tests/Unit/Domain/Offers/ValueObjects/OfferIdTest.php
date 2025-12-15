<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\Offers\ValueObjects;

use App\Domain\Offers\ValueObjects\OfferId;
use PHPUnit\Framework\TestCase;

final class OfferIdTest extends TestCase
{
    public function test_it_accepts_positive_integer(): void
    {
        $id = new OfferId(1);
        $this->assertSame(1, $id->value());
        $this->assertSame('1', (string)$id);
    }

    public function test_equals_compares_underlying_value(): void
    {
        $a = new OfferId(5);
        $b = new OfferId(5);
        $c = new OfferId(6);
        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_zero_or_negative_is_invalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new OfferId(0);
    }
}
