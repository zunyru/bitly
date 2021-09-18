<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Blacklist implements Rule
{

    protected $blacklist = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->blacklist = config('shorturl.blacklist');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return !Str::contains($value, $this->blacklist);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This :attribute contains blacklisted keyword.';
    }
}
