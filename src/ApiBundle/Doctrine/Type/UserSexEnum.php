<?php namespace ApiBundle\Doctrine\Type;

class UserSexEnum extends Enum
{
    const USER_SEX_MAN = 'man';
    const USER_SEX_WOMAN = 'woman';
    const USER_SEX_OTHER = 'other';

    protected $name = 'enum_user_sex';
    protected $values = [
        self::USER_SEX_MAN,
        self::USER_SEX_WOMAN,
        self::USER_SEX_OTHER
    ];
}