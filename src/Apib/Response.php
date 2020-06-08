<?php

namespace Goez\ApibUnit\Apib;

use stdClass;

/**
 * Class Response
 */
class Response
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
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $body = '';

    /**
     * @var stdClass|null
     */
    protected $schema;

    /**
     * Response constructor.
     *
     * @see handleHeaders
     * @see handleSchema
     * @see handleName
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $attributeNames = ['name', 'description', 'headers', 'body', 'schema'];

        foreach ($data as $name => $value) {
            if (in_array($name, $attributeNames, true)) {
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
     * @return stdClass|null
     */
    public function getSchema(): ?stdClass
    {
        return $this->schema;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param array $raw
     */
    private function handleHeaders(array $raw): void
    {
        $headers = [];

        foreach ($raw as $index => $pair) {
            $headers[$pair['name']] = $pair['value'];
        }

        $this->headers = $headers;
    }

    /**
     * @param string $raw
     */
    private function handleName(string $raw): void
    {
        // 雖然文件是如此定義：
        // + Response <HTTP status code> (<Media Type>)
        // https://github.com/apiaryio/api-blueprint/blob/master/API%20Blueprint%20Specification.md#def-response-section
        //
        // 但是這邊的 "HTTP status code" 在 AST 中的名稱為 "name"
        $this->statusCode = (int) $raw;
    }

    /**
     * @param string $raw
     */
    private function handleSchema(string $raw): void
    {
        $this->schema = json_decode($raw);
    }
}
