<?php 
declare(strict_types=1);
namespace DirectorMoav\CosmoBushG\Actions\Validators;
use DirectorMoav\CosmoBushG\Actions\CosmoValidationException;




/**
 *
 * @param string $error_message
 * @return callable
 */
function is_mandatory(string $error_message='%%% is required'):callable{
    return function($value,string $field_name) use($error_message):string{
        if($value === null){
            throw new CosmoValidationException(str_replace('%%%', $field_name,$error_message));
        }
        return $value;
    };
}

/**
 *
 * @param int $min_length
 * @param string $error_message
 * @return callable
 */
function is_min_length(int $min_length,string $error_message='%%% has to be longer than '):callable{
    return function($value,string $field_name) use($min_length,$error_message):string{
        if(strlen($value) < $min_length){
            throw new CosmoValidationException(str_replace('%%%', $field_name,$error_message) . ($min_length-1) . ' characters');//TODO obviously this message is not dynamic
        }
        return $value;
    };
}

/**
 *
 * @param int $max_length
 * @param string $error_message
 * @return callable
 */
function is_max_length(int $max_length,string $error_message='%%% has to be shorter than '):callable{
    return function($value,string $field_name) use($max_length,$error_message):string{
        if(strlen($value) > $max_length){
            throw new CosmoValidationException(str_replace('%%%', $field_name,$error_message) . ($max_length+1) . ' characters');//TODO obviously this message is not dynamic
        }
        return $value;
    };
}

/**
 * @param string $error_message
 * @return callable
 */
function is_phone_us(string $error_message='%%% is not a legit phone number'):callable{
    return function($value,string $field_name) use($error_message):string{
        if(preg_match("/\d{10}/",$value) !== 1){
            throw new CosmoValidationException(str_replace('%%%', $value,$error_message));
        }
        return $value;
    };
}


/**
 * 
 * @param string $error_message
 * @param string $pattern
 * @throws CosmoValidationException
 * @return callable
 */
function is_matching_regexp(string $pattern,string $error_message='%%% does not match'):callable{
    return function(string $value) use($error_message,$pattern):string{
        if(preg_match($pattern, $value) !== 1){
            throw new CosmoValidationException(str_replace('%%%', $value,$error_message));
        }
        return $value;
    };
}

/**
 *
 * @param string $error_message
 * @return callable
 */
function is_date_mmddyyyy(string $error_message = '%%% must be in the format mm/dd/yyyy'): callable {
    // Regular expression for mm/dd/yyyy format
    $pattern = '/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/';
    $validator = is_matching_regexp($pattern, $error_message);
    return function ($value, string $field_name) use ($validator): string {
        return $validator($value);
    };
}

