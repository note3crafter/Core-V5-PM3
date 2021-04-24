<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\utils;

use function count;

class Color{

    public const COLOR_DYE_BLACK = 0, COLOR_SHEEP_BLACK = 15;
    public const COLOR_DYE_RED = 1, COLOR_SHEEP_RED = 14;
    public const COLOR_DYE_GREEN = 2, COLOR_SHEEP_GREEN = 13;
    public const COLOR_DYE_BROWN = 3, COLOR_SHEEP_BROWN = 12;
    public const COLOR_DYE_BLUE = 4, COLOR_SHEEP_BLUE = 11;
    public const COLOR_DYE_PURPLE = 5, COLOR_SHEEP_PURPLE = 10;
    public const COLOR_DYE_CYAN = 6, COLOR_SHEEP_CYAN = 9;
    public const COLOR_DYE_LIGHT_GRAY = 7, COLOR_SHEEP_LIGHT_GRAY = 8;
    public const COLOR_DYE_GRAY = 8, COLOR_SHEEP_GRAY = 7;
    public const COLOR_DYE_PINK = 9, COLOR_SHEEP_PINK = 6;
    public const COLOR_DYE_LIME = 10, COLOR_SHEEP_LIME = 5;
    public const COLOR_DYE_YELLOW = 11, COLOR_SHEEP_YELLOW = 4;
    public const COLOR_DYE_LIGHT_BLUE = 12, COLOR_SHEEP_LIGHT_BLUE = 19;
    public const COLOR_DYE_MAGENTA = 13, COLOR_SHEEP_MAGENTA = 19;
    public const COLOR_DYE_ORANGE = 14, COLOR_SHEEP_ORANGE = 1;
    public const COLOR_DYE_WHITE = 15, COLOR_SHEEP_WHITE = 0;

    protected $a;
    protected $r;
    protected $g;
    protected $b;
    public static $dyeColors = null;

    public function __construct(int $r, int $g, int $b, int $a = 0xff){
        $this->r = $r & 0xff;
        $this->g = $g & 0xff;
        $this->b = $b & 0xff;
        $this->a = $a & 0xff;
    }

    public static function initDyeColors(){
        if(self::$dyeColors === null){
            self::$dyeColors = new \SplFixedArray(16);

            self::$dyeColors[self::COLOR_DYE_BLACK] = new Color(30, 27, 27);
            self::$dyeColors[self::COLOR_DYE_RED] = new Color(179, 49, 44);
            self::$dyeColors[self::COLOR_DYE_GREEN] = new Color(61, 81, 26);
            self::$dyeColors[self::COLOR_DYE_BROWN] = new Color(81, 48, 26);
            self::$dyeColors[self::COLOR_DYE_BLUE] = new Color(37, 49, 146);
            self::$dyeColors[self::COLOR_DYE_PURPLE] = new Color(123, 47, 190);
            self::$dyeColors[self::COLOR_DYE_CYAN] = new Color(40, 118, 151);
            self::$dyeColors[self::COLOR_DYE_LIGHT_GRAY] = new Color(153, 153, 153);
            self::$dyeColors[self::COLOR_DYE_GRAY] = new Color(67, 67, 67);
            self::$dyeColors[self::COLOR_DYE_PINK] = new Color(216, 129, 152);
            self::$dyeColors[self::COLOR_DYE_LIME] = new Color(65, 205, 52);
            self::$dyeColors[self::COLOR_DYE_YELLOW] = new Color(222, 207, 42);
            self::$dyeColors[self::COLOR_DYE_LIGHT_BLUE] = new Color(102, 137, 211);
            self::$dyeColors[self::COLOR_DYE_MAGENTA] = new Color(195, 84, 205);
            self::$dyeColors[self::COLOR_DYE_ORANGE] = new Color(235, 136, 68);
            self::$dyeColors[self::COLOR_DYE_WHITE] = new Color(240, 240, 240);
        }
    }

    public static function getDyeColor(int $dyeColor) : Color{
        return isset(self::$dyeColors[$dyeColor]) ? clone self::$dyeColors[$dyeColor] : new Color(0, 0, 0);
    }

    public function getA() : int{
        return $this->a;
    }

    public function setA(int $a){
        $this->a = $a & 0xff;
    }

    public function getR() : int{
        return $this->r;
    }

    public function setR(int $r){
        $this->r = $r & 0xff;
    }

    public function getG() : int{
        return $this->g;
    }

    public function setG(int $g){
        $this->g = $g & 0xff;
    }

    public function getB() : int{
        return $this->b;
    }

    public function setB(int $b){
        $this->b = $b & 0xff;
    }

    public static function mix(Color ...$colors) : Color{
        $count = count($colors);
        if($count < 1){
            throw new \ArgumentCountError("No colors given");
        }

        $a = $r = $g = $b = 0;

        foreach($colors as $color){
            $a += $color->a;
            $r += $color->r;
            $g += $color->g;
            $b += $color->b;
        }

        return new Color((int) ($r / $count), (int) ($g / $count), (int) ($b / $count), (int) ($a / $count));
    }

    public function equals(Color $color) : bool{
        return  $this->r === $color->r and $this->g === $color->g and $this->b === $color->b and $this->a === $color->a;
    }

    public static function fromRGB(int $code){
        return new Color(($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff);
    }

    public static function fromARGB(int $code){
        return new Color(($code >> 16) & 0xff, ($code >> 8) & 0xff, $code & 0xff, ($code >> 24) & 0xff);
    }

    public function toARGB() : int{
        return ($this->a << 24) | ($this->r << 16) | ($this->g << 8) | $this->b;
    }

    public function toBGRA() : int{
        return ($this->b << 24) | ($this->g << 16) | ($this->r << 8) | $this->a;
    }

    public function toRGBA() : int{
        return ($this->r << 24) | ($this->g << 16) | ($this->b << 8) | $this->a;
    }

    public function toABGR() : int{
        return ($this->a << 24) | ($this->b << 16) | ($this->g << 8) | $this->r;
    }

    public static function fromABGR(int $code){
        return new Color($code & 0xff, ($code >> 8) & 0xff, ($code >> 16) & 0xff, ($code >> 24) & 0xff);
    }
}
