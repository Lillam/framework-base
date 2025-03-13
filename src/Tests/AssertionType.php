<?php

namespace Vyui\Tests;

enum AssertionType: string
{
    case EQUALS = "equals";
    case EQUALS_LOOSELY = "equalsLoosely";
    case GREATER_THAN = "greaterThan";
    case GREATER_THAN_OR_EQUAL = "greaterThanOrEqual";
    case LESS_THAN = "lessThan";
    case LESS_THAN_OR_EQUAL = "lessThanOrEqual";
    case COUNT = "count";
    // case CONTAINS = "contains";
    // case CONTAINS_ONLY = "containsOnly";
    // case CONTAINS_KEY = "containsKey";
    case EMPTY = "empty";
    case NOT_EMPTY = "notEmpty";
    case INSTANCE_OF = "instanceOf";
    case TYPE = "type";
    case TRUE = "true";
    case FALSE = "false";
    case NULL = "null";
    case NOT_NULL = "notNull";
    // case SAME = "same";
    // case NOT_SAME = "notSame";
    case HAS_KEY = "hasKey";
    case HAS_VALUE = "hasValue";
    case IS_NULL = "isNull";
    case IS_NOT_NULL = "isNotNull";


    public function getMessage(string $prefix = ""): string
    {
        return $prefix . match ($this) {
            self::EQUALS => "Expected value to be equal to the actual value.",
            self::EQUALS_LOOSELY => "Expected value to be equal to the actual value loosely.",
            self::GREATER_THAN => "Expected value to be greater than the actual value.",
            self::GREATER_THAN_OR_EQUAL => "Expected value to be greater than or equal to the actual value.",
            self::LESS_THAN => "Expected value to be less than the actual value.",
            self::LESS_THAN_OR_EQUAL => "Expected value to be less than or equal to the actual value.",
            self::COUNT => "Expected value to have a count of %s.",
            self::EMPTY => "Expected value to be empty.",
            self::NOT_EMPTY => "Expected value to not be empty.",
            self::INSTANCE_OF => "Expected value to be an instance of %s.",
            self::TYPE => "Expected value to be of type %s.",
            self::TRUE => "Expected value to be true.",
            self::FALSE => "Expected value to be false.",
            self::NULL => "Expected value to be null.",
            self::NOT_NULL => "Expected value to not be null.",
            self::HAS_KEY => "Expected value to have the key %s.",
            self::HAS_VALUE => "Expected value to have the value %s.",
            self::IS_NULL => "Expected value to be null.",
            self::IS_NOT_NULL => "Expected value to not be null.",
        };
    }
}
