<?php

namespace ps_metrics_module_v4_0_6;

/**
 * @param string $moduleName
 * @param string[] $list
 */
function printErrorsList($moduleName, $list)
{
    echo "\x1b[31m";
    $message = \sprintf('Test failed for module %s, got differences between expected folder and workspace folder :', $moduleName);
    echo $message . \PHP_EOL;
    foreach ($list as $item) {
        echo ' - ' . $item . \PHP_EOL;
    }
    echo "\x1b[37m";
}
/**
 * @param string $message
 */
function printErrorMessage($message)
{
    echo "\x1b[31m";
    echo $message;
    echo "\x1b[37m";
}
/**
 * @param string $message
 */
function printSuccessMessage($message)
{
    echo "\x1b[32m";
    echo $message;
    echo "\x1b[37m";
}
/**
 * @return \Symfony\Component\Console\Application
 */
function buildTestApplication()
{
    $application = new \Symfony\Component\Console\Application('header-stamp', '9.9.9');
    $command = new \ps_metrics_module_v4_0_6\PrestaShop\HeaderStamp\Command\UpdateLicensesCommand();
    $application->add($command);
    $application->setDefaultCommand($command->getName());
    $application->setAutoExit(\false);
    return $application;
}
