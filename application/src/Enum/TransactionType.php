<?php
namespace App\Enum;
enum TransactionType: string
{
    case INCOME = 'Income';
    case EXPENSE ='Expense';

}