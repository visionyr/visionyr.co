<?php

namespace App\Services\Blueprint;

interface BlueprintGenerator
{
    /**
     * Generate a blueprint from a founder's answers.
     *
     * @param  array{brand_name: string, industry: string, audience: string, price_position: string, vision: string}  $answers
     */
    public function generate(array $answers): GeneratedBlueprint;
}
