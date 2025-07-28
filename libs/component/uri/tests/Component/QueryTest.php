<?php

declare(strict_types=1);

namespace Boson\Component\Uri\Tests\Component;

use Boson\Component\Uri\Component\Query;
use Boson\Component\Uri\Tests\TestCase;
use Boson\Contracts\Uri\Component\QueryInterface;
use PHPUnit\Framework\Attributes\Group;

#[Group('boson-php/uri')]
final class QueryTest extends TestCase
{
    public function testConstructorWithEmptyParameters(): void
    {
        $query = new Query();

        self::assertSame(0, $query->count());
        self::assertSame('', (string) $query);
        self::assertSame('', $query->toString());
        self::assertSame([], $query->toArray());
    }

    public function testConstructorWithSingleParameter(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertSame(1, $query->count());
        self::assertSame('name=value', (string) $query);
        self::assertSame('name=value', $query->toString());
        self::assertSame(['name' => 'value'], $query->toArray());
    }

    public function testConstructorWithMultipleParameters(): void
    {
        $query = new Query(['name' => 'value', 'age' => '25']);

        self::assertSame(2, $query->count());
        self::assertSame('name=value&age=25', (string) $query);
        self::assertSame('name=value&age=25', $query->toString());
        self::assertSame(['name' => 'value', 'age' => '25'], $query->toArray());
    }

    public function testConstructorWithArrayParameter(): void
    {
        $query = new Query(['tags' => ['php', 'test']]);

        self::assertSame(1, $query->count());
        self::assertSame('tags%5B0%5D=php&tags%5B1%5D=test', (string) $query);
        self::assertSame('tags%5B0%5D=php&tags%5B1%5D=test', $query->toString());
        self::assertSame(['tags' => ['php', 'test']], $query->toArray());
    }

    public function testConstructorWithIterator(): void
    {
        $iterator = new \ArrayIterator(['name' => 'value', 'age' => '25']);
        $query = new Query($iterator);

        self::assertSame(2, $query->count());
        self::assertSame('name=value&age=25', (string) $query);
        self::assertSame('name=value&age=25', $query->toString());
        self::assertSame(['name' => 'value', 'age' => '25'], $query->toArray());
    }

    public function testHasWithExistingKey(): void
    {
        $query = new Query(['name' => 'value', 'age' => '25']);

        self::assertTrue($query->has('name'));
        self::assertTrue($query->has('age'));
    }

    public function testHasWithNonExistingKey(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertFalse($query->has('age'));
        self::assertFalse($query->has(''));
    }

    public function testHasWithEmptyQuery(): void
    {
        $query = new Query();

        self::assertFalse($query->has('name'));
    }

    public function testGetWithExistingKey(): void
    {
        $query = new Query(['name' => 'value', 'age' => '25']);

        self::assertSame('value', $query->get('name'));
        self::assertSame('25', $query->get('age'));
    }

    public function testGetWithNonExistingKey(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertNull($query->get('age'));
    }

    public function testGetWithNonExistingKeyAndDefault(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertSame('default', $query->get('age', 'default'));
    }

    public function testGetWithArrayParameter(): void
    {
        $query = new Query(['tags' => ['php', 'test']]);

        self::assertSame('php', $query->get('tags'));
    }

    public function testGetWithEmptyArrayParameter(): void
    {
        $query = new Query(['tags' => []]);

        self::assertSame('', $query->get('tags'));
    }

    public function testGetAsIntWithValidInteger(): void
    {
        $query = new Query(['age' => '25', 'count' => '0']);

        self::assertSame(25, $query->getAsInt('age'));
        self::assertSame(0, $query->getAsInt('count'));
    }

    public function testGetAsIntWithInvalidInteger(): void
    {
        $query = new Query(['age' => 'not-a-number', 'count' => '25.5']);

        self::assertNull($query->getAsInt('age'));
        self::assertNull($query->getAsInt('count'));
    }

    public function testGetAsIntWithNonExistingKey(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertNull($query->getAsInt('age'));
    }

    public function testGetAsIntWithNonExistingKeyAndDefault(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertSame(42, $query->getAsInt('age', 42));
    }

