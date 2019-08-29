<?php

namespace Lo\Crawler\Helpers;

use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 * Provides a simple and flexible API wrapper around PHP's pathinfo function.
 *
 * @property string $directory
 * @property string $basename
 * @property string $extension
 * @property string $ext
 * @property string $filename
 */
class FileInfo
{
    /**
     * The file to check for.
     *
     * @var string
     */
    private $file;

    /**
     * Constructs a new `FileInfo` instance.
     *
     * @param string $file Path to file.
     * @return FileInfo
     */
    public static function parse($file): self
    {
        if (!\is_string($file)) {
            throw new InvalidArgumentException('FileInfo expects a string.');
        }

        $obj = new self;
        $obj->file = $file;

        return $obj;
    }

    /**
     * Retrieve the directory of the file.
     *
     * @return string E.g. C:/wamp/www/FileInfo/src
     */
    public function getDirectory(): string
    {
        return \dirname($this->file);
    }

    /**
     * Retrieve the filename and the extension.
     *
     * @return string E.g. FileInfo.php
     */
    public function getBaseName(): string
    {
        return \basename($this->file);
    }

    /**
     * The extension of the file.
     *
     * @return string E.g. php
     */
    public function getExtension(): string
    {
        return \substr(\strrchr($this->getBasename(), '.'), 1);
    }

    /**
     * The extension of the file.
     *
     * @return string E.g. php
     */
    public function getExt(): string
    {
        return '.' . $this->getExtension();
    }

    /**
     * The basename without the extension.
     *
     * @return string E.g. FileInfo
     */
    public function getFileName(): string
    {
        return \basename($this->file, '.' . $this->getExtension());
    }

    public function __set($key, $value)
    {
        if (!$this->__isset($key)) {
            throw new RuntimeException(\sprintf('Undefined property: %s::$%s', __CLASS__, $key));
        }

        $this->$key = $value;
    }

    /**
     * Checks if an arbitrary property exists.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key): bool
    {
        $allowed = [
            'directory',
            'basename',
            'extension',
            'ext',
            'filename',
        ];

        return \in_array(\strtolower($key), $allowed, true);
    }

    /**
     * Retrieve an arbitrary property.
     *
     * @param string $key
     * @return string
     * @throws Exception
     */
    public function __get($key)
    {
        switch (\strtolower($key)) {
            case 'directory':
                return $this->getDirectory();

            case 'basename':
                return $this->getBaseName();

            case 'extension':
                return $this->getExtension();

                case 'ext':
                return $this->getExt();

            case 'filename':
                return $this->getFileName();

            default:
                throw new RuntimeException(\sprintf('Undefined property: %s::$%s', __CLASS__, $key));
        }
    }
}
