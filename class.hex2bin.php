<?PHP


/** PHPClass-Hex2Bin - converts a hex string to a binary string
  *
  * Usage:
  *     <?PHP $hex2bin = new hex2bin(); $bin = $hex2bin->convert(string hex); ?>
  * Eg:
  *     <?PHP $hex2bin = new hex2bin(); print $hex2bin->convert('FFCCFF'); ?>
  *
  * CHANGELOG:
  * 22/MAY/2002
  * - changed from function to class
  * - now check if have only numbers and a-f strings in the $hexString
  *
  * TODO:
  * - create PHPDocument manual
  *
  * @author     Roberto Bertó darkelder (inside) users (dot) sourceforge (dot) net
  * @copyright  {@link http://www.gnu.org/copyleft/lesser.html LGPL}
  *
  */
class hex2bin
{
        /** convert
          * @access     public
          * @param      string  $hexNumber      convert a hex string to binary string
          * @return     string  binary string
          */
        function convert($hexString)
        {
                $hexLenght = strlen($hexString);
                // only hex numbers is allowed
                if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexString)) return FALSE;

                unset($binString);
                for ($x = 1; $x <= $hexLenght/2; $x++)
                {
                        $binString .= chr(hexdec(substr($hexString,2 * $x - 2,2)));
                }

                return $binString;
        }
}
?>
