<?php


namespace App\Domain\Core;


use Symfony\Component\HttpFoundation\ParameterBag;

class ParameterBagTransformer
{
    /**
     * @param ParameterBag $parameterBag
     * @return array
     */
    public function transformQueryToContext(ParameterBag $parameterBag): array
    {
        $context = [
            'fields' => $this->getFields($parameterBag),
            'includes' => $this->getIncludes($parameterBag)
        ];

        return ['query' => $context];
    }

    /**
     * @param ParameterBag $parameterBag
     * @return array
     */
    private function getFields(ParameterBag $parameterBag): array
    {
        if (!$parameterBag->has('fields')) {
            return [];
        }

        return array_map(function ($fields) {
            return explode(',', $fields);
        }, $parameterBag->get('fields', []));
    }

    /**
     * @param ParameterBag $parameterBag
     * @return array
     */
    private function getIncludes(ParameterBag $parameterBag): array
    {
        if (!$parameterBag->has('includes')) {
            return [];
        }

        return explode(',', $parameterBag->get('includes'));
    }
}