<?php declare(strict_types=1);

namespace ShveiderDto\VO;

readonly class DtoFile
{
    public function __construct(
        public string $filesDir,
        public string $content,
        private string $fullNamespaceBase64,
        public string $dirNamespace,
        public string $traitName,
        public string $fileName,
    ) {
    }

    public function getFullNamespace(): string
    {
        return base64_decode($this->fullNamespaceBase64);
    }
}