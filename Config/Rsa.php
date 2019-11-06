<?php
/**
 * Created by PhpStorm
 * User: wulinzhu
 * Date: 19/10/28 上午11:27
 * Email: 18515831680@163.com
 * rsa 公钥、私钥配置
 */

return [

    'common' => [
        'public_key' => <<<eof
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC/m9o2Np8Vm5hqGXwW78fkJ6Nz
I+pJgXkblfyvpjO6AJ9o+ee3R4mgE7uJQBTdWiM3QYyrVZ+ksek6zusuw6kqOJfl
Jrq4UQiPcvc/z2wP6cXI2moyeD/Yu+PA5h0Itm7ykDsIZHWyHp7ddsYqqprBgF+M
H0ipBnhzLyP9cpvOJQIDAQAB
-----END PUBLIC KEY-----
eof,
        'private_key' => <<<eof
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC/m9o2Np8Vm5hqGXwW78fkJ6NzI+pJgXkblfyvpjO6AJ9o+ee3
R4mgE7uJQBTdWiM3QYyrVZ+ksek6zusuw6kqOJflJrq4UQiPcvc/z2wP6cXI2moy
eD/Yu+PA5h0Itm7ykDsIZHWyHp7ddsYqqprBgF+MH0ipBnhzLyP9cpvOJQIDAQAB
AoGAOakjD7F8SjpasYMdzqE13Dj5fQrP1HL8CQsn0YwIgO7zCdo6mvMSPXPyuajp
HnujoVs+g8juk9deQm5GY/pJi39XfGOXfrT71y4SCtjYRvf0z1QK46/BDPToFtIQ
7VGFCczTwYOU+R5649nONPU8SeaC3ke3ePRjdb07peWxzHkCQQDxMq0JHD6I1hep
TfSI0nidEAwvkvTkzCyzI8sxTCUObfGgNWSGQFj4pd92/xpVwatvpNK1SAPhX7Ph
HNgCQW4vAkEAy14cAxarrC2jF3M7TF/ojdk8GR6i6zdHH/iiyQryHFRzX/Hub6km
iCFosHDCj1j7B5tuYS3VkE9Db8JPMUWn6wJAWD5LEj80Hcn+JCHyKjCekg/x9WHV
mPOeEvgwedu63vmYYh3GpltacbX1+MpCL0fI2fK6aDptuQHThLORz9lyXwJBAKj9
CJXgbsLiIVXJIQsz3xCP5QqSlJqUEnwUpWAdwlWcL0sYxCLxEd2otU94Q5POQrpt
g+kopwAFfWoOsrOkyAUCQQDKJv75XUITGbrmwTpiJlt+DgZxIFkmoww093TH6c+a
q1dY+mRqmJ4UxyLCO6viSROSm1R0Ga7aK0oLRNCIh/gv
-----END RSA PRIVATE KEY-----
eof,
    ],

    'others' => [
        //...
    ],
];