    public function testGetAsIntWithArrayParameter(): void
    {
        $query = new Query(['numbers' => ['25', '30']]);

        self::assertSame(25, $query->getAsInt('numbers'));
    }

    public function testGetAsArrayWithArrayParameter(): void
    {
        $query = new Query(['tags' => ['php', 'test', 'unit']]);

        self::assertSame(['php', 'test', 'unit'], $query->getAsArray('tags'));
    }

    public function testGetAsArrayWithStringParameter(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertSame(['value'], $query->getAsArray('name'));
    }

    public function testGetAsArrayWithNonExistingKey(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertSame([], $query->getAsArray('age'));
    }

    public function testGetAsArrayWithNonExistingKeyAndDefault(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertSame(['default'], $query->getAsArray('age', ['default']));
    }

    public function testGetAsArrayWithEmptyArrayParameter(): void
    {
        $query = new Query(['tags' => []]);

        self::assertSame([], $query->getAsArray('tags'));
    }

    public function testToArray(): void
    {
        $parameters = ['name' => 'value', 'age' => '25', 'tags' => ['php', 'test']];
        $query = new Query($parameters);

        self::assertSame($parameters, $query->toArray());
    }

    public function testToArrayWithEmptyQuery(): void
    {
        $query = new Query();

        self::assertSame([], $query->toArray());
    }

    public function testGetIterator(): void
    {
        $query = new Query(['name' => 'value', 'tags' => ['php', 'test']]);

        $iterator = $query->getIterator();
        self::assertInstanceOf(\Traversable::class, $iterator);

        $result = [];
        foreach ($iterator as $key => $value) {
            $result[$key] = $value;
        }

        $expected = [
            'name' => 'value',
            'tags[0]' => 'php',
            'tags[1]' => 'test',
        ];

        self::assertSame($expected, $result);
    }

    public function testIteration(): void
    {
        $query = new Query(['name' => 'value', 'tags' => ['php', 'test']]);

        $result = [];
        foreach ($query as $key => $value) {
            $result[$key] = $value;
        }

        $expected = [
            'name' => 'value',
            'tags[0]' => 'php',
            'tags[1]' => 'test',
        ];

        self::assertSame($expected, $result);
    }

    public function testCount(): void
    {
        $query = new Query(['name' => 'value', 'age' => '25']);

        self::assertSame(2, $query->count());
        self::assertSame(2, \count($query));
    }

    public function testCountWithEmptyQuery(): void
    {
        $query = new Query();

        self::assertSame(0, $query->count());
        self::assertSame(0, \count($query));
    }

    public function testCountWithArrayParameter(): void
    {
        $query = new Query(['tags' => ['php', 'test']]);

        self::assertSame(1, $query->count());
        self::assertSame(1, \count($query));
    }

    public function testEqualsWithSameInstance(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertTrue($query->equals($query));
    }

    public function testEqualsWithIdenticalQuery(): void
    {
        $query1 = new Query(['name' => 'value', 'age' => '25']);
        $query2 = new Query(['name' => 'value', 'age' => '25']);

        self::assertTrue($query1->equals($query2));
        self::assertTrue($query2->equals($query1));
    }

    public function testEqualsWithDifferentParameters(): void
    {
        $query1 = new Query(['name' => 'value']);
        $query2 = new Query(['name' => 'different']);

        self::assertFalse($query1->equals($query2));
        self::assertFalse($query2->equals($query1));
    }

    public function testEqualsWithDifferentParameterCount(): void
    {
        $query1 = new Query(['name' => 'value']);
        $query2 = new Query(['name' => 'value', 'age' => '25']);

        self::assertFalse($query1->equals($query2));
        self::assertFalse($query2->equals($query1));
    }

    public function testEqualsWithArrayVsString(): void
    {
        $query1 = new Query(['tags' => ['php', 'test']]);
        $query2 = new Query(['tags' => 'php']);

        self::assertFalse($query1->equals($query2));
        self::assertFalse($query2->equals($query1));
    }

    public function testEqualsWithDifferentTypes(): void
    {
        $query = new Query(['name' => 'value']);
        $other = 'not a query';

        self::assertFalse($query->equals($other));
    }

