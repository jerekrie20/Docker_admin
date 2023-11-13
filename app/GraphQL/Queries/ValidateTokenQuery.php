<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ValidateTokenQuery
{
    public function validate($rootValue, array $args, $context, $resolveInfo)
    {
        // Assuming the token is already set in the request headers and
        // you are using a library like Passport or Sanctum to set the user based on the token
        $user = Auth::user();

        // If no user is found, the token is invalid
        if (!$user) {
            return [
                'isValid' => false,
                'user' => null,
            ];
        }

        // If a user is found, the token is valid
        return [
            'isValid' => true,
            'user' => $user,
        ];
    }
}
