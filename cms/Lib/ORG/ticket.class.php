<?php

/**
 * ticket
 *
 * @author Ideal.Yanleilei
 *
 */
class Ticket {

    /**
     * ticket有效期
     * @var integer
     */
    protected static $_ttl      = 7200;

    protected static $_deviceId = NULL;

    /**
     * 只允许静态调用
     */
    final private function __construct() {
        throw new ThinkException('只允许静态调用');
    }

    /**
     * 生成ticket
     *
     * @param string  $bind
     * @param integer $uid
     * @param integer $key
     * @param integer $expire
     * @param boolean $durable
     * @return string
     */
    protected static function _buildTicket($bind, $uid, $key, $expire, $durable = false) {
        $uBit = decbin($uid);
        $uLen = strlen($uBit);
        $eBit = sprintf('%032b', $expire);
        $kBit = sprintf('%032b', $key);
        $rBit = sprintf('%058s%05b%d', substr(base_convert(substr(md5(uniqid(mt_rand(), true)), mt_rand(0, 16), 16), 16, 2), 0, 58), $uLen % 32, $durable ? 1 : 0);
        $cBit = sprintf('%032b', crc32($uBit . $eBit . $kBit . $rBit . $bind));
        $uBit .= substr(sprintf('%032b', mt_rand()), $uLen - 32);

        $bin = '';
        for ($i = 0; $i < 32; $i++) {
            $bin .= $rBit[$i * 2];
            $bin .= $eBit[$i];
            $bin .= $kBit[$i];
            $bin .= $rBit[$i * 2 + 1];
            $bin .= $uBit[$i];
            $bin .= $cBit[$i];
        }

        return strtr(base64_encode(implode('', array_map(function($item){ return chr(bindec($item)); }, str_split($bin, 8)))), '+/', '-_');
    }

    /**
     * 解析Ticket
     *
     * @param string $ticket
     * @param string  $bind
     * @param integer $uid
     * @param integer $key
     * @param integer $expire
     * @param boolean $durable
     * @return boolean
     */
    protected static function _parseTicket($ticket, $bind, &$uid = NULL, &$key = NULL, &$expire = NULL, &$durable = NULL) {
        $rBit = '';
        $eBit = '';
        $kBit = '';
        $uBit = '';
        $cBit = '';
        $bin = implode('', array_map(function($item){ return sprintf('%08b', ord($item)); }, str_split(base64_decode(strtr($ticket, '-_', '+/')), 1)));
        for ($i = 0; $i < 192; $i += 6) {
            $rBit .= $bin[$i];
            $eBit .= $bin[$i + 1];
            $kBit .= $bin[$i + 2];
            $rBit .= $bin[$i + 3];
            $uBit .= $bin[$i + 4];
            $cBit .= $bin[$i + 5];
        }

        $uLen = bindec(substr($rBit, 58, 5));
        if ($uLen < 32) {
            $uBit = substr($uBit, 0, $uLen);
        }
        //32位系统会产生整型溢出，必需输出格式化
        if (sprintf('%u', crc32($uBit . $eBit . $kBit . $rBit . $bind)) == bindec($cBit)) {
            $uid = bindec($uBit);
            if ($uid > 0) {
                $expire     = bindec($eBit);
                $key        = bindec($kBit);
                $durable    = ($rBit[63] === '1');

                return true;
            }
        }

        throw new ThinkException('Ticket error');
    }

    /**
     * 生成key
     *
     * @param integer $uid
     * @param string $bind
     * @param integer $expire
     * @return integer
     */
    protected static function _genKey($uid, $bind, $expire) {
        return crc32(md5(sprintf('%d|%s|%d', $uid, $bind, $expire), true));
    }

    /**
     * 发送ticket http头
     *
     * @param string $ticket
     * @param integer $uid
     * @param integer $ttl
     */
    protected static function _headerTicket($ticket, $uid, $ttl) {
        header(sprintf('Set-Ticket: %s; uid=%d; expires=%s; Max-Age=%d', $ticket, $uid, Request::httpDate(time() + $ttl), $ttl));
    }

    /**
     * 判断格式是否正确
     *
     * @param string $ticket
     * @return boolean
     */
    protected static function _isTicket($ticket) {
        return strlen($ticket) == 32 && strspn($ticket, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_') == 32;
    }

    /**
     * 检查格式是否正确
     *
     * @param string $ticket
     */
    public static function checkFormat($ticket) {
        if (!self::_isTicket($ticket)) {
            return false;
        }
    }

    /**
     * 创建ticket
     *
     * @param integer $uid
     * @param string $durable
     * @return multitype:number string
     */
    public static function create($uid, $bind, $durable = false) {
        $expire = time() + self::$_ttl;

        $key = self::_genKey($uid, $bind, $expire);
        $ticket = self::_buildTicket($bind, $uid, $key, $expire, $durable);

        return array('ticket' => $ticket, 'ttl' => self::$_ttl);
    }

    /**
     * 换票 长期的票才可以换新的票
     *
     * @param string $ticket
     * @param boolean $checked
     * @return multitype:number string
     */
    public static function refresh($ticket, $checked = false) {
    }

    /**
     * 获取ticket信息
     *
     * @param integer $ticket
     * @param boolean $checked
     * @return Ambigous <mixed, NULL, multitype:number >
     */
    public static function get($ticket, $bind, $checked = false) {
        $checked || self::checkFormat($ticket);

        $uid        = 0;
        $key        = 0;
        $expire     = 0;
        $durable    = false;

        //$bind = self::_genBind($isDevice);

        $info = self::_parseTicket($ticket, $bind, $uid, $key, $expire, $durable);
        if (time() < $expire) {
            $info = array();
            $info['uid'] = $uid;
            $info['expire'] = $expire;

            return $info;
        }

        return false;
    }

    /**
     * 获取默认ttl
     *
     * @return number
     */
    public static function getTtl() {
        return self::$_ttl;
    }
}