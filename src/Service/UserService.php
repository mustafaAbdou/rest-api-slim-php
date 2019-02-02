<?php

namespace App\Service;

use App\Exception\UserException;
use App\Repository\UserRepository;

/**
 * Users Service.
 */
class UserService extends BaseService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Check if the user exists.
     *
     * @param int $userId
     * @return object
     */
    protected function checkUser($userId)
    {
        return $this->userRepository->checkUser($userId);
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->userRepository->getUsers();
    }

    /**
     * Get one user by id.
     *
     * @param int $userId
     * @return object
     */
    public function getUser($userId)
    {
        return $this->checkUser($userId);
    }

    /**
     * Search users by name.
     *
     * @param string $usersName
     * @return array
     */
    public function searchUsers($usersName)
    {
        return $this->userRepository->searchUsers($usersName);
    }

    /**
     * Create a user.
     *
     * @param array $input
     * @return object
     * @throws UserException
     */
    public function createUser($input)
    {
        $user = new \stdClass();
        $data = json_decode(json_encode($input), false);
        if (!isset($data->name)) {
            throw new UserException(UserException::USER_NAME_REQUIRED, 400);
        }
        $user->name = self::validateName($data->name);
        $user->email = null;
        if (isset($data->email)) {
            $user->email = self::validateEmail($data->email);
        }

        return $this->userRepository->createUser($user);
    }

    /**
     * Update a user.
     *
     * @param array $input
     * @param int $userId
     * @return object
     * @throws UserException
     */
    public function updateUser($input, $userId)
    {
        $user = $this->checkUser($userId);
        if (!isset($input['name']) && !isset($input['email'])) {
            throw new UserException(UserException::USER_INFO_REQUIRED, 400);
        }
        if (isset($input['name'])) {
            $user->name = self::validateName($input['name']);
        }
        if (isset($input['email'])) {
            $user->email = self::validateEmail($input['email']);
        }

        return $this->userRepository->updateUser($user);
    }

    /**
     * Delete a user.
     *
     * @param int $userId
     * @return string
     */
    public function deleteUser($userId)
    {
        $this->checkUser($userId);

        return $this->userRepository->deleteUser($userId);
    }
}
