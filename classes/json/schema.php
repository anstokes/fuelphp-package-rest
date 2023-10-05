<?php

namespace Anstech\Rest\Json;

/**
 * Model to convert ORM model to JSON schema
 */
class Schema
{
    /**
     * Convert field properties to relevant format
     *
     * @param array $properties properties
     *
     * @return array
     */
    public static function convertProperties($properties)
    {
        $array = [];

        foreach ($properties as $property => $details) {
            switch ($property) {
                case 'label':
                    $array['label'] = $details;
                    break;

                // Data Type
                case 'data_type':
                    $array['type'] = static::convertDataType($details);
                    break;

                // Validation
                case 'validation':
                    if ($validation = static::convertValidation($details, $properties)) {
                        $array['validation'] = $validation;
                    }
                    break;

                default:
                    // Do nothing
                    break;
            }
        }

        return $array;
    }


    /**
     * Convert data type
     *
     * @param string $data_type data type
     *
     * @return string
     */
    protected static function convertDataType($data_type)
    {
        switch ($data_type) {
            case 'bool':
                return 'Boolean';

            case 'varchar':
                return 'Text';

            default:
                return $data_type;
        }
    }


    /**
     * Convert validation
     *
     * @param array $validation validation
     * @param array $properties properties
     *
     * @return boolean|array
     */
    protected static function convertValidation($validation, $properties)
    {
        if (isset($properties['data_type']) && ($properties['data_type'] == 'bool')) {
            return false;
        }

        $array = [];

        // Supported types which are not yet implemented:
        // number, email, choices

        foreach ($validation as $key => $rule) {
            if (is_numeric($key)) {
                switch ($rule) {
                    case 'required':
                        $array[] = 'required()';
                        break;

                    default:
                        // Do nothing
                        break;
                }
            } else {
                switch ($key) {
                    case 'match_pattern':
                        $array[] = 'regex(' . $rule[0] . ')';
                        break;

                    case 'min_length':
                        $array[] = 'minLength(' . $rule[0] . ')';
                        break;

                    case 'max_length':
                        $array[] = 'maxLength(' . $rule[0] . ')';
                        break;

                    case 'min_value':
                        $array[] = 'minValue(' . $rule[0] . ')';
                        break;

                    case 'max_value':
                        $array[] = 'maxValue(' . $rule[0] . ')';
                        break;

                    default:
                        // Do nothing
                        break;
                }
            }
        }

        return $array;
    }
}
