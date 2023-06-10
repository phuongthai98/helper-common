<?php

namespace Thainp\HelperCommon;

/**
 * Class Utility
 * @package App\Commons
 */
class Utility
{

    /**
     * @param $string
     * @return mixed
     */
    public static function stripText($string)
    {
        $from = array("à", "ả", "ã", "á", "ạ", "ă", "ằ", "ẳ", "ẵ", "ắ", "ặ", "â", "ầ", "ẩ", "ẫ", "ấ", "ậ", "đ", "è", "ẻ", "ẽ", "é", "ẹ", "ê", "ề", "ể", "ễ", "ế", "ệ", "ì", "ỉ", "ĩ", "í", "ị", "ò", "ỏ", "õ", "ó", "ọ", "ô", "ồ", "ổ", "ỗ", "ố", "ộ", "ơ", "ờ", "ở", "ỡ", "ớ", "ợ", "ù", "ủ", "ũ", "ú", "ụ", "ư", "ừ", "ử", "ữ", "ứ", "ự", "ỳ", "ỷ", "ỹ", "ý", "ỵ", "À", "Ả", "Ã", "Á", "Ạ", "Ă", "Ằ", "Ẳ", "Ẵ", "Ắ", "Ặ", "Â", "Ầ", "Ẩ", "Ẫ", "Ấ", "Ậ", "Đ", "È", "Ẻ", "Ẽ", "É", "Ẹ", "Ê", "Ề", "Ể", "Ễ", "Ế", "Ệ", "Ì", "Ỉ", "Ĩ", "Í", "Ị", "Ò", "Ỏ", "Õ", "Ó", "Ọ", "Ô", "Ồ", "Ổ", "Ỗ", "Ố", "Ộ", "Ơ", "Ờ", "Ở", "Ỡ", "Ớ", "Ợ", "Ù", "Ủ", "Ũ", "Ú", "Ụ", "Ư", "Ừ", "Ử", "Ữ", "Ứ", "Ự", "Ỳ", "Ỷ", "Ỹ", "Ý", "Ỵ");
        $to = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "d", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "i", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "y", "y", "y", "y", "y", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "D", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "I", "I", "I", "I", "I", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "Y", "Y", "Y", "Y", "Y");
        return str_replace($from, $to, $string);
    }

    /**
     * @param $string
     * @return string
     */
    public static function cleanUpSpecialChars($string)
    {
        $string = preg_replace(array("`\W`i", "`[-]+`"), "-", $string);
        return trim($string, '-');
    }

    public static function makeFilename($string)
    {
        $title = self::stripText(strtolower($string));
        $title = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $title);

        return str_replace(' ', '-', $title);
    }

    /**
     * @param $string
     * @return string
     */
    public static function makeSlug($string)
    {
        $title = self::stripText(strtolower($string));
        return self::cleanUpSpecialChars($title);
    }

    /**
     * @param $objId
     * @param bool|false $isDir
     * @return string
     */
    public static function makeStoragePath($objId, $isDir = false)
    {
        $step = 15; // So bit de ma hoa ten thu muc tren 1 cap
        $layer = 3; // So cap thu muc
        $max_bits = PHP_INT_SIZE * 8;
        $result = "";

        for ($i = $layer; $i > 0; $i--) {
            $shift = $step * $i;
            $layer_name = $shift <= $max_bits ? $objId >> $shift : 0;

            $result .= $isDir ? DIRECTORY_SEPARATOR . $layer_name : "/" . $layer_name;
        }

        return $result;
    }

    public static function makeDirectory($dir, $mode = 0777, $recursive = true)
    {
        if (!file_exists($dir)) {
            $fs = new Filesystem();
            $fs->chmod($dir, $mode);
//            $old_umask = umask(0);
//            mkdir($dir, $mode, $recursive);
//            umask($old_umask);
        }
    }

    public static function moneyFormat($data, $currency = 'vnđ')
    {
        return number_format($data, 0, '', '.') . ' ' . $currency;
    }

    public static function displayMonth($time)
    {
        $arrMonth = array(
            1  => 'Tháng 1',
            2  => 'Tháng 2',
            3  => 'Tháng 3',
            4  => 'Tháng 4',
            5  => 'Tháng 5',
            6  => 'Tháng 6',
            7  => 'Tháng 7',
            8  => 'Tháng 8',
            9  => 'Tháng 9',
            10 => 'Tháng 10',
            11 => 'Tháng 11',
            12 => 'Tháng 12',
        );

        $month = date('n', strtotime($time));

        if (isset($arrMonth[$month]))
            return $arrMonth[$month];

        return '';
    }

    public static function numberFormat($value, $min_money = 1000, $symbol = null)
    {
        $value = intval($value);
        $value = $value < $min_money ?
            $value : ($value / $min_money) * $min_money;
        if (intval($value) >= $min_money) {
            if ($value != '' and is_numeric($value)) {
                $value = number_format($value, 2, ',', '.');
                $value = str_replace(',00', '', $value);
            }
        }
        if ($symbol)
            $value .= ' ' . $symbol;
        return $value;
    }

    public static function createDateRange($startDate, $endDate)
    {
        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);
        $dateArr = array();
        foreach ($period as $dt)
            $dateArr[] = $dt->format("d-m-Y");
        return array_merge($dateArr, array($endDate));
    }

    public static function reversephone($phone)
    {
        $phone = str_replace("+", "", $phone);
        $rest = substr($phone, 0, 1);
        if ($rest != '0') {
            $phone = "0" . $phone;
        }
        return $phone;
    }

    static function formatStringToDate($strDate, $format = 'sql', $prefix = '-', $show_time = false)
    {
        $t = strtotime(str_replace('/', '-', $strDate));

        switch ($format) {
            case "vi":
                return $show_time ? date('H:i:s d' . $prefix . 'm' . $prefix . 'Y', $t) : date('d' . $prefix . 'm' . $prefix . 'Y', $t);
            case 'en':
                return $show_time ? date('m' . $prefix . 'd' . $prefix . 'Y H:i:s', $t) : date('m' . $prefix . 'd' . $prefix . 'Y', $t);
            case 'ja':
                return $show_time ? date('Y' . $prefix . 'm' . $prefix . 'd H:i:s', $t) : date('Y' . $prefix . 'm' . $prefix . 'd', $t);
            default:
                return $show_time ? date('Y-m-d H:i:s', $t) : date('Y-m-d', $t);
        }
    }

    static function formatDateToString($object, $format = 'df', $prefix = '-', $show_time = false)
    {
        switch ($format) {
            case "vi":
                return $show_time ? date_format($object, 'H:i d' . $prefix . 'm' . $prefix . 'Y') : date_format($object, 'd' . $prefix . 'm' . $prefix . 'Y');
            case 'en':
                return $show_time ? date_format($object, 'm' . $prefix . 'd' . $prefix . 'Y H:i:s') : date_format($object, 'm' . $prefix . 'd' . $prefix . 'Y');
            case 'ja':
                return $show_time ? date_format($object, 'Y' . $prefix . 'm' . $prefix . 'd H:i:s') : date_format($object, 'Y' . $prefix . 'm' . $prefix . 'd');
            case 'sql':
                return $show_time ? date_format($object, 'Y' . $prefix . 'm' . $prefix . 'd H:i:s') : date_format($object, 'Y' . $prefix . 'm' . $prefix . 'd');
            default:
                return $show_time ? date_format($object, 'Y' . $prefix . 'm' . $prefix . 'd H:i') : date_format($object, 'Y' . $prefix . 'm' . $prefix . 'd');
        }
    }

    static function resizeAndUploadImage($image_file, $path_save = '', $image_name, $resize = false, $options = [])
    {
        $fn = $image_file;

        $max_size = [
            'width'  => 240,
            'height' => 180
        ];
        if (!empty($options)) {
            $max_size = array_merge($max_size, $options);
        }

        try {
            $img_info = exif_read_data($fn);
        } catch (\Exception $e) {
            return false;
        }


        if (empty($img_info)) {
            $src = imagecreatefromstring(file_get_contents($fn));
            imagejpeg($src, $path_save . $image_name); // adjust format as needed
            imagedestroy($src);
            return true;
        }
        $orientation = '';
        if (isset($img_info['Orientation'])) {
            $orientation = $img_info['Orientation'];
        }

        $src = imagecreatefromstring(file_get_contents($fn));
        $orient = 0;
        if ($orientation != '') {
            switch ($orientation) {
                case 3:
                    $src = imagerotate($src, 180, 0);
                    break;
                case 6:
                    $orient = 1;
                    $src = imagerotate($src, -90, 0);
                    break;
                case 8:
                    $orient = 1;
                    $src = imagerotate($src, 90, 0);
                    break;
            }
        }

        if ($resize) {
            if (isset($img_info['COMPUTED']['Width'])) {
                $width = $img_info['COMPUTED']['Width'];
            } else if (isset($img_info['ExifImageWidth'])) {
                $width = $img_info['ExifImageWidth'];
            }
            if (isset($img_info['COMPUTED']['Height'])) {
                $height = $img_info['COMPUTED']['Height'];
            } else if (isset($img_info['ExifImageHeight'])) {
                $height = $img_info['ExifImageHeight'];
            }

            if (isset($width) && isset($height)) {
                if ($orient) {
                    $tmp = $width;
                    $width = $height;
                    $height = $tmp;
                }
                $r = $width / $height;
                $new_width = $width;
                $new_height = $height;
                if ($r > 1 && $width > $max_size['width']) {
                    $new_width = $max_size['width'];
                    $new_height = ceil($new_width / $r);
                }
                if ($r < 1 && $height > $max_size['height']) {
                    $new_height = $max_size['height'];
                    $new_width = ceil($r * $new_height);
                }
                $dst = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagedestroy($src);
                imagejpeg($dst, $path_save . $image_name); // adjust format as needed
                imagedestroy($dst);
                return true;
            }
        }
        imagejpeg($src, $path_save . $image_name); // adjust format as needed
        imagedestroy($src);
        return true;
    }


    public static function checkValidatePassword($password = '')
    {
        if (strlen($password) < 8) {
            return [
                'is_valid' => false,
                'msg'      => 'Mật khẩu không được ít hơn 8 ký tự'
            ];
        }
        if (strlen($password) > 45) {
            return [
                'is_valid' => false,
                'msg'      => 'Mật khẩu không được nhiều hơn 45 ký tự'
            ];
        }

        if (!preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $password)) {
            return [
                'is_valid' => false,
                'msg'      => 'Mật khẩu phải có chứa ký tự đặc biệt'
            ];
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return [
                'is_valid' => false,
                'msg'      => 'Mật khẩu phải có chứa ký tự viết hoa'
            ];
        }
        if (!preg_match('/[a-z]/', $password)) {
            return [
                'is_valid' => false,
                'msg'      => 'Mật khẩu phải có chứa ký tự thường'
            ];
        }
        return [
            'is_valid' => true,
            'msg'      => ''
        ];
    }

    public static function checkRecentPassword($new_pass = '', $arr_pass_history = [])
    {
        if (empty($arr_pass_history)) {
            return true;
        }
        foreach ($arr_pass_history as $_tmp) {
            if (Hash::check($new_pass, $_tmp->password)) {
                return false;
            }
        }
        return true;
    }

    public static function deleteDir($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    public static function milliseconds()
    {
        $mt = explode(' ', microtime());
        return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
    }

    public static function rand_string($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    public static function validateEmail($email)
    {
        if (!empty($email)) {
            $value = filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($value !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
