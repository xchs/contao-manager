<?php

/*
 * This file is part of Contao Manager.
 *
 * Copyright (c) 2016-2017 Contao Association
 *
 * @license LGPL-3.0+
 */

namespace Contao\ManagerApi\Process;

use Composer\Semver\VersionParser;
use Contao\ManagerApi\Exception\ProcessOutputException;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ContaoConsole
{
    /**
     * @var ConsoleProcessFactory
     */
    private $processFactory;

    /**
     * Constructor.
     *
     * @param ConsoleProcessFactory $processFactory
     */
    public function __construct(ConsoleProcessFactory $processFactory)
    {
        $this->processFactory = $processFactory;
    }

    /**
     * Gets the Contao version.
     *
     * @return string
     *
     * @throws ProcessFailedException
     * @throws ProcessOutputException
     */
    public function getVersion()
    {
        $process = $this->processFactory->createContaoConsoleProcess(['contao:version']);
        $process->mustRun();

        $parser = new VersionParser();
        $version = trim($process->getOutput());

        try {
            return $parser->normalize($version);
        } catch (\UnexpectedValueException $e) {
            throw new ProcessOutputException('Console output is not a valid version string.', $process);
        }
    }
}
