<?php

/*
 * This file is part of Contao Manager.
 *
 * Copyright (c) 2016-2017 Contao Association
 *
 * @license LGPL-3.0+
 */

namespace Contao\ManagerApi\Process;

use Contao\ManagerApi\Exception\ProcessOutputException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ContaoApi
{
    /**
     * @var ConsoleProcessFactory
     */
    private $processFactory;

    /**
     * @var null|Filesystem
     */
    private $filesystem;

    /**
     * Constructor.
     *
     * @param ConsoleProcessFactory $processFactory
     * @param Filesystem|null       $filesystem
     */
    public function __construct(ConsoleProcessFactory $processFactory, Filesystem $filesystem = null)
    {
        $this->processFactory = $processFactory;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * Gets the Contao API version.
     *
     * @return int
     *
     * @throws ProcessFailedException
     * @throws ProcessOutputException
     */
    public function getVersion()
    {
        if (!$this->filesystem->exists($this->processFactory->getContaoApiPath())) {
            return 0;
        }

        $process = $this->processFactory->createContaoApiProcess(['version']);
        $process->mustRun();

        $version = trim($process->getOutput());

        if (!preg_match('/^\d+$/', $version)) {
            throw new ProcessOutputException('Output is not a valid API version.', $process);
        }

        return (int) $version;
    }
}