    public function testEqualsWithNull(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertFalse($query->equals(null));
    }

    public function testEqualsWithEmptyQueries(): void
    {
        $query1 = new Query();
        $query2 = new Query();

        self::assertTrue($query1->equals($query2));
        self::assertTrue($query2->equals($query1));
    }

    public function testEqualsWithEmptyVsNonEmptyQuery(): void
    {
        $query1 = new Query();
        $query2 = new Query(['name' => 'value']);

        self::assertFalse($query1->equals($query2));
        self::assertFalse($query2->equals($query1));
    }

    public function testImplementsQueryInterface(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertInstanceOf(QueryInterface::class, $query);
    }

    public function testImplementsIteratorAggregate(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertInstanceOf(\IteratorAggregate::class, $query);
    }

    public function testImplementsCountable(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertInstanceOf(\Countable::class, $query);
    }

    public function testUrlEncodeParameters(): void
    {
        $query = new Query(['user name' => 'user@domain.com']);

        self::assertSame('user%20name=user%40domain.com', (string) $query);
        self::assertSame('user%20name=user%40domain.com', $query->toString());
    }

    public function testUrlEncodeSpecialCharacters(): void
    {
        $query = new Query(['param/name' => 'value?test']);

        self::assertSame('param%2Fname=value%3Ftest', (string) $query);
        self::assertSame('param%2Fname=value%3Ftest', $query->toString());
    }

    public function testUrlEncodeUnicodeCharacters(): void
    {
        $query = new Query(['пользователь' => 'résumé']);

        self::assertSame('%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C=r%C3%A9sum%C3%A9', (string) $query);
        self::assertSame('%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8C=r%C3%A9sum%C3%A9', $query->toString());
    }

    public function testUrlEncodeEmoji(): void
    {
        $query = new Query(['user😀name' => 'pass😀word']);

        self::assertSame('user%F0%9F%98%80name=pass%F0%9F%98%80word', (string) $query);
        self::assertSame('user%F0%9F%98%80name=pass%F0%9F%98%80word', $query->toString());
    }

    public function testUrlEncodeArrayParameters(): void
    {
        $query = new Query(['tags' => ['php test', 'unit test']]);

        self::assertSame('tags%5B0%5D=php%20test&tags%5B1%5D=unit%20test', (string) $query);
        self::assertSame('tags%5B0%5D=php%20test&tags%5B1%5D=unit%20test', $query->toString());
    }

    public function testUrlEncodeMixedParameters(): void
    {
        $query = new Query([
            'name' => 'value',
            'tags' => ['php', 'test'],
            'user name' => 'user@domain.com',
        ]);

        $result = (string) $query;
        self::assertStringContainsString('name=value', $result);
        self::assertStringContainsString('tags%5B0%5D=php', $result);
        self::assertStringContainsString('tags%5B1%5D=test', $result);
        self::assertStringContainsString('user%20name=user%40domain.com', $result);
    }

    public function testEdgeCaseEmptyKey(): void
    {
        $query = new Query(['' => 'value']);

        self::assertSame(1, $query->count());
        self::assertSame('=value', (string) $query);
        self::assertSame('=value', $query->toString());
        self::assertTrue($query->has(''));
        self::assertSame('value', $query->get(''));
    }

    public function testEdgeCaseEmptyValue(): void
    {
        $query = new Query(['name' => '']);

        self::assertSame(1, $query->count());
        self::assertSame('name=', (string) $query);
        self::assertSame('name=', $query->toString());
        self::assertTrue($query->has('name'));
        self::assertSame('', $query->get('name'));
    }

    public function testEdgeCaseEmptyKeyAndValue(): void
    {
        $query = new Query(['' => '']);

        self::assertSame(1, $query->count());
        self::assertSame('=', (string) $query);
        self::assertSame('=', $query->toString());
        self::assertTrue($query->has(''));
        self::assertSame('', $query->get(''));
    }

