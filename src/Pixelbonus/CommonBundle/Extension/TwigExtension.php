<?php

namespace Pixelbonus\CommonBundle\Extension;

class TwigExtension extends \Twig_Extension
{
  protected $container;

  public function __construct($container) {
      $this->container = $container;
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters()
  {
    return array(
        'url_decode' => new \Twig_Filter_Method($this, 'urlDecode'),
        'roman_numeral' => new \Twig_Filter_Method($this, 'romanNumeral'),
        'md5' => new \Twig_Filter_Method($this, 'md5'),
        'stripGrAccent' => new \Twig_Filter_Method($this, 'stripGrAccent'),
        'get_class' => new \Twig_Filter_Method($this, 'getClass'),
    );
  }

  public function getTests()
  {
    return [
        'instanceof' =>  new \Twig_Function_Method($this, 'isInstanceof')
    ];
  }

  public function getGlobals() {
      return array();
  }

  /**
   * URL Decode a string
   *
   * @param string $url
   *
   * @return string The decoded URL
   */
  public function urlDecode( $url )
  {
    return urldecode( $url );
  }

  public function md5($string) {
      return md5($string);
  }

  function romanNumeral($integer, $upcase = true)
  {
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
    $return = '';
    while($integer > 0)
    {
        foreach($table as $rom=>$arb)
        {
            if($integer >= $arb)
            {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }

    return $return;
  }

  function stripGrAccent($tempName)
  {
    $utf8_str_split = function($str='',$len=1){
        preg_match_all("/./u", $str, $arr);
        $arr = array_chunk($arr[0], $len);
        $arr = array_map('implode', $arr);
        return $arr;
    };
    $tempName = str_replace($utf8_str_split("ΆάΈέΉήΌόΎύΏώί"), $utf8_str_split("ααεεηηοουυωωι"), $tempName);
    return str_replace($utf8_str_split("αβγδεζηθικλμνξοπρστυφχψως"), $utf8_str_split("ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΣ"), $tempName);
  }

  function getClass($obj) {
      return get_class($obj);
  }

  /**
   * @param $var
   * @param $instance
   * @return bool
   */
  public function isInstanceof($var, $instance) {
    return  $var instanceof $instance;
  }

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName()
  {
    return 'twig_extension';
  }
}