<?php

namespace Goez\ApibUnit;

use Hmaus\DrafterPhp\Drafter;
use Goez\ApibUnit\Apib\Element;
use Goez\ApibUnit\Traits\Elements;

class Apib
{
    use Elements;

    /**
     * @var array
     */
    private $content = [];

    /**
     * @var string
     */
    private $drafterPath = '/usr/local/bin/drafter';

    /**
     * Apib constructor.
     * @param string $apibPath
     * @param string|null $drafterPath
     * @throws \Exception
     */
    public function __construct(string $apibPath, ?string $drafterPath = null)
    {
        $this->initDrafterPath($drafterPath);

        if ($this->validFile($apibPath)) {
            $this->loadApib($apibPath);
            $this->buildElements();
        }
    }

    /**
     * @param string|null $drafterPath
     */
    private function initDrafterPath(?string $drafterPath = null): void
    {
        if ($drafterPath !== null) {
            $this->drafterPath = $drafterPath;
        } elseif ($drafterPath = trim(shell_exec('(which drafter)'))) {
            $this->drafterPath = $drafterPath;
        }

        if (!file_exists($this->drafterPath)) {
            throw new \RuntimeException('Install Drafter (https://github.com/apiaryio/drafter) first!');
        }
    }

    /**
     * @param string $apibPath
     * @return bool
     * @throws \Exception
     */
    private function validFile(string $apibPath): bool
    {
        $drafter = new Drafter($this->drafterPath);
        return '' === $drafter
            ->input($apibPath)
            ->validate()
            ->run();
    }

    /**
     * @param string $apibPath
     * @throws \Exception
     */
    private function loadApib(string $apibPath): void
    {
        $drafter = new Drafter($this->drafterPath);
        $result = $drafter
            ->input($apibPath)
            ->format('json')
            ->type('ast')
            ->run();
        $this->content = json_decode($result, true);
    }

    /**
     *
     * @throws \InvalidArgumentException
     */
    private function buildElements(): void
    {
        $list = (array) $this->content['ast']['content'];
        foreach ($list as $item) {
            $element = Element::createFrom($item);
            $this->appendElement($element);
        }
    }
}
