<?php

namespace Andsudev\Utm;

use InvalidArgumentException;

class UtmCartOrderEntity
{
    private ?int $id = null;
    private ?string $utmSource = null;
    private ?string $utmMedium = null;
    private ?string $utmCampaign = null;
    private ?string $gadSource = null;
    private ?string $gadCampaignId = null;
    private ?string $cartSessionId = null;
    private ?string $orderId = null;

    const ALLOWED_UTM_PARAMETERS = ['utm_source', 'utm_medium', 'utm_campaign', 'gad_source', 'gad_campaignId'];
    const ALLOWED_PURCHASE_PARAMETERS = ['cart_session_id', 'order_id'];

    public function __construct(array $params)
    {
        $validUtmParameters = array_intersect_key($params, array_flip(self::ALLOWED_UTM_PARAMETERS));

        if (count(array_filter($validUtmParameters)) === 0) {
            throw new InvalidArgumentException('Pelo menos um parâmetro de monitoramento deve ser fornecido.');
        }

        $validPurchaseParameters = array_intersect_key($params, array_flip(self::ALLOWED_PURCHASE_PARAMETERS));

        if (count(array_filter($validPurchaseParameters)) === 0) {
            throw new InvalidArgumentException('Pelo menos um parâmetro de compra deve ser fornecido.');
        }

        $validParameters = array_merge($validUtmParameters, $validPurchaseParameters);

        $isCreatedUtmCartOrder = isset($params['id']) && is_numeric($params['id']);
        if ($isCreatedUtmCartOrder) {
            $validParameters['id'] = (int)$params['id'];
        }

        $this->fill($validParameters);
    }

    public function fill(array $params): void
    {
        $this->utmSource = $params['utm_source'] ?? null;
        $this->utmMedium = $params['utm_medium'] ?? null;
        $this->utmCampaign = $params['utm_campaign'] ?? null;
        $this->gadSource = $params['gad_source'] ?? null;
        $this->gadCampaignId = $params['gad_campaignId'] ?? null;

        $this->id = $params['id'] ?? $this->id;
        $this->cartSessionId = $params['cart_session_id'] ?? $this->cartSessionId;
        $this->orderId = $params['order_id'] ?? $this->orderId;
    }

    public function updateAttribute(string $attribute, $value): void
    {
        $this->$attribute = $value;
    }

    protected function hasAttribute(string $attribute): bool
    {
        return property_exists($this, $attribute)
            && $this->$attribute !== null;
    }

    protected function getUtmSourceParameter(array $default = []): array
    {
        if ($this->hasAttribute('utmSource')) {
            return ['utm_source' => $this->utmSource];
        }

        if ($this->hasAttribute('gadSource')) {
            return ['utm_source' => $this->gadSource];
        }

        return $default;
    }

    protected function getUtmMediumParameter(array $default = []): array
    {
        if ($this->hasAttribute('utmMedium')) {
            return ['utm_medium' => $this->utmMedium];
        }

        return $default;
    }

    protected function getUtmCampaignParameter(array $default = []): array
    {
        if ($this->hasAttribute('utmCampaign')) {
            return ['utm_campaign' => $this->utmCampaign];
        }

        if ($this->hasAttribute('gadCampaignId')) {
            return ['utm_campaign' => $this->gadCampaignId];
        }

        return $default;
    }

    protected function getCartSessionIdParameter(array $default = []): array
    {
        if ($this->hasAttribute('cartSessionId')) {
            return ['cart_session_id' => $this->cartSessionId];
        }

        return $default;
    }

    protected function getOrderIdParameter(array $default = []): array
    {
        if ($this->hasAttribute('orderId')) {
            return ['order_id' => $this->orderId];
        }

        return $default;
    }

    public function toSaveInDatabase(): array
    {
        return [
            ...$this->getUtmSourceParameter(),
            ...$this->getUtmMediumParameter(),
            ...$this->getUtmCampaignParameter(),
            ...$this->getCartSessionIdParameter(),
            ...$this->getOrderIdParameter(),
        ];
    }

    public function toEditInDatabase(array $newData): array
    {
        $this->fill($newData);

        return [
            'id' => $this->id,
            ...$this->toSaveInDatabase()
        ];
    }

    public function toPresentation(): array
    {
        return [
            'id' => $this->id,
            ...$this->getCartSessionIdParameter(['cart_session_id' => '']),
            ...$this->getOrderIdParameter(['order_id' => '']),
            ...$this->getUtmSourceParameter(['utm_source' => '']),
            ...$this->getUtmMediumParameter(['utm_medium' => '']),
            ...$this->getUtmCampaignParameter(['utm_campaign' => '']),
        ];
    }
} 