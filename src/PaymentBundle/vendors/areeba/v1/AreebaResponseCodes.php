<?php

namespace PaymentBundle\vendors\areeba\v1;

class AreebaResponseCodes
{
    protected static $statuses = [
        "REFUNDED" => "6", // The authorized amount for this order, in full or excess, has been captured successfully.
        "REFUND_FAILED" => "7", // The authorized amount for this order, in full or excess, has been captured successfully.
        "FAILED" => "13", // The payment has not been successful.
        "CAPTURED" => "14", // The authorized amount for this order, in full or excess, has been captured successfully.
        "UNKNOWN" => "15", // The result of the operation is unknown
        "PENDING" => "20", // The operation is currently in progress or pending processing
//        202 => "47", // Expired Card Used
//        231 => "47", // The card number is invalid
//        481 => "20", // This transaction may be suspicious, your bank holds for further confirmation.
//        810 => "7", // You already requested Refund for this Transaction ID
//        811 => "7", // Amount is above or below the invoice and also the minmum balance
//        812 => "6", // Refund request is sent to Operation for Approval. You can track the Status
//        813 => "7", // You are not authorized to view this transaction
//        0404 => "3", // You don't have permissions
//        4000 => "2", // Valid Secret Key
//        4001 => "10", // Missing parameters
//        4002 => "3", // Invalid Credentials
//        4003 => "60", // There are no transactions available
//        4006 => "61", // Your time interval should be less than 60 days
//        4007 => "63", // Currency code used is invalid. Only 3 character ISO currency codes are valid.
//        4008 => "64", // Your SITE URL is not matching with your profile URL
//        4012 => "65", // PayPage created successfully
//        4013 => "66", // Your 'amount' post variable should be between 0.27 and 5000.00 USD
//        4014 => "68", // Products titles, Prices, quantity are not matching
//        4090 => "62", // Data Found
//        4091 => "60", // Transaction Count is 0
//        4404 => "3", // You don't have permissions to create an Invoice
//        4094 => "67", // Your total amount is not matching with the sum of unit price amounts per quantity
//        5000 => "5", // Payment has been rejected
//        5001 => "4", // Payment has been accepted successfully
//        5002 => "4", // Payment has been forcefully accepted
//        5003 => "6" // Payment has been refunded
    ];
    protected static $codes    = [
        "PAYMENT" => "4", // Payment is completed successfully
        "REFUND" => "7", // Payment is completed successfully
    ];

    public function getGenericResponseStatus($key)
    {
        return static::$statuses[$key];
    }

    public function getGenericCommandCode($key)
    {
        return static::$codes[$key];
    }
}