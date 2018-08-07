<?php

namespace Goez\ApibUnit\Apib;

class Request
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $body = '';

    /**
     * Response constructor.
     *
     * @see handleHeaders
     * @param array $data
     */
    public function __construct(array $data)
    {
        $attributeNames = [
            'name',
            'description',
            'headers',
            'body',
        ];

        foreach ($data as $name => $value) {
            if (\in_array($name, $attributeNames, true)) {
                $methodName = 'handle' . ucfirst($name);
                if (method_exists($this, $methodName)) {
                    $this->{$methodName}($value);
                } else {
                    $this->{$name} = $value;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return bool
     */
    public function isMultipart(): bool
    {
        $contentType = $this->getContentType();

        if (!$contentType) {
            return false;
        }

        $parseContentType = \explode(';', $contentType);

        return ($parseContentType[0] === 'multipart/form-data');
    }

    /**
     * @return bool
     */
    public function isJson(): bool
    {
        return ($this->getContentType() === 'application/json');
    }

    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->headers['content-type'] ?? null;
    }

    /**
     * @param array $raw
     */
    private function handleHeaders(array $raw): void
    {
        $headers = [];

        foreach ($raw as $index => $pair) {
            $headers[\strtolower($pair['name'])] = $pair['value'];
        }

        $this->headers = $headers;
    }
}
