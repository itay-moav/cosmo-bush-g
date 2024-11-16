<?php
declare(strict_types=1);
namespace DirectorMoav\CosmoBushG\Actions\Transformers;


/**
 * Pass a callable that expects one parameter
 * that callable will return a new value.
 * If callable is null, nothing will change.
 * 
 * @param ?callable $encryption_method
 * @return callable
 */
function to_encrypted(?callable $encryption_method):callable{
    return function($value) use($encryption_method):string{
        if($encryption_method){
            return $encryption_method($value);
        }
        return $value;
    };
}


/**
 * If the expected output has a different fields name than what the
 * normalized collection (input) has, this is where you let the system
 * know about it.
 * The schema will have the expected output, the original fieldname is the name in the input 
 * you would like to use for the schema field name
 * 
 * input = [
 *  'fieldOne'=>'babaYaga'
 * ]
 * 
 * schema = [
 *  'f1' => [api_map('fieldOne')]
 * ];
 * 
 * after applier runs
 * 
 * output = [
 *  'f1' => 'babaYaga'
 * ]
 * 
 * 
 * @param string|int $original_fieldname
 * @return callable
 */
function api_map($original_fieldname):callable{
    return function($value,string $field_name,array $normalized_collection) use($original_fieldname){
        return $normalized_collection[$original_fieldname] ?? null;
    };
}

/**
 * On a value that is null, replace with default. ONLY NULL!
 * 
 * @param mixed $default
 * @return callable
 */
function default_value($default):callable{
    return function($value) use($default){
        if($value===null || $value===''){
            return $default;
        }
        return $value;
    };
}

/**
 * defaults the field to '' if no value given
 * @return callable
 */
function default_empty_string():callable{
    return function($value){
        if($value===null){
            return '';
        }
        return $value;
    };
}
/**
 * defaults the field to null if the value is empty string
 * @return callable
 */
function empty_string_to_null():callable{
    return function($value){
        if($value===''){
            return null;
        }
        return $value;
    };
}
/**
 * @param mixed $overwrite_with
 * @return callable
 */
function force_overwrite(mixed $overwrite_with):callable{
    return function() use($overwrite_with){
        return $overwrite_with;
    };
}

/**
 * Wrapper for strtolower, $value has to be string
 * 
 * @return callable
 */
function lower_case():callable{
    return function (string $value):string{
        return \strtolower($value);
    };
}

/**
 * Wrapper to trim
 * 
 * @return callable
 */
function trim_string():callable{
    return function (string $value):string{
        return \trim($value);
    };
}
/**
 * returns only digits 0 to 9 in a string
 *    "jsdf834lddf12" will become "83412"
 * @return callable
 */
function keep_only_digits09():callable{
    return function (string $value):string{
        $ret = \preg_replace('/[^0-9]/', '', $value);
        if(!$ret) $ret = '';
        return $ret;
    };
}
/**
 * Removes extra chracters from left to keep length= $size
 * "abc1234567890" will be "1234567890" if $size=10
 */
function truncate_left_string(int $size):callable{
    return function (string $value) use ($size){
        return \strlen($value) > $size? \substr($value, -$size): $value;
    };
}

/**
 * Enforces max length by removing all excess chars to the right of the string
 * @param int $size
 * @return callable
 */
function truncate_string(int $size):callable{
    return function (string $value) use ($size){
        return \substr($value,0,$size);
    };
}

/**
 * Wrapper for substr
 * 
 * @param int $start
 * @param int $end
 * @return callable
 */
function sub_string(int $start,int $end):callable{
    return function(string $value) use ($start,$end){
        return \substr($value,$start,$end);
    };
}

/**
 * Assumes 2020-09-23T04:00:00.000Z
 * TODO make this more rubost
 * 
 * @return callable
 */
function keep_just_date():callable{
    return function (string $value):string{
        return \explode('T',$value)[0];
    };
}

/**
 * TODAY's SQL date
 *
 * @return callable
 */
function sql_date_now():callable{
    return function ():string{
        return (new \DateTime())->format('Y-m-d');
    };
}

