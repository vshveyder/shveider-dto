<?php

require_once __DIR__ . '/../../vendor/autoload.php';

function testCase(string $transferNamespace, bool $useMethods, bool $testInterface = false, bool $testMethodsFeatures = false): void {
    echo 'Test '. $transferNamespace . PHP_EOL;
    $mainTransferClass = "\\ShveiderDtoTest\\Transfers\\$transferNamespace\\MainTransfer";
    $addressTransferClass = "\\ShveiderDtoTest\\Transfers\\$transferNamespace\\AddressTransfer";
    $cityTransferClass = "\\ShveiderDtoTest\\Transfers\\$transferNamespace\\CityTransfer";
    $customerTransferClass = "\\ShveiderDtoTest\\Transfers\\$transferNamespace\\CustomerTransfer";
    $testCase = new \ShveiderDtoTest\TestCase($useMethods, new $mainTransferClass(), $addressTransferClass, $cityTransferClass, $customerTransferClass);

    $testInterface && $testCase->testInterface();
    $testMethodsFeatures && $testCase->testMethodsFeatures();
}

testCase('AbstractCastTransfer', false, testInterface: true);

testCase('AbstractCastDynamicTransfer', true, testInterface: true, testMethodsFeatures: true);

testCase('ProjectLevelAbstractCachedTransfer', false, testInterface: true);

testCase('AbstractSetTransfer', false, testInterface: true);

testCase('AbstractConfigurableTransfer', true, testInterface: true, testMethodsFeatures: true);
