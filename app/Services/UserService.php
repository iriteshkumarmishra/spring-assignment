<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Collection;

class UserService
{

    /**
     * Method to create a new user with the associated address
     *
     * @param array $filters
     * @return object
     */
    public function createUser($data): User|null
    {
        $user = null;
        DB::transaction(function () use ($data, &$user) {
            // Create the address first
            $address = UserAddress::firstOrCreate(
                [
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip' => $data['zip'],
                    'country' => $data['country']
                ]
            );

            // Now create the user with the generated/fetched address ID
            $user = User::create([
                'name' => $data['name'],
                'age' => $data['age'],
                'address_id' => $address->id
            ]);
        });

        return $user;
    }

    /**
     * Method to list users with optional filters
     *
     * @param array $filters
     * @return object
     */
    public function listUsers(array $filters = []): Collection
    {
        $query = User::query();

        // Apply filters
        if (isset($filters['age'])) {
            $query->where('age', $filters['age']);
        }

        if (isset($filters['points'])) {
            $query->where('points', '=', $filters['points']);
        }

        // Apply search by name
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        // Apply sorting
        if (isset($filters['sort_key']) && in_array($filters['sort_key'], ['name', 'points'])) {
            $direction = $filters['sort_order'] ?? 'asc'; // Default to ascending
            $query->orderBy($filters['sort_key'], $direction);
        }

        return $query->get();
    }

    /**
     * Add points to a user.
     *
     * @param User $user
     * @param int $points
     * @return User
     */
    public function addPoints(User $user, int $points): User
    {
        return DB::transaction(function () use ($user, $points) {
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            $user->points += $points;
            $user->save();

            return $user;
        });
    }

    /**
     * Remove points from a user.
     *
     * @param User $user
     * @param int $points
     * @return User
     */
    public function removePoints(User $user, int $points): User
    {
        return DB::transaction(function () use ($user, $points) {
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            // Ensure points don't go below 0
            $user->points = max(0, $user->points - $points);
            $user->save();

            return $user;
        });

        return $user;
    }

    /**
     * Get users grouped by score with average age.
     *
     * @return array
     */
    public function getUsersGroupedByScore(): array
    {
        // Fetch users and group by points
        $usersGrouped = User::select('points', 'name', 'age')
            ->get()
            ->groupBy('points')
            ->map(function ($group) {
                return [
                    'names' => $group->pluck('name')->toArray(),
                    'average_age' => round($group->avg('age'), 2), // rounding average_age for better visibility
                ];
            })
            ->toArray();

        return $usersGrouped;
    }

}