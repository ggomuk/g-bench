<?php
namespace GBench\Traits;

trait Converter
{
    /**
     * Change memory size to be human readable
     * @param int $size
     * @return string
     */
    public static function readableSize(int $size): string
    {
        if ($size >= 1 << 30) {
            return number_format($size / (1 << 30), 2) . " GB";
        } elseif ($size >= 1 << 20) {
            return number_format($size / (1 << 20), 2) . " MB";
        } elseif ($size >= 1 << 10) {
            return number_format($size / (1 << 10), 2) . " KB";
        } else {
            return number_format($size) . " bytes";
        }
    }
}