    public function testEdgeCaseNumericKeys(): void
    {
        $query = new Query(['123' => 'value', '456' => 'test']);

        self::assertSame(2, $query->count());
        self::assertSame('123=value&456=test', (string) $query);
        self::assertSame('123=value&456=test', $query->toString());
        self::assertTrue($query->has('123'));
        self::assertTrue($query->has('456'));
        self::assertSame('value', $query->get('123'));
        self::assertSame('test', $query->get('456'));
    }

    public function testEdgeCaseNumericValues(): void
    {
        $query = new Query(['count' => '0', 'limit' => '100']);

        self::assertSame(2, $query->count());
        self::assertSame('count=0&limit=100', (string) $query);
        self::assertSame('count=0&limit=100', $query->toString());
        self::assertSame(0, $query->getAsInt('count'));
        self::assertSame(100, $query->getAsInt('limit'));
    }

    public function testEdgeCaseSpecialCharactersInKeys(): void
    {
        $query = new Query(['user-name' => 'value', 'user_name' => 'test']);

        self::assertSame(2, $query->count());
        self::assertSame('user-name=value&user_name=test', (string) $query);
        self::assertSame('user-name=value&user_name=test', $query->toString());
        self::assertTrue($query->has('user-name'));
        self::assertTrue($query->has('user_name'));
    }

    public function testEdgeCaseSpecialCharactersInValues(): void
    {
        $query = new Query(['name' => 'user-name', 'email' => 'user@domain.com']);

        self::assertSame(2, $query->count());
        self::assertSame('name=user-name&email=user%40domain.com', (string) $query);
        self::assertSame('name=user-name&email=user%40domain.com', $query->toString());
    }

    public function testEdgeCaseArrayWithEmptyValues(): void
    {
        $query = new Query(['tags' => ['', 'php', '']]);

        self::assertSame(1, $query->count());
        self::assertSame('tags%5B0%5D=&tags%5B1%5D=php&tags%5B2%5D=', (string) $query);
        self::assertSame('tags%5B0%5D=&tags%5B1%5D=php&tags%5B2%5D=', $query->toString());
        self::assertSame(['', 'php', ''], $query->getAsArray('tags'));
    }

    public function testEdgeCaseArrayWithMixedTypes(): void
    {
        $query = new Query(['data' => ['string', '123', '']]);

        self::assertSame(1, $query->count());
        self::assertSame('data%5B0%5D=string&data%5B1%5D=123&data%5B2%5D=', (string) $query);
        self::assertSame('data%5B0%5D=string&data%5B1%5D=123&data%5B2%5D=', $query->toString());
        self::assertSame(['string', '123', ''], $query->getAsArray('data'));
    }

    public function testEdgeCaseVeryLongKey(): void
    {
        $longKey = \str_repeat('a', 1000);
        $query = new Query([$longKey => 'value']);

        self::assertSame(1, $query->count());
        self::assertTrue($query->has($longKey));
        self::assertSame('value', $query->get($longKey));
    }

    public function testEdgeCaseVeryLongValue(): void
    {
        $longValue = \str_repeat('a', 1000);
        $query = new Query(['key' => $longValue]);

        self::assertSame(1, $query->count());
        self::assertTrue($query->has('key'));
        self::assertSame($longValue, $query->get('key'));
    }

    public function testEdgeCaseNullByteInKey(): void
    {
        $query = new Query(["key\0" => 'value']);

        self::assertSame(1, $query->count());
        self::assertTrue($query->has("key\0"));
        self::assertSame('value', $query->get("key\0"));
    }

    public function testEdgeCaseNullByteInValue(): void
    {
        $query = new Query(['key' => "value\0"]);

        self::assertSame(1, $query->count());
        self::assertTrue($query->has('key'));
        self::assertSame("value\0", $query->get('key'));
    }

    public function testEdgeCaseControlCharacters(): void
    {
        $query = new Query(['key' => "value\x00\x01\x02"]);

        self::assertSame(1, $query->count());
        self::assertTrue($query->has('key'));
        self::assertSame("value\x00\x01\x02", $query->get('key'));
    }

    public function testPropertiesAreReadonly(): void
    {
        $query = new Query(['name' => 'value']);

        self::assertSame(1, $query->count());
        self::assertSame('name=value', (string) $query);
    }
}
