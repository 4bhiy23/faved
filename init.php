<?php

use Framework\Exceptions\DatabaseNotFound;
use Framework\ServiceContainer;
use Models\ItemCreator;
use Models\TagCreator;

// Bind DB services
ServiceContainer::bind(PDO::class, function () {
	return Config::getPDO();
});

ServiceContainer::bind(Models\Repository::class, function (): Models\Repository {
	$pdo = ServiceContainer::get(PDO::class);
	return new Models\Repository($pdo);
});

ServiceContainer::bind(TagCreator::class, function (): TagCreator {
	$pdo = ServiceContainer::get(PDO::class);
	return new TagCreator($pdo);
});

ServiceContainer::bind(ItemCreator::class, function (): ItemCreator {
	$pdo = ServiceContainer::get(PDO::class);
	return new ItemCreator($pdo);
});