<?php namespace cidr;

//0=0xff, 1=0xfe, 2=0xfc, 3=0xf8, 4=0xf0, 5=0xe0, 6=0xc0, 7=0x80, 8=0x00

function parseIPv4($addr) {
    if (preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $addr, $m)) {
        return new IPv4([$m[1], $m[2], $m[3], $m[4]]);
    }

    return false;
}

function parseCIDR($addr) {

    $m = preg_split('/\//', $addr);
    $ip = parseIPv4($m[0]);
    $mask = $m[1];

    return new CIDR($ip, $mask);
}

class IPv4 {
    private $masks = [
        32 => [0xff, 0xff, 0xff, 0xff],
        31 => [0xff, 0xff, 0xff, 0xfe],
        30 => [0xff, 0xff, 0xff, 0xfc],
        29 => [0xff, 0xff, 0xff, 0xf8],
        28 => [0xff, 0xff, 0xff, 0xf0],
        27 => [0xff, 0xff, 0xff, 0xe0],
        26 => [0xff, 0xff, 0xff, 0xc0],
        25 => [0xff, 0xff, 0xff, 0x80],
        24 => [0xff, 0xff, 0xff, 0x00],
        23 => [0xff, 0xff, 0xfe, 0x00],
        22 => [0xff, 0xff, 0xfc, 0x00],
        21 => [0xff, 0xff, 0xf8, 0x00],
        20 => [0xff, 0xff, 0xf0, 0x00],
        19 => [0xff, 0xff, 0xe0, 0x00],
        18 => [0xff, 0xff, 0xc0, 0x00],
        17 => [0xff, 0xff, 0x80, 0x00],
        16 => [0xff, 0xff, 0x00, 0x00],
        15 => [0xff, 0xfe, 0x00, 0x00],
        14 => [0xff, 0xfc, 0x00, 0x00],
        13 => [0xff, 0xf8, 0x00, 0x00],
        12 => [0xff, 0xf0, 0x00, 0x00],
        11 => [0xff, 0xe0, 0x00, 0x00],
        10 => [0xff, 0xc0, 0x00, 0x00],
        9 => [0xff, 0x80, 0x00, 0x00],
        8 => [0xff, 0x00, 0x00, 0x00],
        7 => [0xfe, 0x00, 0x00, 0x00],
        6 => [0xfc, 0x00, 0x00, 0x00],
        5 => [0xf8, 0x00, 0x00, 0x00],
        4 => [0xf0, 0x00, 0x00, 0x00],
        3 => [0xe0, 0x00, 0x00, 0x00],
        2 => [0xc0, 0x00, 0x00, 0x00],
        1 => [0x80, 0x00, 0x00, 0x00],
        0 => [0x00, 0x00, 0x00, 0x00],
    ];
    private $address = [];
    function __get($i) {
        return $this->address[(int)$i];
    }
    function __construct(array $addr) {
        for ($i = 0; $i < 4; $i++) {
            $v = (int)$addr[$i];
            if ($v < 0 || $v >255) {
                throw new \Exception('error address');
            }

            $this->address[$i] = $v;
        }
    }
    
    function mask($mask) {
        $addr = [];
        foreach ($this->masks[$mask] as $i => $v) {
            $addr[$i] = $this->address[$i] & $v;
        }
        return new IPv4($addr);
    }

    function isEqual(IPv4 $ip) {
        foreach ($this->address as $i => $v) {
            if ($v !== $ip->{$i}) {
                return false;
            }
        }
        return true;
    }
}

class CIDR {
    private $ipv4;
    private $mask = 0;
    function __construct(IPv4 $ip, $mask) {

        $this->ipv4 = $ip;
        $mask = (int)$mask;
        if ($mask < 0 || $mask > 32) {
            throw new \Exception('error mask');
        }
        $this->mask = $mask;
    }

    public function inRange(IPv4 $ip) {
        return $this->ipv4->mask($this->mask)->isEqual($ip->mask($this->mask));
    } 
}

