<?php

namespace Tests;

use Andsudev\Utm\UtmCartOrderEntity;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UtmCartOrderEntityTest extends TestCase
{
    public function testConstructorThrowsExceptionForEmptyParams(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new UtmCartOrderEntity([]);
    }

    public function testToSaveInDatabaseWithUtmParams(): void
    {
        $params = [
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'summer_sale',
        ];
        $entity = new UtmCartOrderEntity($params);
        $this->assertEquals($params, $entity->toSaveInDatabase());
    }

    public function testExceptionWhenInvalidParameterIsProvided(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new UtmCartOrderEntity(['invalid_param' => 'invalid_value']);
    }

    public function testInvalidPurchaseParameterIsIgnored(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $params = [
            'utm_source' => 'google',
        ];
        new UtmCartOrderEntity($params);
    }

    public function testToSaveInDatabaseWithGadParams(): void
    {
        $params = [
            'cart_session_id' => 'cart_abc',
            'gad_source' => 'google_ads',
            'gad_campaignId' => '12345',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'utm_source' => 'google_ads',
            'utm_campaign' => '12345',
            'cart_session_id' => 'cart_abc',
        ];
        $this->assertEquals($expected, $entity->toSaveInDatabase());
    }

    public function testToSaveInDatabaseWithMixedParamsUsesUtmPriority(): void
    {
        $params = [
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'facebook',
            'gad_source' => 'google_ads', 
            'utm_campaign' => 'winter_promo',
            'gad_campaignId' => '67890',
            'utm_medium' => 'social',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'utm_source' => 'facebook',
            'utm_medium' => 'social',
            'utm_campaign' => 'winter_promo',
            'cart_session_id' => 'cart_abc',
        ];
        $this->assertEquals($expected, $entity->toSaveInDatabase());
    }

    public function testToSaveInDatabaseWithSessionIds(): void
    {
        $params = [
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'google',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'google',
        ];
        $this->assertEquals($expected, $entity->toSaveInDatabase());
    }

    public function testToSaveInDatabaseWithAllParams(): void
    {
        $params = [
            'id' => 1,
            'utm_source' => 'facebook',
            'gad_source' => 'google_ads',
            'utm_campaign' => 'winter_promo',
            'gad_campaignId' => '67890',
            'utm_medium' => 'social',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'utm_source' => 'facebook',
            'utm_medium' => 'social',
            'utm_campaign' => 'winter_promo',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $this->assertEquals($expected, $entity->toSaveInDatabase());
    }

    public function testToSaveInDatabaseReturnsOnlyProvidedParams(): void
    {
        $params = [
            'utm_source' => 'source_only',
            'cart_session_id' => 'cart_only',
        ];
        $entity = new UtmCartOrderEntity($params);
        $this->assertEquals($params, $entity->toSaveInDatabase());
    }

    public function testToPresentationWithUtmParams(): void
    {
        $params = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'summer_sale',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'id' => 1,
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'summer_sale',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $this->assertEquals($expected, $entity->toPresentation());
    }

    public function testToPresentationWithGadParams(): void
    {
        $params = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
            'gad_source' => 'google_ads',
            'gad_campaignId' => '12345',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'id' => 1,
            'utm_source' => 'google_ads',
            'utm_medium' => '',
            'utm_campaign' => '12345',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $this->assertEquals($expected, $entity->toPresentation());
    }

    public function testToPresentationWithMixedParams(): void
    {
        $params = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'facebook',
            'gad_source' => 'google_ads',
            'utm_campaign' => 'winter_promo',
            'gad_campaignId' => '67890',
            'utm_medium' => 'social',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'id' => 1,
            'utm_source' => 'facebook',
            'utm_medium' => 'social',
            'utm_campaign' => 'winter_promo',
            'cart_session_id' => 'cart_abc',
            'order_id' => '',
        ];
        $this->assertEquals($expected, $entity->toPresentation());
    }

    public function testToPresentationWithSessionIds(): void
    {
        $params = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
            'utm_source' => 'google',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'id' => 1,
            'utm_source' => 'google',
            'utm_medium' => '',
            'utm_campaign' => '',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $this->assertEquals($expected, $entity->toPresentation());
    }

    public function testToPresentationWithAllParams(): void
    {
        $params = [
            'id' => 1,
            'utm_source' => 'facebook',
            'gad_source' => 'google_ads',
            'utm_campaign' => 'winter_promo',
            'gad_campaignId' => '67890',
            'utm_medium' => 'social',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'id' => 1,
            'utm_source' => 'facebook',
            'utm_medium' => 'social',
            'utm_campaign' => 'winter_promo',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $this->assertEquals($expected, $entity->toPresentation());
    }

    public function testToEditInDatabaseWithUtmParams(): void
    {
        $params = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'summer_sale',
        ];
        $entity = new UtmCartOrderEntity($params);
        $this->assertEquals($params, $entity->toEditInDatabase($params));
    }

    public function testToEditInDatabaseWithGadParams(): void
    {
        $params = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'gad_source' => 'google_ads',
            'gad_campaignId' => '12345',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'google_ads',
            'utm_campaign' => '12345',
        ];
        $this->assertEquals($expected, $entity->toEditInDatabase($params));
    }

    public function testToEditInDatabaseWithMixedParams(): void
    {
        $params = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'facebook',
            'gad_source' => 'google_ads',
            'utm_campaign' => 'winter_promo',
            'gad_campaignId' => '67890',
            'utm_medium' => 'social',
        ];
        $entity = new UtmCartOrderEntity($params);
        $expected = [
            'id' => 1,
            'cart_session_id' => 'cart_abc',
            'utm_source' => 'facebook',
            'utm_medium' => 'social',
            'utm_campaign' => 'winter_promo',
        ];
        $this->assertEquals($expected, $entity->toEditInDatabase($params));
    }

    public function testToEditInDatabaseWithDifferentData(): void
    {
        $params = [
            'id' => 1,
            'utm_source' => 'facebook',
            'utm_medium' => 'social',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
        ];
        $entity = new UtmCartOrderEntity($params);

        $newData = [    
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'summer_sale',
        ];

        $toUpdateData = $entity->toEditInDatabase($newData);

        $expected = [
            'id' => 1,
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'cart_session_id' => 'cart_abc',
            'order_id' => '123',
            'utm_campaign' => 'summer_sale',
        ];

        $this->assertEquals($expected, $toUpdateData);
    }
} 