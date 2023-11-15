<?php

namespace ShveiderDto\Model\Code;

class MethodGenerator
{
    /** @var array<string> */
    protected array $body = [];

    protected bool $minified = true;

    protected string $phpDocReturnType = '';

    /**
     * @param string $name
     * @param array<string> $params
     * @param string $returnType
     */
    public function __construct(
        protected readonly string $name,
        protected readonly array  $params,
        protected readonly string $returnType
    ) {
    }

    public function setPhpDocReturnType(string $phpDocReturnType): static
    {
        $this->phpDocReturnType = $phpDocReturnType;

        return $this;
    }

    public function insertRawBefore(string $raw): static
    {
        $this->body = [$raw, ...$this->body];

        return $this;
    }

    public function setMinified(bool $minified): MethodGenerator
    {
        $this->minified = $minified;

        return $this;
    }

    public function insertRaw(string $raw): static
    {
        $this->body[] = $raw;

        return $this;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function setBody(array $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function toString(): string
    {
        return $this->generateMethod(
            $this->generatePhpDoc(),
            $this->generateMethodBeforeBody(),
            $this->generateMethodStart(),
            $this->generateBody(),
            $this->generateMethodEnd(),
        );
    }

    final public function __toString(): string
    {
        return $this->toString();
    }

    protected function generateMethod(
        string|null $phpDoc,
        string $methodBeforeBody,
        string $methodStart,
        string $body,
        string $methodEnd
    ): string {
        $paramsString = implode(', ', $this->params);

        $method =
            "\tpublic function $this->name($paramsString): $this->returnType$methodBeforeBody{ $methodStart$body$methodEnd}";

        if ($phpDoc) {
            $method = "\t" . $phpDoc . PHP_EOL . $method;
        }

        return $method;
    }

    protected function generatePhpDoc(): ?string
    {
        return $this->phpDocReturnType ? "/** @return $this->phpDocReturnType */" : null;
    }

    protected function generateMethodBeforeBody(): string
    {
        return $this->minified ? ' ' : PHP_EOL . "\t";
    }

    protected function generateMethodStart(): string
    {
        return $this->minified ? '' : PHP_EOL;
    }

    protected function generateBody(): string
    {
        $eol = $this->minified ? ' ' : PHP_EOL;

        return implode($eol, array_map(fn($raw) => $this->minified ? $raw : "\t\t" . $raw, $this->body));
    }

    protected function generateMethodEnd(): string
    {
        return $this->minified ? '' : PHP_EOL . "\t";
    }
}
