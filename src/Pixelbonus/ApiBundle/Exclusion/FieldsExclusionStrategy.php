<?php

/*
 * Copyright 2013 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Pixelbonus\ApiBundle\Exclusion;

use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Context;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;

use Symfony\Component\Yaml\Parser;

class FieldsExclusionStrategy implements ExclusionStrategyInterface
{
    private $fields;
    private $namingStrategy;

    public function __construct($fields, PropertyNamingStrategyInterface $namingStrategy)
    {
        $this->fields = $this->parseFields($fields);
        $this->namingStrategy = $namingStrategy;
    }

    private function parseFields($fieldsString) {
        $fields = explode(',', $fieldsString);
        $yamlParser = new Parser();
        $fields = $yamlParser->parse('['.$fieldsString.']');
        return $fields;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkipClass(ClassMetadata $metadata, Context $navigatorContext)
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldSkipProperty(PropertyMetadata $property, Context $navigatorContext)
    {
        // name,{main_address:[zip_code,{point:[latitude,longitude]}]}
        $filteredMetadataStack = $this->filterMetadataStack($navigatorContext->getMetadataStack());
        $filteredMetadataStack->push($property);
        $part = $this->fields;
        foreach($filteredMetadataStack as $curKey) {
            $part = $this->findArrayPart($this->namingStrategy->translateName($curKey), $part);
        }
        if($part != false) {
            return false;
        }
        return true;
    }

    private function filterMetadataStack(\SplDoublyLinkedList $metadataStack) {
        $filteredMetadataStack = new \SplQueue();
        foreach($metadataStack as $curProperty) {
            if($curProperty instanceof PropertyMetadata) {
                $filteredMetadataStack->unshift($curProperty);
            }
        }
        return $filteredMetadataStack;
    }

    private function findArrayPart($key, $fields) {
        if(is_array($fields)) {
            // Maybe its a key (e.g. main_address)
            if(isset($fields[$key])) {
                return $fields[$key];
            }
            if(($index = array_search($key, $fields, true)) !== false) {
                return $fields[$index];
            }
            // Maybe its an int (e.g. 0) so we check inside for our key
            foreach($fields as $curField) {
                if(isset($curField[$key])) {
                    return $curField[$key];
                }
            }
        }
        // Or maybe its a string
        else if(is_string($fields) && $fields == $key) {
            return $key;
        }
        return false;
    }
}

