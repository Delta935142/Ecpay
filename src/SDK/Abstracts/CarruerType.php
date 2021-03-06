<?php

namespace Delta935142\Ecpay\SDK\Abstracts;

abstract class CarruerType 
{
    // 無載具
    const None = '';
  
    // 會員載具
    const Member = '1';
  
    // 買受人自然人憑證
    const Citizen = '2';
  
    // 買受人手機條碼
    const Cellphone = '3';
  }