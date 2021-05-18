<?php


namespace Freezemage\Smoke\Notification;


use Freezemage\Config\ConfigInterface;


class NotificationCollection {
    protected $items;

    public static function fromConfig(ConfigInterface $config): NotificationCollection {
        $collection = new NotificationCollection();
        foreach ($config->get('notification.phrases') as $phrase) {
            $collection->add($phrase);
        }

        return $collection;
    }

    public function __construct() {
        $this->items = array();
    }

    public function add(string $phrase): void {
        $this->items[] = $phrase;
    }

    public function getRandom(): ?string {
        if (empty($this->items)) {
            return null;
        }

        $index = rand(0, count($this->items) - 1);
        return $this->items[$index];
    }
}