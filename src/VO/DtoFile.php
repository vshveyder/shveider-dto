<?php

namespace ShveiderDto\VO;

class DtoFile
{
    public function __construct(
        public string $filesDir,
        public string $content,
        public string $fullNamespace,
        public string $dirNamespace,
        public string $traitName
    ) {
    }
}